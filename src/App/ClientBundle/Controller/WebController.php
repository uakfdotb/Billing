<?php

namespace App\ClientBundle\Controller;

use App\ClientBundle\Entity\Client,
    App\ClientBundle\Entity\Product,
    App\ClientBundle\Entity\ProductOrder,
    App\ClientBundle\Entity\ClientProduct,
    App\ClientBundle\Form\WebClientType,
    App\AdminBundle\Business\Order\Utils as OrderUtils,
    App\AdminBundle\Business\Order\Constants as OrderConstants,
    App\AdminBundle\Business\Product\Constants as ProductConstants,
    App\AdminBundle\Business\Invoice\Constants as InvoiceConstants,
    App\AdminBundle\Business\AutomationGroup\Utils as AutomationUtils,
    App\AdminBundle\Business\ClientProduct\Constants as ClientProductConstants,
    App\AdminBundle\Business\Invoice\Utils as InvoiceUtils,
    App\AdminBundle\Business\GlobalUtils;

use Symfony\Component\Form\FormError,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent,
    Maxmind\MinFraud\CreditCardFraudDetection;

class WebController extends BaseController
{
    public function homeAction()
    {
        return $this->render('AppClientBundle:Web:home.html.twig');
    }

    public function pricingAction()
    {
        $gid = $this->getRequest()->query->get('gid');   // Group ID

        if (!empty($gid))
        {
            $repository = $this->getDoctrine()->getRepository('AppClientBundle:Product');

            $products = array_filter($repository->findByIdProductGroup($gid), function($entry)
            {
                return $entry->getIsAvailable();
            });
        }

        if (empty($products))
        {
            throw $this->createNotFoundException('No products found');
        }

        return $this->render(
            'AppClientBundle:Web:pricing.html.twig',
            array(
                'products' => $products,
                'config'   => $this->get('app_admin.helper.common')->getConfig(),
                'groups'   => $this->getDoctrine()->getRepository('AppClientBundle:ProductGroup')->findAll()
            )
        );
    }

    public function orderAction()
    {
        $request = $this->getRequest();

        $pid = $request->query->get('pid');   // Product ID

        if (!empty($pid))
        {
            $product = $this->getDoctrine()->getRepository('AppClientBundle:Product')->findOneById($pid);
        }

        if (empty($product) || !$product->getIsAvailable() || $product->getStock() < 1)
        {
            return $this->pricingAction();
        }

        if (($request->getMethod() == 'POST') && ($this->get('form.csrf_provider')->isCsrfTokenValid('order_form', $request->get('token'))))
        {
            $result = $this->handleClientForm($client);

            if ($result === true)
            {
                $this->completeSignUpAndDoLogin($client);

                $invoice = $this->createOrder($client, $product);

                if (!empty($paymentInfo['redirectURL']))
                {
                    $destiny = $paymentInfo['redirectURL'];
                }
                elseif ($product->getIsRedirectUnpaidInvoice() && ($invoice !== 0) && ($invoice->getStatus() != InvoiceConstants::STATUS_PAID))
                {
                    $destiny = $this->get('router')->generate('app_client_invoice_show', array('id' => $invoice->getId(), 'token' => $invoice->getInvoiceAccessToken()));
                }
                else
                {
                    $destiny = $this->get('router')->generate('app_client_default');
                }

                return $this->redirect($destiny);
            }
        }

        return $this->render(
            'AppClientBundle:Web:order.html.twig',
            array(
                'product'   => $product,
                'countries' => $this->get('app_admin.helper.mapping')->getMapping('country'),
                'config'    => $this->get('app_admin.helper.common')->getConfig(),
                'errors'    => empty($result) ? [] : (array) $result
            )
        );
    }

    public function orderLoginAction()
    {
        $request = $this->getRequest();

        if (($request->getMethod() != 'POST') || (!$this->get('form.csrf_provider')->isCsrfTokenValid('order_form', $request->get('token'))))
        {
            return new JsonResponse(array('error' => 1));
        }

        // Obtain user
        $repository = $this->getDoctrine()->getRepository('AppUserBundle:User');
        $user = $repository->findOneByEmail($request->get('email'));

        if (empty($user))
        {
            return new JsonResponse(['error' => 2]);
        }

        // Check password
        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);
        $encoded_pass = $encoder->encodePassword($request->get('password'), $user->getSalt());

        if ($user->getPassword() != $encoded_pass)
        {
            return new JsonResponse(['error' => 3]);
        }

        // Login the client
        $token = new UsernamePasswordToken($user, null, 'secured_customer_area', $user->getRoles());
        $this->get('security.context')->setToken($token);

        // Broadcast the login event
        $event = new InteractiveLoginEvent($request, $token);
        $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);

        return new JsonResponse(['success' => true]);
    }

    protected function handleClientForm(&$client)
    {
        $user    = $this->getUser();
        $request = $this->getRequest();

        // Already authenticated?
        if (!empty($user))
        {
            // Confirm the email address
            if ($user->getEmail() != $request->get('email'))
            {
                return ['email' => 'This is not your e-mail address'];
            }
        }
        // New user?
        else
        {
            // New model
            $user = new Client;

            // Use the form to bind model with request input
            $type = new WebClientType;
            $request->request->add([$type->getName() => $request->request->all()]);

            $form = $this->createForm($type, $user);
            $form->bind($request);

            // Trigger form and model validation
            if (!$form->isValid())
            {
                $type->getErrors($form);
            }
        }

        $client = $user;

        return true;
    }

    protected function completeSignUpAndDoLogin($user)
    {
        if (empty($user))
        {
            return false;
        }

        // Prepare entity for persistence
        $user->setUsername($user->getEmail());
        $user->setPlainPassword($user->getPassword());
        $user->setStatus(1);
        $user->setEnabled(1);
        $user->setAddedDate(new \DateTime);

        // Persist entity (database INSERT)
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        // Login the client
        $token = new UsernamePasswordToken($user, null, 'secured_customer_area', $user->getRoles());
        $this->get('security.context')->setToken($token);

        // Broadcast the login event
        $event = new InteractiveLoginEvent($this->getRequest(), $token);
        $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);

        return true;
    }

    protected function createOrder($client, $product)
    {
        if (empty($client) || empty($product))
        {
            return false;
        }

        $entityManager = $this->getDoctrine()->getEntityManager();

        $orderNumber = $this->get('app_admin.helper.common')->generateOrderNumber(10);

        $idOrderPaymentTerm = array_search($product->getShortestPeriodicity(), Product::$periodicities);

        // ClientInvoice
        $iid = OrderUtils::generateInvoice($this->container, $client->getId(), $product->getId(), $idOrderPaymentTerm, $orderNumber, $invoice);
        if(!is_null($iid)) InvoiceUtils::sendInvoiceEmail($this->container, $invoice, 'invoice');

        // ClientProduct
        $cpUsername = substr(preg_replace("/[^A-Za-z0-9 ]/", '', $this->getRequest()->request->get('domain')), 0, 7);
        $mc = $this->get('app_admin.helper.mcrypt');
        $hc = $this->get('app_admin.helper.common');
        $cp = new ClientProduct;
        $cp->setIdPaymentTerm    ( $idOrderPaymentTerm                                 );
        $cp->setIpAddress        ( ''                                                  );
        $cp->setEncryptedUsername( ''                                                  );
        $cp->setEncryptedPassword( ''                                                  );
        $cp->setIdServer         ( 0                                                   );
        $cp->setCost             ( $product->getShortestPeriodicityPrice() ?: 0        );
        $cp->setIdSchedule       ( $idOrderPaymentTerm                                 );
        $cp->setNextDue          ( new \DateTime                                       );
        $cp->setTaxGroup         ( $product->getTaxGroup() ?: 0                        );
        $cp->setReminders        ( isset($invoice) ? $invoice->getReminders() : 0      );
        $cp->setOverdueNotices   ( isset($invoice) ? $invoice->getOverdueNotices() : 0 );
        $cp->setIdProduct        ( $product->getId()                                   );
        $cp->setIdClient         ( $this->getUser()->getId()                           );
        $cp->setStatus           ( ClientProductConstants::STATUS_PENDING              );
        $cp->setDomain           ( $this->getRequest()->request->get('domain')         );
        $cp->setEncryptedUsername( $mc->encrypt($cpUsername)                           );
        $cp->setEncryptedPassword( $mc->encrypt($hc->generateRandString(6))            );

        $entityManager->persist($cp);
        if(isset($invoice)){
            $invoice->setIdClientProduct($cp->getId());
            $entityManager->persist($invoice);
        }
        $entityManager->flush();

        // ProductOrder
        $order = new ProductOrder;
        $order->setIdProduct    ( $product->getId()                       );
        $order->setIdClient     ( $client->getId()                        );
        $order->setTimestamp    ( new \DateTime                           );
        $order->setStatus       ( OrderConstants::ORDER_STATUS_PROCESSING );
        $order->setOrderNumber  ( $orderNumber                            );
        $order->setClientProduct( $cp->getId()                            );
        $order->setMaxmindData  ( $this->getMaxmindData($client, $fraud)  );
        $order->setIpAddress    ( $this->getRequest()->getClientIp()      );
        $order->setIdInvoice    ( isset($invoice) ?$invoice->getId() : 0  );

        if ($fraud)
        {
            $order->setStatus  ( OrderConstants::ORDER_STATUS_FRAUDULENT );
            if($iid > 0){
                $invoice->setStatus( InvoiceConstants::STATUS_WRITTEN_OFF);
                $entityManager->persist($invoice);
            }
        }

        $entityManager->persist($order);
        $entityManager->flush();

        if (!$fraud)
        {
            $this->handleAutomation($order, $product, $invoice);
        }

        // Should be included in the "if (!$fraud)" block?
        OrderUtils::decreaseProductStock($this->container, $product->getId());
        OrderUtils::sendConfirmation($this->container, $order);

        return $invoice;
    }

    public function getMaxmindData($client, &$fraudulent = false)
    {
        $config = $this->get('app_admin.helper.common')->getConfig();

        if (!$config->getMaxmindEnabled())
        {
            return [];
        }

        $inputs = array(
            "license_key" => $config->getMaxmindlicensekey(),
            "i"           => $this->container->get('request')->getClientIp(),
            "city"        => $client->getCity(),
            "region"      => $client->getState(),
            "postal"      => $client->getPostcode(),
            "country"     => GlobalUtils::getCountryFromId($client->getIdCountry()),
            "custPhone"   => $client->getPhone(),
            "emailMD5"    => md5($client->getEmail())
        );

        $ccfs = new CreditCardFraudDetection;
        $ccfs->input($inputs);
        $ccfs->query();
        $outputs = $ccfs->output();

        if (isset($outputs['riskScore']))
        {
            $fraudulent = ( $outputs['riskScore'] > $config->getMaxmindRiskScoreThreshold() );
        }

        return $outputs;
    }

    public function handleAutomation($order, $product, $invoice)
    {
        $helper = $this->get('app_admin.helper.automation_helper');
        
        // Handle Automation (version 1)
        $helper->handlePostOrdered($order);

        // Hmmm... the status here is either "open" or "fraud"...
        if ($order->getStatus() == OrderConstants::ORDER_STATUS_PROCESSING && (($invoice == 0) ||$invoice->getStatus() == InvoiceConstants::STATUS_PAID))
        {
            $helper->handlePostPaid($order);
        }
        elseif ($order->getStatus() == OrderConstants::ORDER_STATUS_ACTIVE)
        {
            $helper->handlePostAccepted($order);
        }

        return;
    }
}

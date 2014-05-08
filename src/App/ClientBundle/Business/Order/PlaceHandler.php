<?php
namespace App\ClientBundle\Business\Order;

use App\AdminBundle\Business as AdminBusiness;
use App\ClientBundle\Entity;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PlaceHandler extends AdminBusiness\Base\BaseCreateHandler
{
    public $product = null;
    public $isSuccess = false;
    public $targetUrl = '';

    public function getDefaultValues()
    {
        $model            = new PlaceModel();
        $model->container = $this->container;
        $model->pid       = $this->container->get('request')->query->get('pid', 0);
        $this->product    = $this->container->get('doctrine')->getRepository('AppClientBundle:Product')->findOneById($model->pid);

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('pid', 'hidden');
        $builder->add('placeNew', 'hidden');

        $builder->add('firstname', 'text', array(
            'attr'     => array(
                'placeholder' => 'FIRST_NAME'
            ),
            'required' => false
        ));

        $builder->add('lastname', 'text', array(
            'attr'     => array(
                'placeholder' => 'LAST_NAME'
            ),
            'required' => false
        ));

        $builder->add('company', 'text', array(
            'attr'     => array(
                'placeholder' => 'COMPANY'
            ),
            'required' => false
        ));

        $builder->add('address1', 'text', array(
            'attr'     => array(
                'placeholder' => 'ADDRESS_1'
            ),
            'required' => false
        ));

        $builder->add('address2', 'text', array(
            'attr'     => array(
                'placeholder' => 'ADDRESS_2'
            ),
            'required' => false
        ));

        $builder->add('city', 'text', array(
            'attr'     => array(
                'placeholder' => 'CITY'
            ),
            'required' => false
        ));

        $builder->add('state', 'text', array(
            'attr'     => array(
                'placeholder' => 'STATE'
            ),
            'required' => false
        ));

        $builder->add('postcode', 'text', array(
            'attr'     => array(
                'placeholder' => 'POST_CODE'
            ),
            'required' => false
        ));

        $builder->add('idCountry', 'choice', array(
            'attr'     => array(
                'placeholder' => 'COUNTRY'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('country')
        ));

        $builder->add('phone', 'text', array(
            'attr'     => array(
                'placeholder' => 'PHONE'
            ),
            'required' => false
        ));

        $builder->add('email', 'text', array(
            'attr'     => array(
                'placeholder' => 'EMAIL'
            ),
            'required' => false
        ));

        $builder->add('password', 'password', array(
            'attr'     => array(
                'placeholder' => 'PASSWORD'
            ),
            'required' => false
        ));
        $builder->add('idOrderPaymentTerm', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PAYMENT_TERM'
            ),
            'required' => false,
            'choices'  => AdminBusiness\Order\Utils::getPaymentTerms($this->product)
        ));

        if ($this->product->getIdType() == AdminBusiness\Product\Constants::PRODUCT_TYPE_CPANEL) {
            $helperCpanel = $this->container->get('app_admin.helper.cpanel');

            $builder->add('domain', 'text', array(
                'attr'     => array(
                    'placeholder' => 'DOMAIN'
                ),
                'required' => false
            ));
            $builder->add('accountUsername', 'text', array(
                'attr'     => array(
                    'placeholder' => 'ACCOUNT_USERNAME'
                ),
                'required' => false
            ));
            $builder->add('accountPassword', 'password', array(
                'attr'     => array(
                    'placeholder' => 'ACCOUNT_PASSWORD'
                ),
                'required' => false
            ));
        } else if ($this->product->getIdType() == AdminBusiness\Product\Constants::PRODUCT_TYPE_SOLUSVM) {
            $helperSolusvm = $this->container->get('app_admin.helper.solusvm');

            $builder->add('domain', 'text', array(
                'attr'     => array(
                    'placeholder' => 'DOMAIN'
                ),
                'required' => false
            ));
            $builder->add('accountUsername', 'text', array(
                'attr'     => array(
                    'placeholder' => 'ACCOUNT_USERNAME'
                ),
                'required' => false
            ));
            $builder->add('accountPassword', 'password', array(
                'attr'     => array(
                    'placeholder' => 'ACCOUNT_PASSWORD'
                ),
                'required' => false
            ));
        }

        return $builder;
    }

    public function onSuccess()
    {
        $helperUser = $this->container->get('app_admin.helper.user');
        $config     = $this->container->get('app_admin.helper.common')->getConfig();
        $idProduct  = $this->container->get('request')->query->get('pid', 0);
        $product    = $this->container->get('doctrine')->getRepository('AppClientBundle:Product')->findOneById($idProduct);

        $model        = $this->getForm()->getData();
        $helperCommon = $this->container->get('app_admin.helper.common');

        // Create client
        $client = new Entity\ClientDetail();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $client);
        $client->setPassword($helperUser->encodePassword($model->password));
        $client->setStatus(1);
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $idClient = $client->getId();

        // Check maxmind
        if ($config->getMaxmindEnabled()) {
            $maxmindResult = AdminBusiness\Order\Utils::checkMaxMind($this->container, $idClient);
            $maxmindRaw    = $maxmindResult['raw'];
            $maxmindResult = $maxmindResult['result'];

            $error = $maxmindResult['err'];
            if (!empty($error)) {
                parent::onSuccess();
                $this->errors[] = $error;
                $this->messages = array();
                return false;
            }

            $isFraudulent = false;
            $score        = isset($maxmindResult['riskScore']) ? $maxmindResult['riskScore'] : false;
            if ($score > $config->getMaxmindRiskScoreThreshold()) {
                $isFraudulent = true;
            }
        } else {
            $isFraudulent = false;
            $maxmindRaw   = 'status=Not checking';
        }

        // Process
        $orderNumber = $helperCommon->generateOrderNumber(10);

        $idEstimate = AdminBusiness\Order\Utils::generateEstimate($this->container, $idClient, $idProduct, $model->idOrderPaymentTerm, $orderNumber);

        $order = new Entity\ProductOrder();
        $order->setIdProduct($idProduct);
        $order->setIdClient($idClient);
        $order->setStatus(AdminBusiness\Order\Constants::ORDER_STATUS_OPEN);
        if ($isFraudulent) {
            $order->setStatus(AdminBusiness\Order\Constants::ORDER_STATUS_FRAUDULENT);
        }
        $order->setTimestamp(new \DateTime());
        $order->setIdOrderPaymentTerm($model->idOrderPaymentTerm);
        $order->setOrderNumber($orderNumber);
        $order->setIpAddress($_SERVER['REMOTE_ADDR']);
        $order->setIdEstimate($idEstimate);
        $order->setMaxmindData($maxmindRaw);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        AdminBusiness\Order\Utils::decreaseProductStock($this->container, $idProduct);
        AdminBusiness\AutomationGroup\Utils::handlePostOrdered($this->container, $order);

        // Handle CPANEL / SOLUSVM      
        $customData = json_decode($product->getCustomData(), true);
        if ($this->product->getIdType() == AdminBusiness\Product\Constants::PRODUCT_TYPE_CPANEL) {
            $idPackage    = $customData['cpanelIdPackage'];
            $helperCpanel = $this->container->get('app_admin.helper.cpanel');
            $helperCpanel->createUserFromPackage($model->domain, $model->accountUsername, $model->accountPassword, $idPackage, $model);
        } else if ($this->product->getIdType() == AdminBusiness\Product\Constants::PRODUCT_TYPE_SOLUSVM) {
            $idPackage     = $customData['solusvmIdPackage'];
            $helperSolusVm = $this->container->get('app_admin.helper.solusvm');
            $helperSolusVm->createUserFromPackage($model->domain, $model->accountUsername, $model->accountPassword, $idPackage, $model);
        }

        ////////////////////////////////////

        parent::onSuccess();

        $this->messages  = array('The order has been placed! Thank you! Please login with your new account to check your order: ' . $model->email);
        $this->isSuccess = true;

        // Login user
        $user = new AdminBusiness\Login\ClientSession();
        $user->setRole('ROLE_CLIENT');
        $user->setStaff($client);
        $token = new UsernamePasswordToken($user, null, 'secured_client_area', array('ROLE_CLIENT'));
        //$this->container->get('security.context')->setToken($token);
        $session = $this->container->get('request')->getSession();
        $session->set('_security_secured_client_area', serialize($token));

        // Set target
        if ($product->getIsRedirectUnpaidInvoice() == 1) {
            $router          = $this->container->get('router');
            $this->targetUrl = $router->generate('app_client_estimate_view', array('id' => $idEstimate));
        }
    }

    public function isAccept()
    {
        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST') {
            $form = $request->request->get('form');
            if (isset($form['placeNew'])) {
                return true;
            }
            return false;
        }

        return false;
    }
}
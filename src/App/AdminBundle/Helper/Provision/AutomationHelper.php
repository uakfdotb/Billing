<?php

namespace App\AdminBundle\Helper\Provision;

use App\AdminBundle\Business\Order\Constants as OrderConstants;
use App\AdminBundle\Business\ClientProduct\Constants as CPConstants;
use App\AdminBundle\Business\Invoice\Constants as InvoiceConstants;
use App\AdminBundle\Business\Product\Constants as ProductConstants;
use App\AdminBundle\Business\ServerGroup\Constants as SGConstants;
use App\AdminBundle\Business\ClientContact\Utils as CCUtils;
use App\ClientBundle\Entity\ClientProduct;
use App\ClientBundle\Entity\ProductOrder;
use App\ClientBundle\Entity\Product;
use App\ClientBundle\Entity\Server;
use App\ClientBundle\Entity\ServerGroup;
use App\ClientBundle\Entity\ClientEmail;

class AutomationHelper
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /* Triggered when a product is ordered, regardless of which product is ordered */
    public function handlePostOrdered(ProductOrder $order)
    {
        // Get the product and CP
        $doctrine = $this->container->get('doctrine');
        $product  = $doctrine->getRepository('AppClientBundle:Product')->findOneById($order->getIdProduct());
        $cp       = $doctrine->getRepository('AppClientBundle:ClientProduct')->findOneById($order->getClientProduct());

        if($product->getTriggerCreate() == OrderConstants::CREATE_ON_ORDER
            && $cp->getStatus() == CPConstants::STATUS_PENDING)
        {
            return $this->createProduct($cp, $product);
        }
        return 'Conditions for account creation not met';
    }

    /* Triggered when a product's invoice is paid for and that product is pending */
    public function handlePostPaid(ProductOrder $order)
    {
        // Get the product and CP
        $doctrine = $this->container->get('doctrine');
        $product  = $doctrine->getRepository('AppClientBundle:Product')->findOneById($order->getIdProduct());
        $cp       = $doctrine->getRepository('AppClientBundle:ClientProduct')->findOneById($order->getClientProduct());
        $invoice  = $doctrine->getRepository('AppClientBundle:ClientInvoice')->findOneById($order->getIdInvoice());

        if($product->getTriggerCreate() == OrderConstants::CREATE_ON_PAYMENT
            && (is_null($invoice)
            || $invoice->getStatus() == InvoiceConstants::STATUS_PAID)
            && $cp->getStatus() == CPConstants::STATUS_PENDING)
        {
            return $this->createProduct($cp, $product);
        }
        return 'Conditions for account creation not met';
    }

    /* Triggered when an order is manually accepted in the admin area */
    public function handlePostAccepted(ProductOrder $order)
    {
        // Get the product and CP
        $doctrine = $this->container->get('doctrine');
        $cp       = $doctrine->getRepository('AppClientBundle:ClientProduct')->findOneById($order->getClientProduct());
        $product  = $doctrine->getRepository('AppClientBundle:Product')->findOneById($order->getIdProduct());
        $request  = $this->container->get('request')->request->all();

        if($cp->getStatus() == CPConstants::STATUS_PENDING && isset($request['moduleCreate']))
        {
            $sendWelcomeEmail = isset($request['welcomeEmail']);
            return $this->createProduct($cp, $product, $sendWelcomeEmail);
        }
        return 'Conditions for account creation not met';
    }

    /* Triggered by cron or admin area button */
    public function suspendClientProduct(ClientProduct $cp)
    {
        $doctrine = $this->container->get('doctrine');
        $mcrypt   = $this->container->get('app_admin.helper.mcrypt');

        // Check it's active
        if($cp->getStatus() == CPConstants::STATUS_ACTIVE)
        {
            // Suspend
            $server    = $doctrine->getRepository('AppClientBundle:Server')->findOneById($cp->getIdServer());
            $product   = $doctrine->getRepository('AppClientBundle:Product')->findOneById($cp->getIdProduct());
            $name      = ProductConstants::getProductTypes()[$product->getIdType()];
            $className = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s\\%s", ucfirst($name), $name);
            $method    = sprintf("%s_SuspendAccount", $name);
            $class     = new $className($this->container,
                $mcrypt->decrypt($server->getEncryptedIp()),
                $mcrypt->decrypt($server->getEncryptedUser()),
                $mcrypt->decrypt($server->getEncryptedPass())
            );

            $result = $class->$method($mcrypt->decrypt($cp->getEncryptedUsername()));

            if($result === 'success')
            {
                // Send email & mark suspended
                $this->sendSuspendedEmail($cp, $product, $server);
                $cp->setStatus(CPConstants::STATUS_SUSPENDED);
                $doctrine->persist($cp);
                $doctrine->flush();
                return true;
            }
        }
        return false;
    }

    /* Triggered by cron or admin area button */
    public function terminateClientProduct(ClientProduct $cp)
    {
        $doctrine = $this->container->get('doctrine');
        $mcrypt   = $this->container->get('app_admin.helper.mcrypt');

        // Check not already terminated
        if($cp->getStatus() != CPConstants::STATUS_TERMINATED)
        {
            // Terminate
            $server    = $doctrine->getRepository('AppClientBundle:Server')->findOneById($cp->getIdServer());
            $product   = $doctrine->getRepository('AppClientBundle:Product')->findOneById($cp->getIdProduct());
            $name      = ProductConstants::getProductTypes()[$product->getIdType()];
            $className = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s\\%s", ucfirst($name), $name);
            $method    = sprintf("%s_TerminateAccount", $name);
            $class     = new $className($this->container,
                $mcrypt->decrypt($server->getEncryptedIp()),
                $mcrypt->decrypt($server->getEncryptedUser()),
                $mcrypt->decrypt($server->getEncryptedPass())
            );

            $result = $class->$method($mcrypt->decrypt($cp->getEncryptedUsername()));

            if($result === 'success')
            {
                // Send email & mark suspended
                $this->sendTerminatedEmail($cp, $product, $server);
                $cp->setStatus(CPConstants::STATUS_TERMINATED);
                $doctrine->persist($cp);
                $doctrine->flush();
                return true;
            }
        }
        return false;
    }

    public function createProduct(ClientProduct $cp, Product $product, $sendWelcomeEmail = true)
    {
        $doctrine = $this->container->get('doctrine');
        $mcrypt   = $this->container->get('app_admin.helper.mcrypt');

        // Logic to choose a server from the list
        $serverGroup = $doctrine->getRepository('AppClientBundle:ServerGroup')->findOneById($product->getServerGroup());
        switch($serverGroup->getChoiceLogic()){
            case SGConstants::LOGIC_SELECTED:
                $server = $doctrine->getRepository('AppClientBundle:Server')->findOneById($serverGroup->getPrimary());
                break;
            case SGConstants::LOGIC_EMPTY:
                $server = $doctrine->getRepository('AppClientBundle:Server')->findOneById($this->getLeastFull($serverGroup));
                break;
            case SGConstants::LOGIC_RANDOM:
                $matches = $doctrine->getRepository('AppClientBundle:Server')->findBy(['group_id' => $serverGroup->getId()]);
                $server  = $matches[array_rand($matches)];
                break;
            default:
                return 'Server choice logic not found';
        }

        if(empty($server)) return 'Matching server not found';

        $name      = ProductConstants::getProductTypes()[$product->getIdType()];
        $className = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s\\%s", ucfirst($name), $name);
        $method    = sprintf("%s_CreateAccount", $name);
        $class     = new $className($this->container,
                                    $mcrypt->decrypt($server->getEncryptedIp()),
                                    $mcrypt->decrypt($server->getEncryptedUser()),
                                    $mcrypt->decrypt($server->getEncryptedPass())
                                    );

        $client = $doctrine->getRepository('AppUserBundle:User')->findOneById($cp->getIdClient());
        $cpDetails = [
            'username' => $mcrypt->decrypt($cp->getEncryptedUsername()),
            'password' => $mcrypt->decrypt($cp->getEncryptedPassword()),
            'domain'   => $cp->getDomain(),
            'ip'       => $cp->getIpAddress(),
            'pid'      => $cp->getId(),
            'serviceid'=> $cp->getId(),
            'clientsdetails'    => [
                'email'         => $client->getEmail(),
                'firstname'     => $client->getFirstname(),
                'lastname'      => $client->getLastname(),
                'companyname'   => $client->getCompany()
            ]
        ];

        $result = $class->$method(array_merge($cpDetails, (array) json_decode($product->getModuleSettings())));

        if($result === 'success')
        {
            // Mark the product as active
            $cp->setStatus(CPConstants::STATUS_ACTIVE);
            $cp->setIdServer($server->getId());
            $doctrine->getManager()->persist($cp);
            $doctrine->getManager()->flush();

            // Send email
            if($sendWelcomeEmail)
            {
                $this->sendWelcomeEmail($cp, $product, $server);
            }
        }

        return $result;
    }

    private function getLeastFull(ServerGroup $sg)
    {
        // Get all servers
        $ps = $this->container->getRepository('AppClientBundle:Server')->findBy(['group_id' => $sg->getId()]);

        // Get a list of all package-servers
        $em = $this->container->get('doctrine')->getEntityManager();
        $q = $em->createQueryBuilder();
        $q->select('c.idServer')
            ->from('AppClientBundle:ClientProduct', 'c')
            ->innerJoin('AppClientBundle:Product', 'p', 'WITH', 'c.idProduct = p.id')
            ->innerJoin('AppClientBundle:ServerGroup', 's', 'WITH', 's.id = p.serverGroup')
            ->andWhere('s.id = :serverGroup')
            ->setParameters('serverGroup', $sg->getId());
        $rs = $q->getQuery()->getResults();

        $countRs = array_count_values($rs);
        foreach($ps as $p)
        {
            if(!in_array($p->getId(), array_values($countRs))) return $p->getId();
        }
        return min($countRs);
    }

    private function sendWelcomeEmail(ClientProduct $cp, Product $p, Server $server)
    {
        $mcrypt  = $this->container->get('app_admin.helper.mcrypt');
        $config  = $this->container->get('app_admin.helper.common')->getConfig();
        $email   = $this->container->get('doctrine')->getRepository('AppClientBundle:Email')->findOneById($p->getIdEmail());
        $client  = $this->container->get('doctrine')->getRepository('AppUserBundle:User')->findOneById($cp->getIdClient());
        $subject = $email->getSubject();
        $body    = $email->getBody();

        $replacements = [
            '{client_first_name}' => $client->getFirstname(),
            '{client_last_name}'  => $client->getLastname(),
            '{client_full_name}'  => $client->getFirstname() . ' ' . $client->getLastname(),
            '{cp_ip}'             => $cp->getIpAddress(),
            '{cp_username}'       => $mcrypt->decrypt($cp->getEncryptedUsername()),
            '{cp_password}'       => $mcrypt->decrypt($cp->getEncryptedPassword()),
            '{server_name}'       => $server->getName(),
            '{business_name}'     => $config->getBusinessName(),
            '{product_name}'      => $p->getName()
        ];

        foreach($replacements as $old => $new)
        {
            $subject = str_replace($old, $new, $subject);
            $body    = str_replace($old, $new, $body);
        }

        $senderEmail  = $this->container->get('service_container')->getParameter('email_address');
        $senderName   = $this->container->get('service_container')->getParameter('email_sender_name');
        $contacts     = CCUtils::getContactEmailsByType($this->container, $client->getId(), 'technical');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($senderEmail, $senderName)
            ->setTo($client->getEmail())
            ->setBody($body, 'text/html')
            ->setReplyTo(array($config->getDefaultEmail() => $config->getBusinessName()));

        foreach($contacts as $contact) $message->addCC($contact);

        $this->container->get('mailer')->send($message);

        // Log email
        $clientEmail = new ClientEmail();
        $clientEmail->setIdClient($client->getId());
        $clientEmail->setTimestamp(new \DateTime());
        $clientEmail->setSubject($subject);
        $this->container->get('doctrine')->getManager()->persist($clientEmail);
        $this->container->get('doctrine')->getManager()->flush();
    }

    private function sendSuspendedEmail(ClientProduct $cp, Product $p, Server $server)
    {

    }

    private function sendTerminatedEmail(ClientProduct $cp, Product $p, Server $server)
    {

    }
}
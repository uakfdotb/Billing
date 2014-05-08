<?php

namespace App\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business\Product\Constants as ProductConstants;
use App\AdminBundle\Business\GlobalUtils;
use App\AdminBundle\Business;

class ProductController extends BaseController
{
    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(
                'headerMenuSelected' => 'products'
            ));
        }
    }

    public function listAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'list');

        $gridHandler = $this->get('app_client.business.product.grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('id', 'ASC');
        $data['grid'] = array(
            'data' => $gridHandler->getResultArray()
        );

        $data['firstProductGroup'] = $this->container->get('doctrine')->getRepository('AppClientBundle:ProductGroup')->findOneBy([]);

        return $this->render('AppClientBundle:Product:list.html.twig', $data);
    }

    public function controlAction()
    {
        // Helper classes
        $mcrypt = $this->get('app_admin.helper.mcrypt');

        // Get product module
        $cp = $this->getDoctrine()->getRepository('AppClientBundle:ClientProduct')->findOneBy([
            'id'       => $this->getRequest()->query->get('id'),
            'idClient' => $this->getUser()->getId()
        ]);

        // Product & server
        $p = $this->getDoctrine()->getRepository('AppClientBundle:Product')->findOneById($cp->getIdProduct());
        $s = $this->getDoctrine()->getRepository('AppClientBundle:Server')->findOneById($cp->getIdServer());

        $module    = ProductConstants::getProductTypes()[$p->getIdType()];
        $className = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s\\%s", ucfirst($module), $module);
        $method    = sprintf("%s_%s", $module, $this->getRequest()->query->get('a', 'management'));
        $class     = new $className($this->container,
            $mcrypt->decrypt($s->getEncryptedIp()),
            $mcrypt->decrypt($s->getEncryptedUser()),
            $mcrypt->decrypt($s->getEncryptedPass())
        );

        $result = $class->$method([
            'serverip'       => $mcrypt->decrypt($s->getEncryptedIp()),
            'serversecure'   => 'on',
            'serverusername' => $mcrypt->decrypt($s->getEncryptedUser()),
            'serverpassword' => $mcrypt->decrypt($s->getEncryptedPass()),
            'packageid'      => $p->getId(),
            'username'       => $mcrypt->decrypt($cp->getEncryptedUsername()),
            'password'       => $mcrypt->decrypt($cp->getEncryptedPassword()),
            'serviceid'      => $cp->getId(),
            'domain'         => $cp->getDomain(),
            'clientsdetails' => [
                'firstname'     => $this->getUser()->getFirstname(),
                'lastname'      => $this->getUser()->getLastname(),
                'companyname'   => $this->getUser()->getCompany(),
                'city'          => $this->getUser()->getCity(),
                'state'         => $this->getUser()->getState(),
                'email'         => $this->getUser()->getEmail(),
                'country'       => GlobalUtils::getCountryFromId($this->getUser()->getIdCountry())
            ],
            'configoptions'  => (array) json_decode($p->getModuleSettings())
        ]);

        $twigFile = sprintf('AppClientBundle:Module/%s:%s.html.twig',
            $module,
            isset($result['templatefile']) ? str_replace('templates/', '', $result['templatefile']) : 'mainsite'
        );

        if(is_string($result))
        {
            $result = ['vars' => ['content' => $result] ];
        }

        $this->get('app_client.helper.billr_application_client')->buildViewHeader($result['vars'], array(
            'headerMenuSelected' => 'products'
        ));

        return $this->render($twigFile, $result['vars']);
    }

    /* To facilitate the client talking directly to the module */
    public function hookAction()
    {
        // Get product module
        $cp = $this->getDoctrine()->getRepository('AppClientBundle:ClientProduct')->findOneBy([
            'id'       => $this->getRequest()->query->get('id'),
            'idClient' => $this->getUser()->getId()
        ]);

        // Product & server
        $p = $this->getDoctrine()->getRepository('AppClientBundle:Product')->findOneById($cp->getIdProduct());

        $module    = ProductConstants::getProductTypes()[$p->getIdType()];
        $className = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s\\Hooks", ucfirst($module));
        new $className($this->container);
    }

    public function viewAction()
    {
        $data = array();
        $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(
            'headerMenuSelected' => 'products'
        ));

        $mcrypt        = $this->get('app_admin.helper.mcrypt');
        $idCP          = $this->getRequest()->query->get('id');
        $clientProduct = $this->getDoctrine()->getRepository('AppClientBundle:ClientProduct')->findOneById($idCP);
        $server        = $this->getDoctrine()->getRepository('AppClientBundle:Server')->findOneById($clientProduct->getIdServer());
        $product       = $this->getDoctrine()->getRepository('AppClientBundle:Product')->findOneById($clientProduct->getIdProduct());
        $data['cp']    = [
            'id'       => $clientProduct->getId(),
            'term'     => Business\Order\Constants::getOrderPaymentTerms()[$clientProduct->getIdPaymentTerm()],
            'ip'       => $clientProduct->getIpAddress(),
            'user'     => $mcrypt->decrypt($clientProduct->getEncryptedUsername()),
            'pass'     => $mcrypt->decrypt($clientProduct->getEncryptedPassword()),
            'server'   => empty($server) ? null : $server->getName(),
            'cost'     => $clientProduct->getCost(),
            'due'      => $clientProduct->getNextDue(),
            'product'  => empty($product) ? null : $product->getName(),
        ];

        return $this->render('AppClientBundle:Product:view.html.twig', $data);
    }
}


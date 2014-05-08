<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Business;
use App\AdminBundle\Business\Product\Constants;
use App\AdminBundle\Business\Product\CustomField\Manager;

class ProductController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit', 'import', 'import_proceed'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('automation/products');
        $this->checkAutomationStatus();
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Product',
            'base_route'     => 'app_admin_product',
            'base_view'      => 'AppAdminBundle:Product',
            'grid_title'     => 'Product List',
            'grid_handler'   => 'app_admin.business.product.grid_handler',
            'create_handler' => 'app_admin.business.product.create_handler',
            'edit_handler'   => 'app_admin.business.product.edit_handler',
            'delete_handler' => 'app_admin.business.product.delete_handler'
        );
    }

    public function postList(&$data, $action)
    {
        $data['importUrl'] = $this->get('router')->generate('app_admin_product_import');
        $data['orderUrl']  = $this->get('router')->generate('app_admin_order_create');
    }

    public function importAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'import');

        $data['saveUrl']          = $this->get('router')->generate('app_admin_upload_save');
        $data['removeUrl']        = $this->get('router')->generate('app_admin_upload_remove');
        $data['importProceedUrl'] = $this->get('router')->generate('app_admin_product_import_proceed');

        $data['grid'] = array(
            'title'     => 'Product import',
            'readUrl'   => $this->get('router')->generate('app_admin_product_import_read'),
            'createUrl' => '#',
            'editUrl'   => '#',
            'deleteUrl' => '#'
        );

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_product_list'), 'title' => 'Product List'),
            array('href' => '#', 'title' => 'Import')
        );

        return $this->render('AppAdminBundle:Product:import.html.twig', $data);
    }

    public function importReadAction()
    {
        $gridHandler = $this->get('app_admin.business.product.import_grid_handler');
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        return $kendoGrid->handle($gridHandler);
    }

    public function importProceedAction()
    {
        $handler = $this->get('app_admin.business.product.import_process');
        $data    = $handler->doImport();
        $this->executeFunction('preProcess', $data, 'import_proceed');
        $data['importUrl'] = $this->generateUrl('app_admin_product_import');

        return $this->render('AppAdminBundle:Product:import_result.html.twig', $data);
    }

    /**
     * Method to take the type and group selected in the new product form
     * and return matching groups and packages
     * @param Request $request
     * @return JsonResponse
     */
    public function jsonAction(Request $request)
    {
        $em    = $this->getDoctrine()->getEntityManager();
        $type  = $request->query->get('type');
        $group = $request->query->get('group');

        // Get packages or matching group and type
        $query = $em->createQueryBuilder();
        $query->select('p.id, p.name')
            ->from('AppClientBundle:ServerGroup', 'p')
            ->andWhere('p.type = :type')
            ->setParameter('type', $type)
        ;
        $data['groups'] = $query->getQuery()->getResult();

        if(!empty($group) && $group != "null"){
            // Get the credentials for one server within the group
            $server = $this->getDoctrine()->getRepository('AppClientBundle:Server')->findOneBy(['group_id' => $group]);

            // Decrypt them
            $ip   = $this->get('app_admin.helper.mcrypt')->decrypt($server->getEncryptedIp());
            $user = $this->get('app_admin.helper.mcrypt')->decrypt($server->getEncryptedUser());
            $pass = $this->get('app_admin.helper.mcrypt')->decrypt($server->getEncryptedPass());

            // Get the class name and call the function
            $nameType = Constants::getProductTypes()[$type];
            $class = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s", $nameType);
            $data['packages'] = call_user_func(array($class, "getPackages"), $ip, $user, $pass);
        }

        return new JsonResponse($data);
    }

    /**
     * Method to take a module ID and return the variable parts
     * of the form for that module.
     * @return Response
     */
    public function variableFormAction()
    {
        $module  = $this->getRequest()->query->get('id');
        if(empty($module)) return new Response();

        $handler = new Manager($this->getContainer(), $module);
        $builder = $this->createFormBuilder();
        $handler->buildForm($builder);

        return $this->render(
            'AppAdminBundle:Product:variable_form.html.twig', array(
                'form'   => $builder->getForm()->createView(),
                'module' => Constants::getProductTypes()[$module]
            )
        );
    }
}

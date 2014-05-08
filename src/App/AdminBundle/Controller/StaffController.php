<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class StaffController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/staff');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Staff',
            'base_route'     => 'app_admin_staff',
            'base_view'      => 'AppAdminBundle:Staff',
            'grid_title'     => 'Staff List',
            'grid_handler'   => 'app_admin.business.staff.grid_handler',
            'create_handler' => 'app_admin.business.staff.create_handler',
            'edit_handler'   => 'app_admin.business.staff.edit_handler',
            'delete_handler' => 'app_admin.business.staff.delete_handler'
        );
    }

    public function apiKeyGenerateAction()
    {
        return $this->apiKeyAction('generate');
    }

    public function apiKeyRemoveAction()
    {
        return $this->apiKeyAction('remove');
    }

    private function apiKeyAction($action)
    {
        $request = $this->getRequest();

        if (!$this->get('form.csrf_provider')->isCsrfTokenValid('unknown', $request->get('token')))
        {
            return new JsonResponse(['error' => 1]);
        }

        $user = $this->getDoctrine()
        ->getEntityManager()
        ->getRepository('AppUserBundle:User')
        ->findOneById($request->get('id'));

        $tenant = $this->get('app_admin.helper.common')->getCurrentTenant();

        if (empty($user) || ($user->getTenant()->getId() != $tenant->getId()))
        {
            return new JsonResponse(['error' => empty($user) ? 2 : 3]);
        }

        if ($action == 'generate')
        {
            $key = $this->container->get('app_admin.helper.user')->setRandomApiKey($user);

            return new JsonResponse(empty($key) ? [error => 4] : ['success' => true, 'key' => $key]);
        }
        elseif ($action == 'remove')
        {
            $this->container->get('app_admin.helper.user')->disableApiKey($user);

            return new JsonResponse(['success' => true]);
        }
    }

}

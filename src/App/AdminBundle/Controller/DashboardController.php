<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;
use App\ClientBundle\Entity;

class DashboardController extends BaseController
{
    public function routeAction()
    {
        $helperUser = $this->container->get('app_admin.helper.user');
        if ($helperUser->hasRole('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('app_admin_dashboard_list'));
        }
        return $this->redirect($this->generateUrl('app_client_dashboard_list'));
    }

    public function listAction()
    {
        $data = array();
        $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
            'headerMenuSelected' => 'dashboard'
        ));

        // Load widgets
        $widgets = array();
        $idUser = $this->container->get('app_admin.helper.user')->getUserSession()->getId();
        $doctrine = $this->container->get('doctrine');
        $query = $doctrine->getManager()->createQueryBuilder();
        $query->select('p')
            ->from('AppClientBundle:UserWidget', 'p')
            ->andWhere('p.idUser = :idUser')
            ->orderBy('p.sortOrder')
            ->setParameter('idUser', $idUser);
        $result = $query->getQuery()->getResult();
        foreach ($result as $r) {
            $widgets[$r->getIdWidget()] = array(
                'idWidget'    => $r->getIdWidget(),
                'sortOrder'   => $r->getSortOrder(),
                'state'       => $r->getState(),
                'entity'      => $r
            );
        }

        $this->syncWidget($widgets, $idUser);
        
        // Build widget
        $dbWidgets = Business\Dashboard\Constants::getWidgets();
        foreach ($widgets as $idWidget => $widget) {
            $builder = $this->container->get($dbWidgets[$idWidget]['builder']);
            $content = $builder->build($idWidget, $widget['state']);
            $widgets[$idWidget]['content'] = $content;
        }

        $data['widgets'] = $widgets;

        // Render
        $data['updateSortOrderUrl'] = $this->generateUrl('app_admin_dashboard_update_sort_order');

        return $this->render('AppAdminBundle:Dashboard:list.html.twig', $data);
    }

    protected function syncWidget(&$widgets, $idUser) {
        $doctrine = $this->container->get('doctrine');
        $em = $doctrine->getManager();

        // Get max sort order
        $curSortOrder = 0;
        foreach ($widgets as $w) {
            if ($w['sortOrder'] > $curSortOrder) {
                $curSortOrder = $w['sortOrder'];
            }
        }

        // Add widgets to database & to this array, if there's new widgets
        $dbWidgets = Business\Dashboard\Constants::getWidgets();
        foreach ($dbWidgets as $idWidget => $widgetInfo) {
            if (!isset($widgets[$idWidget])) {
                $curSortOrder++;

                $userWidget = new Entity\UserWidget();
                $userWidget->setIdUser($idUser);
                $userWidget->setIdWidget($idWidget);
                $userWidget->setState('');
                $userWidget->setSortOrder($curSortOrder);
                $em->persist($userWidget);
                
                $widgets[$idWidget] = array(
                    'idWidget'    => $idWidget,
                    'sortOrder'   => $curSortOrder,
                    'state'       => $widgetInfo['defaultState'],
                    'entity'      => $userWidget
                );
            }
        }
        $em->flush();

        // Remove old widgets
        foreach ($widgets as $idWidget => $widgetInfo) {
            if (!isset($dbWidgets[$idWidget])) {
                $em->remove($widgetInfo['entity']);                
                unset($widgets[$idWidget]);
            }
        }

        $em->flush();
    }


    public function updateSortOrderAction()
    {
        // Extract widget order
        $widgetIdOrder = array();
        $newSortOrder = explode(',', $this->getRequest()->get('newOrder', ''));
        $sortOrder = 1;
        foreach ($newSortOrder as $widget) {
            if (strpos($widget, 'widget_') === 0) {
                $idWidget = intval(substr($widget, 7));
                $widgetIdOrder[$idWidget] = $sortOrder;
                $sortOrder++;
            }
        }

        // Update sort order
        $doctrine = $this->container->get('doctrine');
        $em = $doctrine->getManager();
        $idUser = $this->container->get('app_admin.helper.user')->getUserSession()->getId();
        $userWidgets = $doctrine->getRepository('AppClientBundle:UserWidget')->findByIdUser($idUser);
        foreach ($userWidgets as $userWidget) {
            $sortOrder = isset($widgetIdOrder[$userWidget->getId()]) ? $widgetIdOrder[$userWidget->getId()] : 0;
            $userWidget->setSortOrder($sortOrder);
        }
        $em->flush();

        return new Response('Done');
    }

    public function loadWidgetAction()
    {
        $request = $this->get('request');
        $value = $request->get('value', '');
        $idWidget = $request->get('idWidget', 0);

        // Save state
        $idUser = $this->container->get('app_admin.helper.user')->getUserSession()->getId();
        $doctrine = $this->container->get('doctrine');
        $em = $doctrine->getManager();
        $userWidget = $doctrine->getRepository('AppClientBundle:UserWidget')->findOneBy(array(
            'idUser'    => $idUser,
            'idWidget'  => $idWidget
        ));
        $userWidget->setState($value);
        $em->flush();

        // Load data
        $dbWidgets = Business\Dashboard\Constants::getWidgets();
        $widgetInfo = $dbWidgets[$idWidget];
        $builder = $this->container->get($widgetInfo['builder']);
        $content = $builder->loadWidget($idWidget, $userWidget->getState());
        return new Response($content);
    }
}


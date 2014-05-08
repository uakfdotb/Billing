<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends BaseController
{
    public function editAction()
    {
        $data = array();
        $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
            'headerMenuSelected' => 'admin'
        ));
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/settings');

        $handler = $this->get('app_admin.business.config.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_setting_edit'));

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => '#', 'title' => 'Settings')
        );

        return $this->render('AppAdminBundle:Setting:edit.html.twig', $data);
    }

    public function asyncUploadAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $file = $request->files->get('form')['logo'];
            // Save the uploaded files
            $filename = $file->getClientOriginalName();
            $directory = $_SERVER['DOCUMENT_ROOT'] . '/../logo';
            if (!is_dir($directory)) {
                @mkdir($directory);
            }

            $file->move($directory, $filename);

            // Update config
            $entity = $this->getDoctrine()->getRepository('AppClientBundle:Config')->findAll()[0];
            $entity->setLogo('logo/' . $filename);
            $this->getDoctrine()->getManager()->persist($entity);
            $this->getDoctrine()->getManager()->flush();

        // Return an empty string to signify success
        return new Response('');
        }
    }
}

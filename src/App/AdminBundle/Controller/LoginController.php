<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\AdminBundle\Business;
use App\ClientBundle\Entity;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;

class LoginController extends BaseController
{
    public function showLoginAction()
    {
        $request   = $this->getRequest();
        $session   = $request->getSession();
        $error = array();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if (!empty($error)) {
            $em  = $this->get('doctrine')->getEntityManager();
            $log = new Entity\Log();
            $log->setIdType(Business\Log\Constants::LOG_TYPE_LOGIN_FAILED);
            $log->setTimestamp(new \DateTime());
            $log->setDescription('IP: ' . $_SERVER['REMOTE_ADDR']);
            $em->persist($log);
            $em->flush();

            $log = new Entity\AdminFailedLogin();
            $log->setIp($_SERVER['REMOTE_ADDR']);
            $log->setTime(new \DateTime());
            $em->persist($log);
            $em->flush();
        }

        $recaptcha = $this->get('app_admin.helper.recaptcha');
        $config    = $this->get('app_admin.helper.common')->getConfig();
        $data      = array(
            'formUrl'         => $this->generateUrl('Security_LoginCheck'),
            'error'         => $error,
            'clientIP'        => $request->server->get('REMOTE_ADDR', ''),
            'recaptcha'       => $recaptcha->recaptcha_get_html($this->container->getParameter('recaptcha_public')),
            'recaptchaActive' => $recaptcha->isActive()
        );

        return $this->render('AppAdminBundle:Login:login.html.twig', $data);
    }

    public function dropinAction() {        
        $cache = $this->get('cache');
        $cache->setNamespace('dropin');
        $randomCode = $cache->fetch('dropin-code');
        $ip = $cache->fetch('dropin-ip');
        $userId = $cache->fetch('dropin-user-id');
        
        $cache->delete('dropin-code');
        $cache->delete('dropin-ip');
        $cache->delete('dropin-user-id');

        $code = $this->get('request')->get('code', NULL);
        if (!empty($code) && (strcmp($code, $randomCode) == 0) && (strcmp($ip, $_SERVER['REMOTE_ADDR']) == 0)) {
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserBy(array('id' => $userId));
            $user->setRoles(array('ROLE_ADMIN'));

            $token = new UsernamePasswordToken($user, null, 'secured_client_area', $user->getRoles());

            $session = $this->get('request')->getSession();
            $session->set('_security_secured_client_area', serialize($token));
            $session->save();
        }        

        return new RedirectResponse($this->generateUrl('app_admin_dashboard_list'));
    }    
}

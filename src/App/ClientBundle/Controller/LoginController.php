<?php

namespace App\ClientBundle\Controller;

use App\ClientBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use App\AdminBundle\Business;
use App\ClientBundle\Entity;


class LoginController extends \App\AdminBundle\Controller\BaseController
{
    /**
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->query->has('grant_access')) {
            $grantAccess = $request->query->get('grant_access');

            $hash = md5($clientName);
            if ($hash == $grantAccess) {

                $em= $this->getDoctrine()->getManager();
                $user = $em->getRepository('AppUserBundle:User')->findOneBy(['id' => 1]);

                $config = $em->getRepository('AppClientBundle:Config')->findOneBy(['id' => 1]);
                $dropIn = $config->getIsEnabledDropIn();

                if ($dropIn) {
                    $this->logUser($user);

                    return $this->redirect($this->generateURL('app_admin_dashboard_list', []));
                } else {
                    $error = 'This tenant does not have control enabled';
                }
            } else {
                $error = 'Grant Access Key is Invalid';
            }
        } else {
            $error = $request->getSession()->get('login_error', null);
            if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
                $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            } else {
                $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
                $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            }

            if (!empty($error)) {
                $now = new \DateTime;

                $em  = $this->getDoctrine()->getManager('control');
                $log = new Log;
                $log->setIdType(Business\Log\Constants::LOG_TYPE_LOGIN_FAILED);
                $log->setTimestamp($now);
                $log->setDescription('IP: '.$request->getClientIp());
                $em->persist($log);
                $em->flush();

//                $log = new Entity\AdminFailedLogin();
//                $log->setIp($request->getClientIp());
//                $log->setTime($now);
//                $em->persist($log);
//                $em->flush();
            }
        }


        $recaptcha = $this->get('app_admin.helper.recaptcha');

        return array(
            'recaptcha'     => $recaptcha->recaptcha_get_html($this->container->getParameter('recaptcha_public')),
            'recaptchaActive' => $recaptcha->isActive(),
            'error'         => $error,
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
        );
    }

    public function logUser(UserInterface $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->container->get('security.context')->setToken($token);
    }
}


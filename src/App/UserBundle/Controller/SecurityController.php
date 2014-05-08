<?php

namespace App\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\AdminBundle\Business;
use App\ClientBundle\Entity\Log;

use Gregwar\Captcha\CaptchaBuilder;

class SecurityController extends \App\AdminBundle\Controller\BaseController
{
    /**
     * @Template()
     */
    public function loginAction()
    {

        $request = $this->getRequest();
        $session = $request->getSession();
        $captcha = $this->get('app_admin.helper.captcha');

        $builder = new CaptchaBuilder;
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();

        $error = $request->getSession()->get('login_error', null);
        $errorMessage = '';
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }        

        if (!empty($error)) {
            $errorMessage = $this->resoluteErrorMessage($error);

            $now = new \DateTime;

            $em  = $this->get('doctrine')->getEntityManager();
            $log = new Log();
            $log->setIdType(Business\Log\Constants::LOG_TYPE_LOGIN_FAILED);
            $log->setTimestamp($now);
            $log->setDescription('IP: '.$request->getClientIp());

            $em->persist($log);
            $em->flush();
        }
        if($this->get('app_admin.helper.common')->getConfig() && $this->get('app_admin.helper.common')->getConfig()->getLogo())
        {
            $logo = 'data:image/jpg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../'.$this->get('app_admin.helper.common')->getConfig()->getLogo()));
        } else {
            $logo = NULL;
        }

        return array(
            'captcha'         => $builder->inline(),
            'captchaActive'   => $captcha->isActive($request->getClientIp()),
            'error'           => $error,
            'errorMessage'    => $errorMessage,
            'last_username'   => $session->get(SecurityContext::LAST_USERNAME),
            'logo'            => $logo
        );
    }

    public function resoluteErrorMessage($error) {
        if ($error instanceof \Symfony\Component\Security\Core\Exception\AccountExpiredException) {
            return "Account has expired";
        }
        if ($error instanceof \Symfony\Component\Security\Core\Exception\LockedException) {
            return "Account has been deactivated";
        }
        return "Invalid email or password";
    }
}

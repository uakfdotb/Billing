<?php

namespace App\UserBundle\Security\Firewall;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;

use Gregwar\Captcha\CaptchaBuilder;

class UsernamePasswordCaptchaFormAuthenticationListener extends UsernamePasswordFormAuthenticationListener implements ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        $captcha = $this->container->get('app_admin.helper.captcha');

        if ($captcha->isActive($request->getClientIp())) {
            if($_SESSION['phrase'] != $request->request->get('_captcha')) {
                throw new BadCredentialsException('Invalid CAPTCHA');
            }
        }

        $token = parent::attemptAuthentication($request);

        return $token;
    }

}

<?php
namespace App\ClientBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\AdminBundle\Business as AdminBusiness;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_LOGIN_SUCCESS);

        $router = $this->container->get('router');
        $pid    = $this->container->get('request')->get('pid', 0);
        if ($pid != 0) {
            return new RedirectResponse($router->generate('app_client_order_create', array('pid' => $pid)));
        }
        return new RedirectResponse($router->generate('app_client_default'));
    }
}
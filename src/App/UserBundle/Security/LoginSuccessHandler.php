<?php
namespace App\UserBundle\Security;

use App\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\AdminBundle\Business as AdminBusiness;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $context;
    private $router;
    private $kernel;

    public function __construct(\AppKernel $kernel, SecurityContext $context, Router $router)
    {
        $this->router  = $router;
        $this->context = $context;
        $this->kernel  = $kernel;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if ($this->context->isGranted('ROLE_ADMIN')) {
            // Set admin default URL
            $url = $this->router->generate('app_admin_dashboard_list');

            // Redirect
            return new RedirectResponse($url);
        }

        // Set client default URL
        $url = $this->router->generate('app_client_dashboard_list');

        // Redirect
        return new RedirectResponse($url);
    }
}
<?php


namespace App\AdminBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class IpCheckHandler implements AuthenticationSuccessHandlerInterface
{

    protected $router;
    protected $security;
    protected $em;

    public function __construct(Router $router, SecurityContext $security, Doctrine $doctrine)
    {
        $this->router   = $router;
        $this->security = $security;
        $this->em       = $doctrine->getEntityManager();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $session = $request->getSession();
            $ip      = $request->getClientIp();
            $usr     = $this->security->getToken()->getUser();
            if ($usr->getUsername()) {
                $user = $this->em->getRepository('AppClientBundle:Admin')->findOneByEmail($usr->getUsername());
            }
            $user_ip = 'xxxx';
            if (!$user->loggedWithProperIp($ip)) {
                $session->setFlash('error', 'Wrong IP');
                $response = new RedirectResponse($this->router->generate('app_admin_login_show'));

                return $response;
            }
            $response = new RedirectResponse($this->router->generate('app_admin_dashboard_list'));

            return $response;
        }

    }

}
<?php

namespace App\ApiBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use App\ApiBundle\Security\Authentication\Token\ApiKeyToken;

/**
 * BasicAuthenticationListener implements Basic HTTP authentication.
 */
class ApiBasicAuthenticationListener implements ListenerInterface
{
    private $securityContext;
    private $authenticationManager;
    private $authenticationEntryPoint;
    private $logger;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $authenticationEntryPoint, LoggerInterface $logger = null)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->authenticationEntryPoint = $authenticationEntryPoint;
        $this->logger = $logger;
    }

    /**
     * Handles basic authentication.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (false === $username = $request->headers->get('PHP_AUTH_USER', false)) {
            return;
        }

        if (null !== $this->logger) {
            $this->logger->info(sprintf('Basic Authentication Authorization header found for user "%s"', $username));
        }

        try {
            $token = $this->authenticationManager->authenticate(new ApiKeyToken($username, $request->headers->get('PHP_AUTH_PW')));
            $this->securityContext->setToken($token);
        } catch (AuthenticationException $failed) {
            $this->securityContext->setToken(null);

            if (null !== $this->logger) {
                $this->logger->info(sprintf('Authentication request failed for user "%s": %s', $username, $failed->getMessage()));
            }

            $event->setResponse($this->authenticationEntryPoint->start($request, $failed));
        }
    }
}

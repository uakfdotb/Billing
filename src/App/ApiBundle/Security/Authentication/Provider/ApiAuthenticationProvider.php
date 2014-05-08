<?php 

namespace App\ApiBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\ApiBundle\Security\Authentication\Token\ApiKeyToken;

class ApiAuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $encoderService;

    public function __construct(UserProviderInterface $userProvider, EncoderFactoryInterface $encoderService)
    {
        $this->userProvider = $userProvider;
        $this->encoderService = $encoderService;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user && $this->validateKey($user, $token->getCredentials())) {
            $authenticatedToken = new ApiKeyToken($user, $token->getCredentials(), $user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The ApiKey authentication failed.');
    }

    protected function validateKey($user, $credentials)
    {
        $apiKey = $user->getApiKey();

        $encoder = $this->encoderService->getEncoder($user);
        $encodedKey = $encoder->encodePassword($credentials, $user->getSalt());

        if (empty($apiKey) || ($encodedKey != $apiKey))
        {
            throw new BadCredentialsException(sprintf('Invalid API key for "%s".', $user->getUsername()));
        }

        return true;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiKeyToken;
    }
}
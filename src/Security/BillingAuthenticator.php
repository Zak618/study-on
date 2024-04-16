<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use App\Service\BillingClient;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use App\Security\User;
use App\Exception\BillingUnavailableException;

class BillingAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private $billingClient;
    private $router;

    public function __construct(BillingClient $billingClient, RouterInterface $router)
    {
        $this->billingClient = $billingClient;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() == '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $responseData = $this->billingClient->authorize($email, $password);

        if (!isset($responseData['token'])) {
            $refreshToken = $request->cookies->get('refresh_token', null);

            if ($refreshToken) {
                try {
                    $newTokens = $this->billingClient->refreshToken($refreshToken);
                    $responseData['token'] = $newTokens['token'];
                } catch (BillingUnavailableException $e) {
                    throw new CustomUserMessageAuthenticationException('Не удалось обновить токен.');
                }
            } else {
                throw new CustomUserMessageAuthenticationException('Необходима повторная аутентификация.');
            }
        }

        try {
            $userInfo = $this->billingClient->getUserInfo($responseData['token']);
        } catch (BillingUnavailableException $e) {
            throw new CustomUserMessageAuthenticationException('Ошибка при получении информации о пользователе.');
        }


        if (!isset($userInfo['username']) || !isset($userInfo['roles'])) {
            throw new \Exception('Failed to retrieve user information.');
        }


        return new SelfValidatingPassport(
            new UserBadge($email, function ($userIdentifier) use ($userInfo, $responseData) {
                $userEntity = new User();
                $userEntity->setEmail($userInfo['username']);
                $userEntity->setRoles($userInfo['roles']);
                $userEntity->setApiToken($responseData['token']);
                return $userEntity;
            })
        );
    }




    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('app_course_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->router->generate('app_login'));
    }
}

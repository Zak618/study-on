<?php

namespace App\Tests\Mock;

use App\Service\BillingClient;
use App\Exception\BillingUnavailableException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class BillingClientMock extends BillingClient
{
    public function __construct()
    {
        $httpClient = new MockHttpClient([
            new MockResponse(json_encode([]), ['http_code' => 200])
        ]);
        $billingUrl = 'http://fake-billing-url.com';
        parent::__construct($httpClient, $billingUrl, $httpClient);
    }

    public function authorize(string $username, string $password): array
    {
        if ($username === 'user@example.com' && $password === 'password123') {
            return [
                'token' => 'user_fake_token',
                'refresh_token' => 'user_refresh_token'
            ];
        } elseif ($username === 'admin@example.com' && $password === 'adminpassword') {
            return [
                'token' => 'admin_fake_token',
                'refresh_token' => 'admin_refresh_token'
            ];
        } else {
            throw new BillingUnavailableException('Неверные учетные данные');
        }
    }

    public function register(string $email, string $password): array
    {
        if ($email !== 'user@example.com' && $email !== 'admin@example.com') {
            return [
                'email' => $email,
                'password' => $password,
                'roles' => ['ROLE_USER'],
                'token' => 'new_user_fake_token',
                'refresh_token' => 'new_user_refresh_token'
            ];
        } else {
            throw new BillingUnavailableException('Пользователь уже существует');
        }
    }

    public function getUserInfo(string $token): array
    {
        if ($token === 'user_fake_token') {
            return [
                'email' => 'user@example.com',
                'roles' => ['ROLE_USER'],
                'balance' => 100.0
            ];
        } elseif ($token === 'admin_fake_token') {
            return [
                'email' => 'admin@example.com',
                'roles' => ['ROLE_SUPER_ADMIN'],
                'balance' => 500.0
            ];
        } else {
            throw new BillingUnavailableException('Неверный токен');
        }
    }

    public function refreshToken(string $refreshToken): array
    {
        if ($refreshToken === 'user_refresh_token') {
            return [
                'token' => 'new_user_fake_token',
                'refresh_token' => 'new_user_refresh_token'
            ];
        } elseif ($refreshToken === 'admin_refresh_token') {
            return [
                'token' => 'new_admin_fake_token',
                'refresh_token' => 'new_admin_refresh_token'
            ];
        } else {
            throw new BillingUnavailableException('Неверный refresh token');
        }
    }
}


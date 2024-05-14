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
        // Используем MockHttpClient для создания заглушки HTTP-клиента
        $httpClient = new MockHttpClient(new MockResponse(json_encode([])));
        $billingUrl = 'http://fake-billing-url.com'; // Пример URL, можно использовать любой

        // Вызываем конструктор базового класса с моковыми параметрами
        parent::__construct($httpClient, $billingUrl, $httpClient);
    }

    public function authorize(string $username, string $password): array
    {
        // Симуляция авторизации пользователя
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
        // Симуляция регистрации пользователя
        // Возвращаем данные в зависимости от ввода (можете добавить логику проверки существующих пользователей)
        if ($email !== 'user@example.com' && $email !== 'admin@example.com') {
            return [
                'email' => $email,
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
        // Симуляция получения информации о пользователе
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
        // Симуляция обновления токена
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

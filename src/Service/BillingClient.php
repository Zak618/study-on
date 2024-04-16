<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Exception\BillingUnavailableException;

class BillingClient
{
    private HttpClientInterface $client;
    private string $billingUrl;
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $client, string $billingUrl, HttpClientInterface $httpClient)
    {
        $this->client = $client;
        $this->billingUrl = $billingUrl;
        $this->httpClient = $httpClient;
    }

    public function authorize(string $username, string $password): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . '/api/v1/auth', [
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Сервис временно недоступен. Попробуйте авторизоваться позднее.');
        }
    }

    public function getUserInfo(string $token): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . '/api/v1/users/current', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при получении информации о пользователе. ' . $e->getMessage());
        }
    }

    public function refreshToken(string $refreshToken): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . '/api/v1/token/refresh', [
                'json' => [
                    'refresh_token' => $refreshToken,
                ],
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Не удалось обновить токен. ' . $e->getMessage());
        }
    }

    public function getCourses(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . '/api/v1/courses');
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при получении списка курсов. ' . $e->getMessage());
        }
    }

    public function getCourse(string $code): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . "/api/v1/courses/$code");
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при получении информации о курсе. ' . $e->getMessage());
        }
    }

    public function payForCourse(string $token, string $courseCode): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . "/api/v1/courses/$courseCode/pay", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при оплате курса. ' . $e->getMessage());
        }
    }

    public function getTransactions(string $token, array $filters = []): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . '/api/v1/transactions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => $filters,
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при получении истории транзакций. ' . $e->getMessage());
        }
    }
}

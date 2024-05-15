<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Exception\BillingUnavailableException;
use App\Entity\Course;

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
            // После успешного получения токена
            $_SESSION['user_token'] = 'Bearer ' . $token;

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

    public function payForCourse(string $courseCode, string $token): array
    {
        try {
            // Убедитесь, что URL формируется правильно
            $url = $this->billingUrl . "/api/v1/courses/$courseCode/pay";
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            if ($response->getStatusCode() >= 400) {
                throw new \Exception("HTTP Error: " . $response->getStatusCode() . " " . $response->getContent(false));
            }

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



    public function getUserCourses(string $token): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . '/api/v1/user/courses', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            $data = $response->toArray();

            // Обработка данных, если это необходимо, например:
            $courses = [];
            foreach ($data as $item) {
                $courses[$item['code']] = [
                    'type' => $item['type'],
                    'expires_at' => isset($item['expires_at']) ? $item['expires_at'] : null,
                ];
            }

            return $courses;
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Не удалось получить информацию о курсах пользователя.');
        }
    }

    public function deposit(string $token, float $amount): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . '/api/v1/deposit', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => [
                    'amount' => $amount,
                ],
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при пополнении баланса. ' . $e->getMessage());
        }
    }

    public function createCourse(Course $course): void
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . '/api/v1/courses/create', [
                'json' => [
                    'type' => $course->getType(),
                    'title' => $course->getTitle(),
                    'code' => $course->getCode(),
                    'price' => $course->getPrice(),
                    'description' => $course->getDescription(),
                ],
            ]);

            if ($response->getStatusCode() !== 201) {
                throw new \Exception('Не удалось создать курс в биллинге');
            }
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при создании курса в биллинге: ' . $e->getMessage());
        }
    }

    public function updateCourse(Course $course): void
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . '/api/v1/courses/' . $course->getCode() . '/update', [
                'json' => [
                    'type' => $course->getType(),
                    'title' => $course->getTitle(),
                    'code' => $course->getCode(),
                    'price' => $course->getPrice(),
                    'description' => $course->getDescription(),
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Не удалось обновить курс в биллинге');
            }
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при обновлении курса в биллинге: ' . $e->getMessage());
        }
    }
}

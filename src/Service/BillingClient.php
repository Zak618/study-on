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

    public function __construct(HttpClientInterface $client, HttpClientInterface $httpClient)
    {
        $this->client = $client;
        $this->billingUrl = $_ENV['BILLING_BASE_URL'];
        $this->httpClient = $httpClient;
    }

    public function authorize(string $username, string $password): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . $_ENV['BILLING_AUTH_PATH'], [
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

    public function register(string $email, string $password): void
    {
        try {
            $response = $this->httpClient->request('POST', $this->billingUrl . $_ENV['BILLING_REGISTER_PATH'], [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ]);

            if ($response->getStatusCode() !== 201) {
                throw new \Exception('Не удалось зарегистрировать пользователя');
            }
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при регистрации пользователя. ' . $e->getMessage());
        }
    }

    public function getUserInfo(string $token): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . $_ENV['BILLING_USER_INFO_PATH'], [
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
            $response = $this->httpClient->request('POST', $this->billingUrl . $_ENV['BILLING_REFRESH_TOKEN_PATH'], [
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
            $response = $this->httpClient->request('GET', $this->billingUrl . $_ENV['BILLING_COURSES_PATH']);
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при получении списка курсов. ' . $e->getMessage());
        }
    }

    public function getCourse(string $code): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->billingUrl . sprintf($_ENV['BILLING_COURSES_PATH'] . '/%s', $code));
            return $response->toArray();
        } catch (\Exception $e) {
            throw new BillingUnavailableException('Ошибка при получении информации о курсе. ' . $e->getMessage());
        }
    }

    public function payForCourse(string $courseCode, string $token): array
    {
        try {
            $url = $this->billingUrl . sprintf($_ENV['BILLING_PAY_COURSE_PATH'], $courseCode);
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
            $response = $this->httpClient->request('GET', $this->billingUrl . $_ENV['BILLING_TRANSACTIONS_PATH'], [
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
            $response = $this->httpClient->request('GET', $this->billingUrl . $_ENV['BILLING_USER_COURSES_PATH'], [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            $data = $response->toArray();

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
            $response = $this->httpClient->request('POST', $this->billingUrl . $_ENV['BILLING_DEPOSIT_PATH'], [
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
            $response = $this->httpClient->request('POST', $this->billingUrl . $_ENV['BILLING_CREATE_COURSE_PATH'], [
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
            $response = $this->httpClient->request('POST', $this->billingUrl . sprintf($_ENV['BILLING_UPDATE_COURSE_PATH'], $course->getCode()), [
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

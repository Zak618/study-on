<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Service\BillingClient;

// testPayForCourseRequiresAuthentication: Проверяет, что при отсутствии токена авторизации возвращается статус 401 и соответствующее сообщение об ошибке.
// testPayForCourseWithInvalidToken: Проверяет, что при предоставлении неправильного токена возвращается статус 400 и соответствующее сообщение об ошибке.
// testPayForCourseWithValidToken: Проверяет, что при предоставлении правильного токена возвращается статус 200 и успешный ответ с информацией о курсе.
// testPayForCourseWithServerError:Проверяет, что при возникновении ошибки на сервере возвращается статус 500 и соответствующее сообщение об ошибке.

class PaymentControllerTest extends WebTestCase
{
    public function testPayForCourseRequiresAuthentication(): void
    {
        $client = static::createClient();
        
        $client->request('POST', '/api/v1/courses/CS101/pay');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Token not provided or invalid']),
            $client->getResponse()->getContent()
        );
    }

    public function testPayForCourseWithInvalidToken(): void
    {
        $client = static::createClient();

        $billingClient = $this->createMock(BillingClient::class);
        $billingClient->method('payForCourse')
                      ->willReturn(['error' => 'Invalid token']);

        $client->getContainer()->set('App\Service\BillingClient', $billingClient);

        $client->request('POST', '/api/v1/courses/CS101/pay', [], [], [
            'HTTP_Authorization' => 'Bearer invalid_token',
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Invalid token']),
            $client->getResponse()->getContent()
        );
    }

    public function testPayForCourseWithValidToken(): void
    {
        $client = static::createClient();


        $billingClient = $this->createMock(BillingClient::class);
        $billingClient->method('payForCourse')
                      ->willReturn([
                          'success' => true,
                          'course_type' => 1,
                          'expires_at' => null,
                      ]);

        $client->getContainer()->set('App\Service\BillingClient', $billingClient);

        $client->request('POST', '/api/v1/courses/CS101/pay', [], [], [
            'HTTP_Authorization' => 'Bearer valid_token',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'success' => true,
                'course_type' => 1,
                'expires_at' => null,
            ]),
            $client->getResponse()->getContent()
        );
    }

    public function testPayForCourseWithServerError(): void
    {
        $client = static::createClient();

        $billingClient = $this->createMock(BillingClient::class);
        $billingClient->method('payForCourse')
                      ->willThrowException(new \Exception('Internal server error'));

        $client->getContainer()->set('App\Service\BillingClient', $billingClient);

        $client->request('POST', '/api/v1/courses/CS101/pay', [], [], [
            'HTTP_Authorization' => 'Bearer valid_token',
        ]);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Internal server error']),
            $client->getResponse()->getContent()
        );
    }
}

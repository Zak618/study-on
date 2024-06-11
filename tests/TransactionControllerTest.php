<?php

// namespace App\Tests\Controller;

// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use Symfony\Component\HttpFoundation\Response;
// use App\Service\BillingClient;

// // testTransactionHistoryRequiresAuthentication: Проверяет, что при отсутствии токена авторизации возвращается статус 401 и соответствующее сообщение об ошибке.
// // testTransactionHistoryWithInvalidToken: Проверяет, что при предоставлении неправильного токена возвращается статус 400 и соответствующее сообщение об ошибке.
// // testTransactionHistoryWithValidToken: Проверяет, что при предоставлении правильного токена возвращается статус 200 и список транзакций.
// // testTransactionHistoryWithFilters: Проверяет работу метода с фильтрами, переданными в запросе.


// class TransactionControllerTest extends WebTestCase
// {
//     public function testTransactionHistoryRequiresAuthentication(): void
//     {
//         $client = static::createClient();
        
//         $client->request('GET', '/api/v1/transactions');

//         $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
//         $this->assertJsonStringEqualsJsonString(
//             json_encode(['error' => 'Authentication required']),
//             $client->getResponse()->getContent()
//         );
//     }

//     public function testTransactionHistoryWithInvalidToken(): void
//     {
//         $client = static::createClient();


//         $billingClient = $this->createMock(BillingClient::class);
//         $billingClient->method('getTransactions')
//                       ->willReturn(['error' => 'Invalid token']);

//         $client->getContainer()->set('App\Service\BillingClient', $billingClient);

//         $client->request('GET', '/api/v1/transactions', [], [], [
//             'HTTP_Authorization' => 'Bearer invalid_token',
//         ]);

//         $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
//         $this->assertJsonStringEqualsJsonString(
//             json_encode(['error' => 'Invalid token']),
//             $client->getResponse()->getContent()
//         );
//     }

//     public function testTransactionHistoryWithValidToken(): void
//     {
//         $client = static::createClient();


//         $billingClient = $this->createMock(BillingClient::class);
//         $billingClient->method('getTransactions')
//                       ->willReturn([
//                           [
//                               'id' => 1,
//                               'created_at' => '2023-05-15T12:34:56+00:00',
//                               'type' => 'purchase',
//                               'course_code' => 'CS101',
//                               'amount' => 100.50,
//                               'expires_at' => null,
//                           ],
//                       ]);

//         $client->getContainer()->set('App\Service\BillingClient', $billingClient);

//         $client->request('GET', '/api/v1/transactions', [], [], [
//             'HTTP_Authorization' => 'Bearer valid_token',
//         ]);

//         $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//         $this->assertJsonStringEqualsJsonString(
//             json_encode([
//                 [
//                     'id' => 1,
//                     'created_at' => '2023-05-15T12:34:56+00:00',
//                     'type' => 'purchase',
//                     'course_code' => 'CS101',
//                     'amount' => 100.50,
//                     'expires_at' => null,
//                 ],
//             ]),
//             $client->getResponse()->getContent()
//         );
//     }

//     public function testTransactionHistoryWithFilters(): void
//     {
//         $client = static::createClient();


//         $billingClient = $this->createMock(BillingClient::class);
//         $billingClient->method('getTransactions')
//                       ->willReturn([
//                           [
//                               'id' => 2,
//                               'created_at' => '2023-06-15T12:34:56+00:00',
//                               'type' => 'rent',
//                               'course_code' => 'PY202',
//                               'amount' => 50.00,
//                               'expires_at' => '2023-07-15T12:34:56+00:00',
//                           ],
//                       ]);

//         $client->getContainer()->set('App\Service\BillingClient', $billingClient);

//         $client->request('GET', '/api/v1/transactions', ['type' => 'rent'], [], [
//             'HTTP_Authorization' => 'Bearer valid_token',
//         ]);

//         $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//         $this->assertJsonStringEqualsJsonString(
//             json_encode([
//                 [
//                     'id' => 2,
//                     'created_at' => '2023-06-15T12:34:56+00:00',
//                     'type' => 'rent',
//                     'course_code' => 'PY202',
//                     'amount' => 50.00,
//                     'expires_at' => '2023-07-15T12:34:56+00:00',
//                 ],
//             ]),
//             $client->getResponse()->getContent()
//         );
//     }
// }

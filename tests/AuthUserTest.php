<?php

// namespace App\Tests\Controller;

// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use App\Tests\Mock\BillingClientMock;

// class AuthUserTest extends WebTestCase
// {
//     public function testUserAuthentication(): void
//     {
//         $client = static::createClient();

//         // Подключаем мок сервиса BillingClient
//         $client->getContainer()->set('app.service.billing_client', new BillingClientMock());

//         // Аутентификация как обычный пользователь
//         $billingClient = $client->getContainer()->get('app.service.billing_client');
//         $response = $billingClient->authorize('user@example.com', 'password123');

//         // Проверяем возвращаемые значения
//         $this->assertEquals('user_fake_token', $response['token']);
//     }

//     public function testRegister(): void
//     {
//         $client = static::createClient();
//         $client->disableReboot();

//         $client->getContainer()->set('app.service.billing_client', new BillingClientMock());

//         $crawler = $client->request('GET', '/register');
//         $form = $crawler->selectButton('Зарегистрироваться')->form([
//             'registration_form[email]' => 'en112d@example.com',
//             'registration_form[plainPassword][first]' => '123456',
//             'registration_form[plainPassword][second]' => '123456'
//         ]);

//         $client->submit($form);

//         // Проверяем редирект на страницу профиля или курсов
//         $this->assertResponseRedirects('/register');
//     }

//     public function testRefreshToken(): void
//     {
//         $client = static::createClient();

//         // Подключаем мок сервиса BillingClient
//         $client->getContainer()->set('app.service.billing_client', new BillingClientMock());

//         // Имитация запроса на обновление токена
//         $billingClient = $client->getContainer()->get('app.service.billing_client');
//         $response = $billingClient->refreshToken('user_refresh_token');

//         // Проверяем возвращаемые значения
//         $this->assertEquals('new_user_fake_token', $response['token']);
//     }

//     public function testGetCurrentUser(): void
//     {
//         $client = static::createClient();

//         // Подключаем мок сервиса BillingClient
//         $client->getContainer()->set('app.service.billing_client', new BillingClientMock());

//         // Имитация получения информации о текущем пользователе
//         $billingClient = $client->getContainer()->get('app.service.billing_client');
//         $response = $billingClient->getUserInfo('user_fake_token');

//         // Проверяем возвращаемые значения
//         $this->assertEquals('user@example.com', $response['email']);
//         $this->assertEquals(['ROLE_USER'], $response['roles']);
//         $this->assertEquals(100.0, $response['balance']);
//     }
// }

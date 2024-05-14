<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ErrorTest extends WebTestCase
{
    public function testPageNotFound(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/non-existing-page'); // Запрос к несуществующему URL

        // Проверяем, что статус ответа равен 404
        $this->assertResponseStatusCodeSame(404);
    }

    // public function testEditLessonAccessDeniedForRegularUser()
    // {
    //     $client = static::createClient();

    //     // Логинимся под пользователем, у которого нет прав на редактирование урока
    //     $crawler = $client->request('GET', '/login');
    //     $form = $crawler->selectButton('Войти')->form([
    //         'email' => 'user@example.com',
    //         'password' => 'password123',
    //     ]);
    //     $client->submit($form);

    //     // Пытаемся получить доступ к странице редактирования урока
    //     $client->request('GET', '/lessons/192/edit');

    //     // Проверяем, что сервер возвращает статус код 403 Forbidden
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    // }

    // public function testAuthUserNotServer()
    // {
    //     $client = static::createClient();

    //     // Логинимся под пользователем, у которого нет прав на редактирование урока
    //     $crawler = $client->request('GET', '/login');
    //     $form = $crawler->selectButton('Войти')->form([
    //         'email' => 'user@example.com',
    //         'password' => 'password123',
    //     ]);
    //     $client->submit($form);

    //     // Проверяем, что сервер возвращает статус код 500
    //     $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    // }
}

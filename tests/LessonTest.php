<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Lesson;

class LessonTest extends WebTestCase
{
    public function testEditLesson(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Аутентификация как администратор
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Войти')->form([
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
        ]);
        $client->submit($form);


        $crawler = $client->request('GET', '/courses/');

        // Находим ссылку на курс по названию
        $link = $crawler->filter('h5:contains("Python Exclusive")')->closest('.card')->filter('.btn')->link();

        // Переходим по ссылке
        $crawler = $client->click($link);

        // Открываем урок
        $link = $crawler->selectLink('Основы HTML и CSS для Python-разработчиков')->link();
        $crawler = $client->click($link);

        // Нажимаем на кнопку редактирования урока
        $link = $crawler->selectLink('Редактировать')->link();
        $crawler = $client->click($link);

        // Меняем описание урока
        $form = $crawler->selectButton('Сохранить')->form([
            'lesson[name]' => 'Что такое веб разработка?',
            'lesson[content]' => 'Что такое сайт и как его создать?',
            'lesson[number]' => 2333333333,
        ]);
        $client->submit($form);

        // После отправки формы и перенаправления
        $this->assertSelectorExists('li', 'Значение поля должно быть в пределах от 1 до 10000');
    }

    
}

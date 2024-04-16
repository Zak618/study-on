<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Course;

class CourseTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/');

        $this->assertResponseIsSuccessful();
    }

    public function testShowNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/courses/999');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testCoursesExistInDatabase(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $entityManager = $container->get('doctrine.orm.entity_manager');
        $courseRepository = $entityManager->getRepository(Course::class);

        $phpCourse = $courseRepository->findOneBy(['code' => 'CODE1']);
        $this->assertNotNull($phpCourse, 'Курс "FullStack Pro MAX" не найден в базе данных.');

        $pythonCourse = $courseRepository->findOneBy(['code' => 'CODE2']);
        $this->assertNotNull($pythonCourse, 'Курс "Python Exclusive" не найден в базе данных.');
    }
    
    public function testAddNewCourse(): void
{
    $client = static::createClient();
    $client->followRedirects(true);

    // Аутентификация как администратор
    $crawler = $client->request('GET', '/login');
    $form = $crawler->selectButton('Войти')->form([
        'email' => 'admin@gmail.com',
        'password' => 'password',
    ]);
    $client->submit($form);

    // Переход к форме создания нового курса
    $crawler = $client->request('GET', '/courses/new');
    
    // Отправка формы с данными нового курса
    $form = $crawler->selectButton('Сохранить')->form([
        'course[code]' => 'NEW-CODE',
        'course[name]' => 'New Course',
        'course[description]' => 'Description for new course.',
    ]);
    $client->submit($form);

    // Проверка результата
    $crawler = $client->request('GET', '/courses/');
    $this->assertResponseIsSuccessful();
    $this->assertStringContainsString('New Course', $client->getResponse()->getContent());
}

}

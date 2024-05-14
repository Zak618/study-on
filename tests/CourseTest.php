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
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
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

    public function testIndexContainsCourses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/');
        $this->assertResponseIsSuccessful();

        $this->assertGreaterThanOrEqual(1, $crawler->filter('.card')->count(), 'Должен отображаться хотя бы один курс');
    }


    public function testCreateCourseWithExistingName(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        // Логинимся как администратор
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Войти')->form([
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
        ]);
        $client->submit($form);

        // Создаем курс с названием "FULL STACK PRO MAX"
        $crawler = $client->request('GET', '/courses/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form([
            'course[code]' => '6CODE',
            'course[name]' => 'FullStack Pro MAX',
            'course[description]' => 'A new awesome course.',
        ]);
        $client->submit($form);

        // Повторно пытаемся создать курс с тем же названием
        $crawler = $client->request('GET', '/courses/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form([
            'course[code]' => '6CODE',
            'course[name]' => 'FullStack Pro MAX',
            'course[description]' => 'A new awesome course.',
        ]);
        $client->submit($form);

        // Проверяем, что на странице есть сообщение о существующем курсе
        $this->assertSelectorExists('span', 'Такое название уже есть!');
    }



    public function testEditCourse(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Войти')->form([
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
        ]);
        $client->submit($form);



        $crawler = $client->request('GET', '/courses/');

        // Находим ссылку на курс по названию
        $link = $crawler->filter('h5:contains("FullStack Pro MAX")')->closest('.card')->filter('.btn')->link();

        // Переходим по ссылке
        $crawler = $client->click($link);

        $link = $crawler->selectLink('Редактировать')->link();
        $crawler = $client->click($link);

        $this->assertResponseIsSuccessful();

        // Заполняем форму редактирования
        $form = $crawler->selectButton('Сохранить')->form([
            'course[code]' => 'CODE 1',
            'course[name]' => 'FullStack Pro MAX1',
            'course[description]' => 'Данный курс создан для начинающих веб разработчиков',
        ]);
        $client->submit($form);

        $this->assertResponseIsSuccessful();
    }

    public function testNoNameEditCourse(): void
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
        $link = $crawler->filter('h5:contains("FullStack Pro MAX")')->closest('.card')->filter('.btn')->link();

        // Переходим по ссылке
        $crawler = $client->click($link);

        $link = $crawler->selectLink('Редактировать')->link();
        $crawler = $client->click($link);
	
        // Заполняем форму редактирования
        $form = $crawler->selectButton('Сохранить')->form([
            'course[code]' => 'CODE1',
            'course[name]' => '',
            'course[description]' => 'Данный курс создан для начинающих веб разработчиков',
        ]);
        $client->submit($form);

        $this->assertSelectorExists('li', 'Название не может быть пустым');
    }

    public function testNoCodeEditCourse(): void
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
        $link = $crawler->filter('h5:contains("FullStack Pro MAX")')->closest('.card')->filter('.btn')->link();

        // Переходим по ссылке
        $crawler = $client->click($link);

        $link = $crawler->selectLink('Редактировать')->link();
        $crawler = $client->click($link);
	
        // Заполняем форму редактирования
        $form = $crawler->selectButton('Сохранить')->form([
            'course[code]' => '',
            'course[name]' => 'FullStack Pro MAX',
            'course[description]' => 'Данный курс создан для начинающих веб разработчиков',
        ]);
        $client->submit($form);

        $this->assertSelectorExists('li', 'Символьный код не может быть пустым');
    }
}

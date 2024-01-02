<?php

 namespace App\DataFixtures;

 use App\Entity\Course;
 use App\Entity\Lesson;
 use Doctrine\Bundle\FixturesBundle\Fixture;
 use Doctrine\Persistence\ObjectManager;

 class AppFixtures extends Fixture
 {
     public function load(ObjectManager $manager): void
     {
         $phpCourse = new Course();
         $phpCourse
             ->setCode('CODE1')
             ->setName('FullStack Pro MAX')
             ->setDescription('Курс "FullStack Pro MAX" предоставляет студентам все необходимые знания и навыки для работы как с Frontend (HTML, CSS, JS, Figma), так и с Backend (Git, VS Code, PHP, SQL, MySQL/PHPMyAdmin). По окончанию курса студенты смогут создавать полноценные веб-приложения с использованием различных технологий и инструментов.');

         $lesson = new Lesson();
         $lesson
             ->setName('Что такое создание сайтов?')
             ->setContent('Что такое сайты?')
             ->setNumber(1);
         $phpCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('Фронтенд и бэкенд')
             ->setContent('Сегодня мы поговорим о том, какие направленя бывают в WEB-разработке!')
             ->setNumber(2);
         $phpCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('URL-адреса')
             ->setContent('Как мы переходим на другую страницу!')
             ->setNumber(3);
         $phpCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('Микрофреймворки')
             ->setContent('Рассмотреть идею микрофреймворков.')
             ->setNumber(4);
         $phpCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('Хостинг')
             ->setContent('Делимся своим творением со всем миром')
             ->setNumber(5);
         $phpCourse->addLesson($lesson);

         $manager->persist($phpCourse);

         $jsCourse = new Course();
         $jsCourse
             ->setCode('CODE2')
             ->setName('Python Exclusive')
             ->setDescription('Курс "Введение в программирование на Python" подготовит вас к основам программирования, позволит понять логику программирования и научит вас использовать язык Python для создания простых программ и автоматизации задач.');

         $lesson = new Lesson();
         $lesson
             ->setName('Введение')
             ->setContent('Познакомиться с курсом.')
             ->setNumber(1);
         $jsCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('Основы HTML и CSS для Python-разработчиков')
             ->setContent('Как же WEB используется в PYTHON')
             ->setNumber(2);
         $jsCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('Работа с фреймворком Flask')
             ->setContent('Изучим азы FLASK')
             ->setNumber(3);
         $jsCourse->addLesson($lesson);

         $lesson = new Lesson();
         $lesson
             ->setName('Создание баз данных с помощью SQLAlchemy')
             ->setContent('Пора сохранить наши данные')
             ->setNumber(4);
         $jsCourse->addLesson($lesson);

         $manager->persist($jsCourse);

         $manager->flush();
     }
 }
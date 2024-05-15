<?php
namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Service\BillingClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private BillingClient $billingClient;

    public function __construct(BillingClient $billingClient)
    {
        $this->billingClient = $billingClient;
    }

    public function load(ObjectManager $manager): void
    {
        // Получение курсов по API
        $courses = $this->billingClient->getCourses();

        foreach ($courses as $courseData) {
            $course = new Course();
            $course
                ->setCode($courseData['code'])
                ->setTitle($courseData['title'])
                ->setDescription($courseData['description'])
                ->setType($courseData['type'])
                ->setPrice($courseData['price']);

            $this->addLessonsToCourse($manager, $course, $courseData['code']);

            $manager->persist($course);
        }

        $manager->flush();
    }

    private function addLessonsToCourse(ObjectManager $manager, Course $course, string $courseCode): void
    {
        $lessonsData = $this->getLessonsDataForCourse($courseCode);

        foreach ($lessonsData as $lessonData) {
            $lesson = new Lesson();
            $lesson
                ->setName($lessonData['name'])
                ->setContent($lessonData['content'])
                ->setNumber($lessonData['number'])
                ->setCourse($course);
            $manager->persist($lesson);
        }
    }

    private function getLessonsDataForCourse(string $courseCode): array
    {
        $lessons = [];

        if ($courseCode === 'course_101') {
            $lessons = [
                [
                    'name' => 'URL-адреса',
                    'content' => 'Как мы переходим на другую страницу!',
                    'number' => 3,
                ],
                [
                    'name' => 'Микрофреймворки',
                    'content' => 'Рассмотреть идею микрофреймворков.',
                    'number' => 4,
                ],
                [
                    'name' => 'Хостинг',
                    'content' => 'Делимся своим творением со всем миром',
                    'number' => 5,
                ],
            ];
        } elseif ($courseCode === 'course_102') {
            $lessons = [
                [
                    'name' => 'Введение',
                    'content' => 'Познакомиться с курсом.',
                    'number' => 1,
                ],
                [
                    'name' => 'Основы HTML и CSS для Python-разработчиков',
                    'content' => 'Как же WEB используется в PYTHON',
                    'number' => 2,
                ],
                [
                    'name' => 'Работа с фреймворком Flask',
                    'content' => 'Изучим азы FLASK',
                    'number' => 3,
                ],
                [
                    'name' => 'Создание баз данных с помощью SQLAlchemy',
                    'content' => 'Пора сохранить наши данные',
                    'number' => 4,
                ],
            ];
        }

        return $lessons;
    }
}

<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Form\CourseType;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BillingClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Security\User;

#[Route('/courses')]
class CourseController extends AbstractController
{
    private BillingClient $billingClient;
    private LessonRepository $lessonRepository;

    public function __construct(BillingClient $billingClient, LessonRepository $lessonRepository)
    {
        $this->billingClient = $billingClient;
        $this->lessonRepository = $lessonRepository;
    }

    #[Route('/', name: 'app_course_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $apiToken = $user->getApiToken();

        // Получаем список всех курсов
        $courses = $this->billingClient->getCourses();

        // Получаем список транзакций пользователя
        $transactions = $this->billingClient->getTransactions($apiToken);

        // Преобразуем список транзакций в массив с ключами по коду курса
        $transactionsByCourseCode = [];
        foreach ($transactions as $transaction) {
            $transactionsByCourseCode[$transaction['course_code']] = $transaction;
        }

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'transactions' => $transactionsByCourseCode,
            'token' => $apiToken,
        ]);
    }


    #[Route('/new', name: 'app_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingCourse = $courseRepository->findOneBy(['code' => $course->getCode()]);
            if ($existingCourse) {
                $this->addFlash('error', 'Курс с таким символьным кодом уже существует!');
                return $this->redirectToRoute('app_course_new');
            }

            // Создание курса в биллинге
            try {
                $this->billingClient->createCourse($course);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Ошибка при создании курса в биллинге: ' . $e->getMessage());
                return $this->redirectToRoute('app_course_new');
            }

            $courseRepository->save($course, true);
            return $this->redirectToRoute('app_course_show', ['code' => $course->getCode()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }


    #[Route('/courses/{code}', name: 'app_course_show', methods: ['GET'])]
    public function show(string $code, CourseRepository $courseRepository): Response
    {

        $course = $courseRepository->findOneBy(['code' => $code]);

        if (!$course) {
            // Получение информации о курсе по API
            $courseData = $this->billingClient->getCourse($code);

            // Создание объекта Course на основе данных API
            $course = new Course();
            $course
                ->setCode($courseData['code'])
                ->setTitle($courseData['title'])
                ->setDescription($courseData['description'])
                ->setType($courseData['type'])
                ->setPrice($courseData['price']);

            // Сохранение курса в базу данных
            $courseRepository->save($course, true);
        }

        // Получение уроков для курса
        $lessons = $this->lessonRepository->findBy(['course' => $course]);

        return $this->render('course/show.html.twig', [
            'course' => $course,
            'lessons' => $lessons,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Обновление курса в биллинге
            try {
                $this->billingClient->updateCourse($course);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Ошибка при обновлении курса в биллинге: ' . $e->getMessage());
            }

            $courseRepository->save($course, true);
            return $this->redirectToRoute('app_course_show', ['id' => $course->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course/edit.html.twig', ['course' => $course, 'form' => $form]);
    }


    #[Route('/{id}', name: 'app_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            $courseRepository->remove($course, true);
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{code}/buy', name: 'app_course_buy', methods: ['POST'])]
    public function buyCourse(Request $request, string $code): Response
    {
        $token = $request->request->get('token');  // Получение токена из POST-параметров
        try {
            $result = $this->billingClient->payForCourse($code, $token);
            if (isset($result['success']) && $result['success']) {
                $this->addFlash('success', 'Курс успешно куплен и доступен для изучения.');
            } else {
                $this->addFlash('error', 'Не удалось купить курс: ' . ($result['message'] ?? 'Ошибка сервера'));
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка при покупке курса: ' . $e->getMessage());
        }
        return $this->redirectToRoute('app_course_show', ['code' => $code]);
    }



    #[Route('/{code}/rent', name: 'app_course_rent', methods: ['POST'])]
    public function rentCourse(Request $request, string $code): Response
    {
        $token = $request->request->get('token');  // Получение токена из POST-параметров
        try {
            $result = $this->billingClient->payForCourse($code, $token);
            if (isset($result['expires_at'])) {
                $this->addFlash('success', 'Курс успешно арендован до ' . $result['expires_at']);
            } else {
                $this->addFlash('error', 'Не удалось арендовать курс: ' . ($result['message'] ?? 'Ошибка сервера'));
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка аренды курса: ' . $e->getMessage());
        }
        return $this->redirectToRoute('app_course_show', ['code' => $code]);
    }
}

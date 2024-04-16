<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Form\CourseType;
use App\Form\LessonType;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Service\BillingClient;
use App\Exception\BillingUnavailableException;

#[Route('/courses')]
class CourseController extends AbstractController
{
    private BillingClient $billingClient;

    public function __construct(BillingClient $billingClient)
    {
        $this->billingClient = $billingClient;
    }

    #[Route('/new', name: 'app_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('У вас нет доступа к этой операции');
        }
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->save($course, true);
        }

        return $this->renderForm('course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_course_index', methods: ['GET'])]
public function index(Request $request): Response
{
    $userToken = $request->headers->get('Authorization', '');
    $userInfo = [];
    $courses = [];

    if ($userToken) {
        try {
            // Попытка получить информацию о текущем пользователе
            $userInfo = $this->billingClient->getUserInfo($userToken);
        } catch (BillingUnavailableException $e) {
            $this->addFlash('error', 'Не удалось получить информацию о пользователе.');
        }
    }

    try {
        // Попытка получить список курсов
        $courses = $this->billingClient->getCourses();
    } catch (BillingUnavailableException $e) {
        $this->addFlash('error', 'Не удалось получить список курсов.');
    }

    return $this->render('course/index.html.twig', [
        'courses' => $courses,
        'userInfo' => $userInfo, // Передача данных о пользователе в шаблон
        'userToken' => $userToken
    ]);
}


    #[Route('/{code}', name: 'app_course_show', methods: ['GET'])]
    public function show(string $code): Response
    {
        // Получаем информацию о курсе по его коду
        $course = $this->billingClient->getCourse($code);

        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    



    #[Route('/{id}/edit', name: 'app_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('У вас нет доступа к этой операции');
        }
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->save($course, true);

            return $this->redirectToRoute(
                'app_course_show',
                ['id' => $course->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('У вас нет доступа к этой операции');
        }
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            $courseRepository->remove($course, true);
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('{id}/new/lesson', name: 'app_lesson_new', methods: ['GET', 'POST'])]
    public function newLesson(Request $request, Course $course, LessonRepository $lessonRepository): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('У вас нет доступа к этой операции');
        }
        $lesson = new Lesson();
        $lesson->setCourse($course);
        $form = $this->createForm(LessonType::class, $lesson, [
            'course' => $course,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessonRepository->save($lesson, true);

            return $this->redirectToRoute(
                'app_course_show',
                ['id' => $course->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
            'course' => $course,
        ]);
    }
}

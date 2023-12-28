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

 #[Route('/courses')]
 class CourseController extends AbstractController
 {
     #[Route('/', name: 'app_course_index', methods: ['GET'])]
     public function index(CourseRepository $courseRepository): Response
     {
         return $this->render('course/index.html.twig', [
             'courses' => $courseRepository->findAll(),
         ]);
     }

     #[Route('/new', name: 'app_course_new', methods: ['GET', 'POST'])]
     public function new(Request $request, CourseRepository $courseRepository): Response
     {
         $course = new Course();
         $form = $this->createForm(CourseType::class, $course);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $courseRepository->save($course, true);

             return $this->redirectToRoute(
                 'app_course_show', ['id' => $course->getId()],
                 Response::HTTP_SEE_OTHER
             );
         }

         return $this->renderForm('course/new.html.twig', [
             'course' => $course,
             'form' => $form,
         ]);
     }

     #[Route('/{id}', name: 'app_course_show', methods: ['GET'])]
     public function show(Course $course): Response
     {
         return $this->render('course/show.html.twig', [
             'course' => $course,
         ]);
     }

     #[Route('/{id}/edit', name: 'app_course_edit', methods: ['GET', 'POST'])]
     public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
     {
         $form = $this->createForm(CourseType::class, $course);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $courseRepository->save($course, true);

             return $this->redirectToRoute(
                 'app_course_show', ['id' => $course->getId()],
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
         if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
             $courseRepository->remove($course, true);
         }

         return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
     }

     #[Route('{id}/new/lesson', name: 'app_lesson_new', methods: ['GET', 'POST'])]
     public function newLesson(Request $request, Course $course, LessonRepository $lessonRepository): Response
     {
         $lesson = new Lesson();
         $lesson->setCourse($course);
         $form = $this->createForm(LessonType::class, $lesson, [
             'course' => $course,
         ]);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $lessonRepository->save($lesson, true);

             return $this->redirectToRoute(
                 'app_course_show', ['id' => $course->getId()],
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

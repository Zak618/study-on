<?php

 namespace App\Controller;

 use App\Entity\Course;
 use App\Entity\Lesson;
 use App\Form\LessonType;
 use App\Repository\LessonRepository;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\Security\Core\Exception\AccessDeniedException;
 #[Route('/lessons')]
 class LessonController extends AbstractController
 {
     #[Route('/{id}', name: 'app_lesson_show', methods: ['GET'])]
     public function show(Lesson $lesson): Response
     {
         return $this->render('lesson/show.html.twig', [
             'lesson' => $lesson,
         ]);
     }

     #[Route('/{id}/edit', name: 'app_lesson_edit', methods: ['GET', 'POST'])]
     
     public function edit(Request $request, Lesson $lesson, LessonRepository $lessonRepository): Response
     {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('У вас нет доступа к этой операции');
        }
         $form = $this->createForm(LessonType::class, $lesson);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $lessonRepository->save($lesson, true);

             return $this->redirectToRoute(
                 'app_course_show', ['id' => $lesson->getCourse()->getId()],
                 Response::HTTP_SEE_OTHER
             );
         }

         return $this->render('lesson/edit.html.twig', [
             'lesson' => $lesson,
             'form' => $form,
         ]);
     }

     #[Route('/{id}', name: 'app_lesson_delete', methods: ['POST'])]
     public function delete(Request $request, Lesson $lesson, LessonRepository $lessonRepository): Response
     {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('У вас нет доступа к этой операции');
        }
         $courseId = $lesson->getCourse()->getId();
         if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
             $lessonRepository->remove($lesson, true);
         }

         return $this->redirectToRoute(
             'app_course_show', ['id' => $courseId],
             Response::HTTP_SEE_OTHER
         );
     }
 }
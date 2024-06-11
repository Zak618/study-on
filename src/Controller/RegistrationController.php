<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Service\BillingClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, BillingClient $billingClient): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $billingClient->register($formData['email'], $formData['plainPassword']);
                $this->addFlash('success', 'Регистрация успешна');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Сервис временно недоступен. Попробуйте зарегистрироваться позднее');
            }

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

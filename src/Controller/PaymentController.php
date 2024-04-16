<?php

namespace App\Controller;

use App\Service\BillingClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/api/v1/courses/{code}/pay', name: 'pay_for_course', methods: ['POST'])]
    public function payForCourse(BillingClient $billingClient, Request $request, string $code): JsonResponse
    {
        $userToken = $request->headers->get('Authorization', '');
        
        if (!$userToken) {
            return $this->json(['error' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
        }

        $paymentResult = $billingClient->payForCourse($userToken, $code);

        if (isset($paymentResult['error'])) {
            return $this->json(['error' => $paymentResult['error']], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($paymentResult);
    }
}

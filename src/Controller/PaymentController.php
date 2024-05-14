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
    $token = $request->headers->get('Authorization');
    if (!$token || !str_starts_with($token, 'Bearer ')) {
        return $this->json(['error' => 'Token not provided or invalid'], Response::HTTP_UNAUTHORIZED);
    }
    $token = str_replace('Bearer ', '', $token);
    $paymentResult = $billingClient->payForCourse($code, $token);
    if (isset($paymentResult['error'])) {
        return $this->json(['error' => $paymentResult['error']], Response::HTTP_BAD_REQUEST);
    }
    return $this->json($paymentResult);
}

}
<?php

namespace App\Controller;

use App\Service\BillingClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route('/api/v1/transactions', name: 'transaction_history', methods: ['GET'])]
    public function transactionHistory(BillingClient $billingClient, Request $request): JsonResponse
    {
        $userToken = $request->headers->get('Authorization', '');

        if (!$userToken) {
            return $this->json(['error' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
        }

        $filters = $request->query->all(); // Получаем фильтры из запроса, если они есть
        $transactions = $billingClient->getTransactions($userToken, $filters);

        if (isset($transactions['error'])) {
            return $this->json(['error' => $transactions['error']], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($transactions);
    }
}


<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\User;
use App\Service\BillingClient;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(BillingClient $billingClient): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Пользовательский объект не является экземпляром App\Security\User.');
        }

        $apiToken = $user->getApiToken();

        try {
            $userInfo = $billingClient->getUserInfo($apiToken);
            $balance = $userInfo['balance'] ?? 0;
            $transactions = $billingClient->getTransactions($apiToken);
        } catch (\Exception $e) {
            $balance = 0;
            $transactions = [];
            $this->addFlash('error', 'Ошибка при получении данных: ' . $e->getMessage());
        }

        return $this->render('profile/index.html.twig', [
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'balance' => $balance,
            'transactions' => $transactions,
        ]);
    }

    #[Route('/profile/deposit', name: 'app_profile_deposit', methods: ['POST'])]
    public function deposit(Request $request, BillingClient $billingClient): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('Пользовательский объект не является экземпляром App\Security\User.');
        }

        $amount = $request->request->get('amount');
        $apiToken = $user->getApiToken();

        try {
            $billingClient->deposit($apiToken, (float)$amount);
            $this->addFlash('success', 'Баланс успешно пополнен.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка при пополнении баланса: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_profile');
    }
}

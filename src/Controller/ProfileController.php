<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Security\User;
use App\Service\BillingClient;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(BillingClient $billingClient): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Пользовательский объект не является экземпляром App\Security\User.');
        }

        $apiToken = $user->getApiToken(); 

        $response = $this->httpClient->request('GET', 'http://billing.study-on.local/api/v1/users/current', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],
        ]);

        $data = $response->toArray(); 
        $balance = $data['balance'] ?? 0;

        // Получаем историю транзакций
        try {
            $transactions = $billingClient->getTransactions($apiToken);
        } catch (\Exception $e) {
            $transactions = [];
            $this->addFlash('error', 'Ошибка при получении истории транзакций: ' . $e->getMessage());
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
            $result = $billingClient->deposit($apiToken, (float)$amount);
            $this->addFlash('success', 'Баланс успешно пополнен.');
            return $this->redirectToRoute('app_profile');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка при пополнении баланса: ' . $e->getMessage());
            return $this->redirectToRoute('app_profile');
        }
    }

}

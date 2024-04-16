<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Security\User;

class ProfileController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
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

        return $this->render('profile/index.html.twig', [
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'balance' => $balance,
        ]);
    }
}

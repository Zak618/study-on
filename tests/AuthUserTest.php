<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class AuthUserTest extends WebTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    public function testRegister()
    {
        // Ваш тестовый код
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $form = $crawler->selectButton('Register')->form([
            'registration_form[email]' => 'newuser@example.com',
            'registration_form[plainPassword][first]' => '123456',
            'registration_form[plainPassword][second]' => '123456',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/register');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Удаление данных из всех таблиц
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($connection->getSchemaManager()->listTableNames() as $tableName) {
            $connection->executeStatement($platform->getTruncateTableSQL($tableName, true));
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');

        $this->entityManager->close();
        $this->entityManager = null; // Избегаем утечек памяти
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240515075354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Добавление поля price с дефолтным значением NULL
        $this->addSql('ALTER TABLE course ADD price DOUBLE PRECISION DEFAULT NULL');
        
        // Добавление поля type с временным дефолтным значением
        $this->addSql('ALTER TABLE course ADD type INT DEFAULT 0');
        
        // Удаление временного дефолтного значения
        $this->addSql('ALTER TABLE course ALTER COLUMN type SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Удаление полей
        $this->addSql('ALTER TABLE course DROP price');
        $this->addSql('ALTER TABLE course DROP type');
    }
}

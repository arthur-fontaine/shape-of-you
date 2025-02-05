<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250203131152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add password to User';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD password VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP password');
    }
}

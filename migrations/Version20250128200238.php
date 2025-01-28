<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250128200238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add name column to clothing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE clothing ADD name VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE clothing DROP name');
    }
}

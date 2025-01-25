<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125133750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add image_url to clothing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE clothing ADD image_url TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE clothing DROP image_url');
    }
}

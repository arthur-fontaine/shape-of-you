<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250305104727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create new table Brand';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE brand (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE brand');
    }
}

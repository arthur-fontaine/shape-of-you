<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250211124331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Activate the vector extension and create the user_vector table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS vector');
        $this->addSql('CREATE TABLE user_vector (id SERIAL NOT NULL, owner_id INT NOT NULL, vector vector(1024) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E3C53667E3C61F9 ON user_vector (owner_id)');
        $this->addSql('ALTER TABLE user_vector ADD CONSTRAINT FK_7E3C53667E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP EXTENSION vector');
        $this->addSql('ALTER TABLE user_vector DROP CONSTRAINT FK_7E3C53667E3C61F9');
        $this->addSql('DROP TABLE user_vector');
    }
}

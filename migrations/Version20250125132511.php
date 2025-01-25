<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125132511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create interaction table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE interaction (id SERIAL NOT NULL, origin_user_id INT NOT NULL, interaction JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_378DFDA71219C6F8 ON interaction (origin_user_id)');
        $this->addSql('ALTER TABLE interaction ADD CONSTRAINT FK_378DFDA71219C6F8 FOREIGN KEY (origin_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interaction DROP CONSTRAINT FK_378DFDA71219C6F8');
        $this->addSql('DROP TABLE interaction');
    }
}

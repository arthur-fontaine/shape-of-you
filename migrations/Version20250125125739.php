<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125125739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE clothing_list (id SERIAL NOT NULL, creator_id INT NOT NULL, name VARCHAR(50) NOT NULL, is_bookmark_list BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_943379EF61220EA6 ON clothing_list (creator_id)');
        $this->addSql('CREATE TABLE clothing_list_clothing (clothing_list_id INT NOT NULL, clothing_id INT NOT NULL, PRIMARY KEY(clothing_list_id, clothing_id))');
        $this->addSql('CREATE INDEX IDX_74B052CA85D2F190 ON clothing_list_clothing (clothing_list_id)');
        $this->addSql('CREATE INDEX IDX_74B052CA4CFB3290 ON clothing_list_clothing (clothing_id)');
        $this->addSql('ALTER TABLE clothing_list ADD CONSTRAINT FK_943379EF61220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE clothing_list_clothing ADD CONSTRAINT FK_74B052CA85D2F190 FOREIGN KEY (clothing_list_id) REFERENCES clothing_list (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE clothing_list_clothing ADD CONSTRAINT FK_74B052CA4CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE clothing_list DROP CONSTRAINT FK_943379EF61220EA6');
        $this->addSql('ALTER TABLE clothing_list_clothing DROP CONSTRAINT FK_74B052CA85D2F190');
        $this->addSql('ALTER TABLE clothing_list_clothing DROP CONSTRAINT FK_74B052CA4CFB3290');
        $this->addSql('DROP TABLE clothing_list');
        $this->addSql('DROP TABLE clothing_list_clothing');
    }
}

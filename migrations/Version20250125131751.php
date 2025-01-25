<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125131751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create post_rate table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post_rate (id SERIAL NOT NULL, rater_id INT NOT NULL, post_id INT NOT NULL, rate10 SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_16B958323FC1CD0A ON post_rate (rater_id)');
        $this->addSql('CREATE INDEX IDX_16B958324B89032C ON post_rate (post_id)');
        $this->addSql('ALTER TABLE post_rate ADD CONSTRAINT FK_16B958323FC1CD0A FOREIGN KEY (rater_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_rate ADD CONSTRAINT FK_16B958324B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post_rate DROP CONSTRAINT FK_16B958323FC1CD0A');
        $this->addSql('ALTER TABLE post_rate DROP CONSTRAINT FK_16B958324B89032C');
        $this->addSql('DROP TABLE post_rate');
    }
}

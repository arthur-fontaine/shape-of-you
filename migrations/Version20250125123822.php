<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125123822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create clothing table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE clothing (id SERIAL NOT NULL, type VARCHAR(255) NOT NULL, color TEXT NOT NULL, measurements JSONB NOT NULL, social_rate5 SMALLINT DEFAULT NULL, ecology_rate5 SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN clothing.color IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE clothing');
    }
}

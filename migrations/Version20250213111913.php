<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250213111913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add materials and fit to clothing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE clothing ADD materials TEXT NOT NULL');
        $this->addSql('ALTER TABLE clothing ADD fit VARCHAR(255) NOT NULL');
        $this->addSql('COMMENT ON COLUMN clothing.materials IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE clothing DROP materials');
        $this->addSql('ALTER TABLE clothing DROP fit');
    }
}

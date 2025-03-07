<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250305160016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add relation between clothing and brand';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE clothing ADD brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clothing ADD CONSTRAINT FK_139C38B144F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_139C38B144F5D008 ON clothing (brand_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE clothing DROP CONSTRAINT FK_139C38B144F5D008');
        $this->addSql('DROP INDEX IDX_139C38B144F5D008');
        $this->addSql('ALTER TABLE clothing DROP brand_id');
    }
}

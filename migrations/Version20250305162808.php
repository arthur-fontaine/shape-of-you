<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250305162808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add relation between user and brand';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64944F5D008 ON "user" (brand_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64944F5D008');
        $this->addSql('DROP INDEX IDX_8D93D64944F5D008');
        $this->addSql('ALTER TABLE "user" DROP brand_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125134738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create admin_notification table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE admin_notification (id SERIAL NOT NULL, notification JSONB NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE admin_notification');
    }
}

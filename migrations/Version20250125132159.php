<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125132159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow users to be friends';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_friend (user_source INT NOT NULL, user_target INT NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX IDX_30BCB75C3AD8644E ON user_friend (user_source)');
        $this->addSql('CREATE INDEX IDX_30BCB75C233D34C1 ON user_friend (user_target)');
        $this->addSql('ALTER TABLE user_friend ADD CONSTRAINT FK_30BCB75C3AD8644E FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_friend ADD CONSTRAINT FK_30BCB75C233D34C1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_friend DROP CONSTRAINT FK_30BCB75C3AD8644E');
        $this->addSql('ALTER TABLE user_friend DROP CONSTRAINT FK_30BCB75C233D34C1');
        $this->addSql('DROP TABLE user_friend');
    }
}

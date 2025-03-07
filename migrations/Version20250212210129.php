<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250212210129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates a user_clothing_recommendation table for user-specific clothing recommendations.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_clothing_recommendation (user_id INT NOT NULL, clothing_id INT NOT NULL, PRIMARY KEY(user_id, clothing_id))');
        $this->addSql('CREATE INDEX IDX_709F0536A76ED395 ON user_clothing_recommendation (user_id)');
        $this->addSql('CREATE INDEX IDX_709F05364CFB3290 ON user_clothing_recommendation (clothing_id)');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD CONSTRAINT FK_709F0536A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD CONSTRAINT FK_709F05364CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT FK_709F0536A76ED395');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT FK_709F05364CFB3290');
        $this->addSql('DROP TABLE user_clothing_recommendation');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250213115023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add UserMoodPrompt entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_mood_prompt (id SERIAL NOT NULL, owner_id INT NOT NULL, prompt VARCHAR(1000) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A8FB484A7E3C61F9 ON user_mood_prompt (owner_id)');
        $this->addSql('COMMENT ON COLUMN user_mood_prompt.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_mood_prompt ADD CONSTRAINT FK_A8FB484A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_mood_prompt DROP CONSTRAINT FK_A8FB484A7E3C61F9');
        $this->addSql('DROP TABLE user_mood_prompt');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250213124731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add more information to user_clothing_recommendation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP PROCEDURE calculate_user_recommendations');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT fk_709f0536a76ed395');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT FK_709F05364CFB3290');
        $this->addSql('DROP INDEX idx_709f0536a76ed395');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT user_clothing_recommendation_pkey');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD type VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user_clothing_recommendation RENAME COLUMN user_id TO owner_id');
        $this->addSql('COMMENT ON COLUMN user_clothing_recommendation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD CONSTRAINT FK_709F05367E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD CONSTRAINT FK_709F05364CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_709F05367E3C61F9 ON user_clothing_recommendation (owner_id)');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT FK_709F05367E3C61F9');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP CONSTRAINT fk_709f05364cfb3290');
        $this->addSql('DROP INDEX IDX_709F05367E3C61F9');
        $this->addSql('DROP INDEX user_clothing_recommendation_pkey');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP id');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP type');
        $this->addSql('ALTER TABLE user_clothing_recommendation DROP created_at');
        $this->addSql('ALTER TABLE user_clothing_recommendation RENAME COLUMN owner_id TO user_id');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD CONSTRAINT fk_709f0536a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD CONSTRAINT fk_709f05364cfb3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_709f0536a76ed395 ON user_clothing_recommendation (user_id)');
        $this->addSql('ALTER TABLE user_clothing_recommendation ADD PRIMARY KEY (user_id, clothing_id)');
    }
}

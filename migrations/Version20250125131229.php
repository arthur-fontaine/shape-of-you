<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125131229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create post and post_clothing tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post (id SERIAL NOT NULL, author_id INT NOT NULL, text VARCHAR(280) DEFAULT NULL, media_urls JSON DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('COMMENT ON COLUMN post.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN post.modified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE post_clothing (post_id INT NOT NULL, clothing_id INT NOT NULL, PRIMARY KEY(post_id, clothing_id))');
        $this->addSql('CREATE INDEX IDX_AC71B1104B89032C ON post_clothing (post_id)');
        $this->addSql('CREATE INDEX IDX_AC71B1104CFB3290 ON post_clothing (clothing_id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_clothing ADD CONSTRAINT FK_AC71B1104B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_clothing ADD CONSTRAINT FK_AC71B1104CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post_clothing DROP CONSTRAINT FK_AC71B1104B89032C');
        $this->addSql('ALTER TABLE post_clothing DROP CONSTRAINT FK_AC71B1104CFB3290');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_clothing');
    }
}

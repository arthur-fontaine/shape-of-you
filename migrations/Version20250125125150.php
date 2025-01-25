<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125125150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create clothing_link and clothing_price tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE clothing_link (id SERIAL NOT NULL, clothing_id INT NOT NULL, url TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E65718064CFB3290 ON clothing_link (clothing_id)');
        $this->addSql('CREATE TABLE clothing_price (id SERIAL NOT NULL, link_id INT NOT NULL, price_cts INT NOT NULL, is_on_sale BOOLEAN NOT NULL, registered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E9C1BEE7ADA40271 ON clothing_price (link_id)');
        $this->addSql('COMMENT ON COLUMN clothing_price.registered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE clothing_link ADD CONSTRAINT FK_E65718064CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE clothing_price ADD CONSTRAINT FK_E9C1BEE7ADA40271 FOREIGN KEY (link_id) REFERENCES clothing_link (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE clothing_link DROP CONSTRAINT FK_E65718064CFB3290');
        $this->addSql('ALTER TABLE clothing_price DROP CONSTRAINT FK_E9C1BEE7ADA40271');
        $this->addSql('DROP TABLE clothing_link');
        $this->addSql('DROP TABLE clothing_price');
    }
}

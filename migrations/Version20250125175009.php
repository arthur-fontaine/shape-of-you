<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125175009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add clothing relation to DressingPiece';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dressing_piece ADD clothing_id INT NOT NULL');
        $this->addSql('ALTER TABLE dressing_piece ADD CONSTRAINT FK_50AD104A4CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_50AD104A4CFB3290 ON dressing_piece (clothing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dressing_piece DROP CONSTRAINT FK_50AD104A4CFB3290');
        $this->addSql('DROP INDEX IDX_50AD104A4CFB3290');
        $this->addSql('ALTER TABLE dressing_piece DROP clothing_id');
    }
}

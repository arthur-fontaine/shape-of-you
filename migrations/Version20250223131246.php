<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223131246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add onDelete cascade to dressing_piece';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dressing_piece DROP CONSTRAINT FK_50AD104A4CFB3290');
        $this->addSql('ALTER TABLE dressing_piece ADD CONSTRAINT FK_50AD104A4CFB3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dressing_piece DROP CONSTRAINT fk_50ad104a4cfb3290');
        $this->addSql('ALTER TABLE dressing_piece ADD CONSTRAINT fk_50ad104a4cfb3290 FOREIGN KEY (clothing_id) REFERENCES clothing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}

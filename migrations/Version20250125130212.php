<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125130212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create dressing_piece table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE dressing_piece (id SERIAL NOT NULL, owner_id INT NOT NULL, rate10 SMALLINT NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50AD104A7E3C61F9 ON dressing_piece (owner_id)');
        $this->addSql('ALTER TABLE dressing_piece ADD CONSTRAINT FK_50AD104A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dressing_piece DROP CONSTRAINT FK_50AD104A7E3C61F9');
        $this->addSql('DROP TABLE dressing_piece');
    }
}

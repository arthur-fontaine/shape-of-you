<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250211232034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add hnsw index to user_vector';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX idx_vectors_20250211232034 ON user_vector USING hnsw (vector vector_l2_ops)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_vectors_20250211232034');
    }
}

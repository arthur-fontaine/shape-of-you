<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250211231622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create sync_user_vectors procedure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE PROCEDURE sync_user_vectors()
                LANGUAGE plpgsql
                AS $$
                BEGIN
                    INSERT INTO user_vector (owner_id, vector)
                    SELECT 
                        id,
                        user_vector_array(id)
                    FROM "user"
                    ON CONFLICT (owner_id) 
                    DO UPDATE SET 
                        vector = EXCLUDED.vector,
                        updated_at = CURRENT_TIMESTAMP;

                    RAISE NOTICE \'Vectors synchronized for % users\', (SELECT COUNT(*) FROM user_vector);
                END;
                $$;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP PROCEDURE sync_user_vectors');
    }
}

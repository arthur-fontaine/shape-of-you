<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212210211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE PROCEDURE calculate_user_recommendations()
                LANGUAGE plpgsql
                AS $$
                BEGIN
                    DELETE FROM user_clothing_recommendation;
                    INSERT INTO user_clothing_recommendation (user_id, clothing_id)
                    SELECT id, u.clothing_id
                    FROM "user"
                    CROSS JOIN user_clothing_recommendations(id, 100) u;

                    RAISE NOTICE \'Recommendations calculated for % users\', (SELECT COUNT(*) FROM "user");
                END;
                $$;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP PROCEDURE calculate_user_recommendations');
    }
}

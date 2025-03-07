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
                    CREATE TEMP TABLE user_recommendations_tmp AS
                    SELECT id, u.clothing_id
                    FROM "user"
                    CROSS JOIN user_clothing_recommendations(id, 100) u;

                    RAISE NOTICE \'Recommendations calculated for % users\', (SELECT COUNT(*) FROM "user");

                    DELETE FROM user_clothing_recommendation;
                    
                    INSERT INTO user_clothing_recommendation
                    SELECT * FROM user_recommendations_tmp;

                    DROP TABLE user_recommendations_tmp;

                    RAISE NOTICE \'Recommendations recalculated for % users\', (SELECT COUNT(*) FROM user_clothing_recommendation);
                END;
                $$;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP PROCEDURE calculate_user_recommendations');
    }
}

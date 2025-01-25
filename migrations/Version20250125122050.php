<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125122050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(30) NOT NULL, email VARCHAR(320) NOT NULL, is_enabled BOOLEAN DEFAULT true NOT NULL, weight_kg SMALLINT DEFAULT NULL, size_cm SMALLINT DEFAULT NULL, hip_measurement_cm SMALLINT DEFAULT NULL, chest_measurement_cm SMALLINT DEFAULT NULL, waist_measurement_cm SMALLINT DEFAULT NULL, arm_measurement_cm SMALLINT DEFAULT NULL, leg_measurement_cm SMALLINT DEFAULT NULL, foot_measurement_cm SMALLINT DEFAULT NULL, is_fake BOOLEAN DEFAULT false NOT NULL, has_finished_onboarding BOOLEAN NOT NULL GENERATED ALWAYS AS (size_cm IS NOT NULL AND weight_kg IS NOT NULL AND hip_measurement_cm IS NOT NULL AND chest_measurement_cm IS NOT NULL AND waist_measurement_cm IS NOT NULL AND arm_measurement_cm IS NOT NULL AND leg_measurement_cm IS NOT NULL AND foot_measurement_cm IS NOT NULL) STORED, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP TABLE "user"');
    }
}

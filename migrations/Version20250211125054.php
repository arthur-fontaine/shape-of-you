<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250211125054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user info fields and user vector functions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD gender VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD birthday DATE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD country_iso2 VARCHAR(2) NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".birthday IS \'(DC2Type:date_immutable)\'');
        $this->addSql('
            CREATE FUNCTION user_dressing_value(user_id INT) RETURNS INTEGER AS $$
                DECLARE
                    total INTEGER;
                BEGIN
                    SELECT COALESCE(SUM(cp.price_cts), 0) INTO total
                    FROM "user" u
                    INNER JOIN dressing_piece dp ON dp.owner_id = u.id
                    INNER JOIN clothing_link cl ON cl.clothing_id = dp.clothing_id
                    INNER JOIN clothing_price cp ON cp.link_id = cl.id
                    WHERE u.id = user_id;

                    RETURN total;
                END;
                $$ LANGUAGE plpgsql IMMUTABLE RETURNS NULL ON NULL INPUT;
        ');
        $this->addSql('
            CREATE FUNCTION user_vector_array(user_id INT) RETURNS FLOAT[] AS $$
                DECLARE
                    clothing_vector FLOAT[];
                    user_gender TEXT;
                    gender_one_hot FLOAT[4];
                    user_age FLOAT;
                    user_dressing_value FLOAT;
                    user_country INT;
                    user_weight FLOAT;
                    user_size FLOAT;
                    user_hip_size FLOAT;
                    user_chest_size FLOAT;
                    user_waist_size FLOAT;
                    user_arm_size FLOAT;
                    user_leg_size FLOAT;
                    user_foot_size FLOAT;

                    min_age INT;
                    max_age INT;
                    min_dressing_value FLOAT;
                    max_dressing_value FLOAT;
                    min_weight FLOAT;
                    max_weight FLOAT;
                    min_size FLOAT;
                    max_size FLOAT;
                    min_hip FLOAT;
                    max_hip FLOAT;
                    min_chest FLOAT;
                    max_chest FLOAT;
                    min_waist FLOAT;
                    max_waist FLOAT;
                    min_arm FLOAT;
                    max_arm FLOAT;
                    min_leg FLOAT;
                    max_leg FLOAT;
                    min_foot FLOAT;
                    max_foot FLOAT;
                BEGIN
                    SELECT MIN(EXTRACT(YEAR FROM AGE(birthday))), MAX(EXTRACT(YEAR FROM AGE(birthday))),
                        MIN(user_dressing_value(id)), MAX(user_dressing_value(id)),
                        MIN(weight_kg), MAX(weight_kg), MIN(size_cm), MAX(size_cm),
                        MIN(hip_measurement_cm), MAX(hip_measurement_cm), MIN(chest_measurement_cm), MAX(chest_measurement_cm),
                        MIN(waist_measurement_cm), MAX(waist_measurement_cm), MIN(arm_measurement_cm), MAX(arm_measurement_cm),
                        MIN(leg_measurement_cm), MAX(leg_measurement_cm), MIN(foot_measurement_cm), MAX(foot_measurement_cm)
                    INTO min_age, max_age,
                        min_dressing_value, max_dressing_value,
                        min_weight, max_weight, min_size, max_size,
                        min_hip, max_hip, min_chest, max_chest,
                        min_waist, max_waist, min_arm, max_arm,
                        min_leg, max_leg, min_foot, max_foot
                    FROM "user";

                    SELECT u.gender,
                        (EXTRACT(YEAR FROM AGE(u.birthday)) - min_age) / NULLIF(max_age - min_age, 0),
                        (user_dressing_value(u.id) - min_dressing_value) / NULLIF(max_dressing_value - min_dressing_value, 0),
                        array_position(ARRAY(SELECT DISTINCT u.country_iso2 FROM "user" u)::TEXT[], u.country_iso2::TEXT),
                        (u.weight_kg - min_weight) / NULLIF(max_weight - min_weight, 0),
                        (u.size_cm - min_size) / NULLIF(max_size - min_size, 0),
                        (u.hip_measurement_cm - min_hip) / NULLIF(max_hip - min_hip, 0),
                        (u.chest_measurement_cm - min_chest) / NULLIF(max_chest - min_chest, 0),
                        (u.waist_measurement_cm - min_waist) / NULLIF(max_waist - min_waist, 0),
                        (u.arm_measurement_cm - min_arm) / NULLIF(max_arm - min_arm, 0),
                        (u.leg_measurement_cm - min_leg) / NULLIF(max_leg - min_leg, 0),
                        (u.foot_measurement_cm - min_foot) / NULLIF(max_foot - min_foot, 0)
                    INTO user_gender, user_age, user_dressing_value, user_country, user_weight, user_size, user_hip_size, user_chest_size, user_waist_size, user_arm_size, user_leg_size, user_foot_size
                    FROM "user" u
                    WHERE u.id = user_id;

                    gender_one_hot := ARRAY[0, 0, 0, 0];
                    IF user_gender = \'male\' THEN
                        gender_one_hot[1] := 1;
                    ELSIF user_gender = \'female\' THEN
                        gender_one_hot[2] := 1;
                    ELSIF user_gender = \'non_binary\' THEN
                        gender_one_hot[3] := 1;
                    ELSIF user_gender = \'not_specified\' THEN
                        gender_one_hot[4] := 1;
                    END IF;

                    clothing_vector := gender_one_hot || ARRAY[user_age, user_dressing_value, user_country, user_weight, user_size, user_hip_size, user_chest_size, user_waist_size, user_arm_size, user_leg_size, user_foot_size];
                    RETURN clothing_vector;
                END;
                $$ LANGUAGE plpgsql IMMUTABLE RETURNS NULL ON NULL INPUT;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE "user" DROP gender');
        $this->addSql('ALTER TABLE "user" DROP birthday');
        $this->addSql('ALTER TABLE "user" DROP country_iso2');
        $this->addSql('DROP FUNCTION user_dressing_value(INT)');
        $this->addSql('DROP FUNCTION user_vector_array(INT)');
    }
}

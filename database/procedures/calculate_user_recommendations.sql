CREATE OR REPLACE PROCEDURE calculate_user_hybrid_recommendations()
LANGUAGE plpgsql
AS $$
BEGIN
    CREATE TEMP TABLE user_recommendations_tmp AS
    SELECT u.id AS owner_id, ur.clothing_id AS clothing_id, 'hybrid' AS type
    FROM "user" u
    CROSS JOIN user_clothing_recommendations(u.id, 100) ur;

    RAISE NOTICE 'Recommendations calculated for % users', (SELECT COUNT(*) FROM "user");

    DELETE FROM user_clothing_recommendation WHERE type = 'hybrid';
    
    INSERT INTO user_clothing_recommendation(owner_id, clothing_id, type)
    SELECT * FROM user_recommendations_tmp;

    DROP TABLE user_recommendations_tmp;

    RAISE NOTICE 'Recommendations recalculated for % users', (SELECT COUNT(*) FROM user_clothing_recommendation);
END;
$$;

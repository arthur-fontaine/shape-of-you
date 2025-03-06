CREATE OR REPLACE PROCEDURE calculate_user_hybrid_recommendations()
LANGUAGE plpgsql
AS $$
BEGIN
    CREATE TEMP TABLE user_recommendations_tmp AS
    SELECT id, u.clothing_id, 'hybrid' AS type
    FROM "user"
    CROSS JOIN user_clothing_recommendations(id, 100) u;

    RAISE NOTICE 'Recommendations calculated for % users', (SELECT COUNT(*) FROM "user");

    DELETE FROM user_clothing_recommendation WHERE type = 'hybrid';
    
    INSERT INTO user_clothing_recommendation
    SELECT * FROM user_recommendations_tmp;

    DROP TABLE user_recommendations_tmp;

    RAISE NOTICE 'Recommendations recalculated for % users', (SELECT COUNT(*) FROM user_clothing_recommendation);
END;
$$;

CREATE OR REPLACE FUNCTION user_recommendations(
    user_id INT,
    total_limit INT
) RETURNS TABLE
            (
                clothing_id INT
            )
AS
$$
SELECT
    user_likes_weighted(t.target_user_id, t.distr, total_limit) AS clothing_id
FROM user_recommendation_distributions(user_id) t;
$$ LANGUAGE SQL;

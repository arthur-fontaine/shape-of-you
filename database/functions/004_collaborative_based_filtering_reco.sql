CREATE OR REPLACE FUNCTION collaborative_based_filtering_reco(
    user_id INT,
    total_limit INT
)
    RETURNS TABLE
            (
                clothing_id INT,
                score       FLOAT
            )
AS
$$
SELECT r.clothing_id,
       SUM(r.distr::float) / 100 AS score
FROM (SELECT user_likes_weighted(t.target_user_id, t.distr, total_limit) AS clothing_id,
             t.distr
      FROM user_recommendation_distributions(user_id) t) as r
GROUP BY r.clothing_id
ORDER BY score DESC;
$$ LANGUAGE SQL;

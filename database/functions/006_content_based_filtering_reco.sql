CREATE OR REPLACE FUNCTION  content_based_filtering_reco(
    p_user_id INT,
    total_limit INT
) RETURNS TABLE
            (
                clothing_id INT,
                score FLOAT
            )
AS
$$
SELECT
    p.clothing_id,
    AVG(p.score) AS score
FROM dressing_piece dp
         CROSS JOIN possible_similar_clothing(dp.clothing_id, total_limit) p
WHERE dp.owner_id = p_user_id
GROUP BY p.clothing_id
ORDER BY score DESC
LIMIT total_limit;
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION possible_similar_clothing(
    p_clothing_id INT,
    total_limit INT
) RETURNS TABLE
            (
                clothing_id INT,
                score FLOAT
            )
AS
$$
SELECT
    dp2.clothing_id AS clothing_id,
    AVG(dp2.rate10) / 10 AS score
FROM dressing_piece dp
JOIN dressing_piece dp2 on dp.owner_id = dp2.owner_id AND dp.id != dp2.id
WHERE dp.clothing_id = p_clothing_id
GROUP BY dp2.clothing_id
HAVING AVG(dp2.rate10) >= 7
ORDER BY score DESC
LIMIT total_limit;
$$ LANGUAGE SQL;

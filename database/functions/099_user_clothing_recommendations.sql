CREATE OR REPLACE FUNCTION user_clothing_recommendations(
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
  c.clothing_id,
  SUM(c.score) AS score
FROM (
  SELECT
      clothing_id,
      score
  FROM collaborative_based_filtering_reco(p_user_id, total_limit)
  UNION
  SELECT
      clothing_id,
      score
  FROM content_based_filtering_reco(p_user_id, total_limit)
) c
GROUP BY c.clothing_id
ORDER BY score DESC
LIMIT total_limit;
$$ LANGUAGE SQL;

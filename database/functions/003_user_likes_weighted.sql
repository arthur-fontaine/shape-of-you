CREATE OR REPLACE FUNCTION user_likes_weighted(
    user_id INT,
    distr INT,
    total_limit INT
) RETURNS TABLE
            (
                clothing_id INT
            )
AS
$$
  SELECT clothing_id
  FROM
      user_clothing_ratings(user_id)
  LIMIT total_limit * distr / 100;
$$ LANGUAGE SQL;
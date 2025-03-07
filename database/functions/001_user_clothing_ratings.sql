CREATE OR REPLACE FUNCTION user_clothing_ratings(
    user_id INT
)
    RETURNS TABLE
            (
                clothing_id INT,
                rater_id    INT,
                rating      INT
            )
AS
$$
SELECT pc.clothing_id,
       post_rate.rater_id,
       AVG(post_rate.rate10) AS rating
FROM post_rate
         INNER JOIN public.post p ON p.id = post_rate.post_id
         INNER JOIN public.post_clothing pc ON post_rate.post_id = pc.post_id
WHERE post_rate.rater_id = user_id
GROUP BY pc.clothing_id,
         post_rate.rater_id;
$$ LANGUAGE SQL;

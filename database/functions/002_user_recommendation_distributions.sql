CREATE OR REPLACE FUNCTION user_recommendation_distributions(
    user_id INT
)
    RETURNS TABLE
            (
                target_user_id INT,
                distr   INT
            )
AS
$$
WITH user_distances AS (
    SELECT
        id,
        user_vector_array(id)::vector <-> user_vector_array(user_id)::vector AS distance
    FROM
        "user"
    WHERE
        id != user_id
),
max_distance AS (
    SELECT
        MAX(distance) AS max_dist
    FROM
        user_distances
),
inverted_distances AS (
    SELECT
        ud.id,
        POW(md.max_dist - ud.distance, 2) AS inverted_distance
    FROM
        user_distances ud
    CROSS JOIN
        max_distance md
),
total_inverted_distance AS (
    SELECT
        SUM(inverted_distance) AS total_inverted_dist
    FROM
        inverted_distances
),
percentage_calculation AS (
    SELECT
        id.*,
        tid.total_inverted_dist,
        FLOOR(id.inverted_distance / tid.total_inverted_dist * 100) AS percentage
    FROM
        inverted_distances id
    CROSS JOIN
        total_inverted_distance tid
)
SELECT
    pc.id AS target_user_id,
    pc.percentage AS distr
FROM
    percentage_calculation pc
ORDER BY
    inverted_distance DESC;
$$ LANGUAGE SQL;
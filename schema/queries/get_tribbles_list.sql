SELECT 
  tr_tribbles.tribble_id AS id,
  tr_tribbles.tribble_title AS title,
  tr_tribbles.tribble_text AS `text`,
  tr_users.user_realname AS username,
  tr_users.user_id AS userid,
  tr_images.image_path,
  COUNT(tr_likes.like_id) AS likes,
  COUNT(tr_replies_ref.ref_tribble_id) AS replies,
  tr_tribbles.tribble_timestamp AS ts
FROM
  tr_tribbles
  INNER JOIN tr_images ON (tr_tribbles.tribble_id = tr_images.image_tribble_id)
  INNER JOIN tr_users ON (tr_tribbles.tribble_user_id = tr_users.user_id)
  INNER JOIN tr_likes ON (tr_tribbles.tribble_id = tr_likes.like_tribble_id)
  LEFT OUTER JOIN tr_replies_ref ON (tr_tribbles.tribble_id = tr_replies_ref.ref_tribble_id)
GROUP BY
  tr_tribbles.tribble_id,
  tr_tribbles.tribble_title,
  tr_tribbles.tribble_text,
  tr_users.user_realname,
  tr_users.user_id,
  tr_images.image_path,
  tr_tribbles.tribble_timestamp
ORDER BY
  ts DESC

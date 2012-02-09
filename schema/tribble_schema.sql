SELECT `tr_post`.`post_id` AS post_id, `tr_post`.`post_title` AS post_title, `tr_post`.`post_text` AS post_text, `tr_post`.`post_timestamp` AS post_date, `tr_image`.`image_path` as post_image_path, (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as post_like_count, (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as post_reply_count, `tr_user`.`user_id` AS user_id, `tr_user`.`user_realname` AS user_name, `tr_user`.`user_email` AS user_email
FROM (`tr_post`)
INNER JOIN `tr_image` ON `tr_post`.`post_id` = `tr_image`.`image_post_id`
INNER JOIN `tr_user` ON `tr_post`.`post_user_id` = `tr_user`.`user_id`
WHERE `tr_user`.`user_id` =  '409'
ORDER BY `tr_post`.`post_timestamp` desc
LIMIT 600 
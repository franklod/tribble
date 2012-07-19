<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['cacher'] = array(
	/* ADD LIKE */
	'like_put' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		),
		array(
			'method' => 'posts/likes/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		)
	),
	/* DELETE LIKE */
	'like_delete' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		),
		array(
			'method' => 'posts/likes/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		)
	),
	/* NEW POST */
	'post_put' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/user/id/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 1
		),
		array(
			'method' => 'users/list/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/colors/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/tags/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/users/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		)
	),
	/* NEW REPLY POST */
	'reply_put' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/user/id/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 1
		),
		array(
			'method' => 'users/list/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/colors/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/tags/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		),
		array(
			'method' => 'meta/users/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		)
	),
	/* DELETE POST */
	'delete_delete' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/user/id/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 1
		),
		array(
			'method' => 'users/list/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/colors/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/tags/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		),
		array(
			'method' => 'meta/users/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		)
	),
	/* EDIT POST */
	'edit_post' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		),
		array(
			'method' => 'users/list/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/colors/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/tags/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'meta/users/',
			'paged'  => 0,
			'post_id' => 0,
			'user_id' => 0
		)
	),
	/* ADD COMMENT */
	'comment_put' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		)
	),
	/* DELETE COMMENT */
	'comment_delete' => array(
		array(
			'method' => 'posts/list/new/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/buzzing/',
			'paged'  => 1, 
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/list/loved/',
			'paged'  => 1,
			'post_id' => 0,
			'user_id' => 0
		),
		array(
			'method' => 'posts/detail/id/',
			'paged'  => 0,
			'post_id' => 1,
			'user_id' => 0
		)
	)
);

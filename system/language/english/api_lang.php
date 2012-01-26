<?php

/*
API Text messages:
* All error messages are prefixed with 'E_';
* All data validation failure messages are prefixed with 'INV_';
* All data read/write/delete operations failure messages are prefixed with 'F_';
* All data read/write/delete operations success messages are prefixed with 'S_';
* All data unchanged messages are prefixed with 'NC_';
*/

// INSUFICIENT DATA
$lang['E_NO_EMAIL'] = "User email was not provided.";
$lang['E_NO_PASS'] = "The password was not provided.";
$lang['E_NO_USER_ID'] = "The user id was not provided.";
$lang['E_NO_POST_ID'] = "The post id was not provided.";
$lang['E_NO_NEW_PASS'] = "The new password was not provided.";
$lang['E_NO_OLD_PASS'] = "The old password was not provided.";
$lang['E_NO_SEARCH_TEXT'] = "The search text was not provided.";
$lang['E_NO_SEARCH_TAG'] = "The search tag was not provided.";
$lang['E_NO_POST_TITLE'] = "The post title was not provided.";
$lang['E_NO_POST_TEXT'] = "The post description was not provided.";
$lang['E_NO_POST_IMAGE'] = "The post image was not provided.";
$lang['E_NO_POST_TAGS'] = "The post tags was not provided.";
$lang['E_NO_COMMENT_TEXT'] = "The comment text was not provided.";
$lang['E_NO_COMMENT_ID'] = "The comment id was not provided.";

// DATA VALIDATION
$lang['INV_LOGIN'] = "The email or password you entered are not correct. Please try again.";
$lang['INV_SESSION'] = "Unknown session.";
$lang['INV_OLD_PASSWORD'] = "The old password does not match.";
$lang['INV_USER'] = "Unknown user.";
$lang['INV_POST'] = "Unknown post.";
$lang['INV_POST_LIST_TYPE'] = "An invalid post list type was requested. Supported types are: 'new', 'buzzing' and 'loved'.";
$lang['INV_SEARCH_TEXT'] = "Search text must be at least 3 characters long.";
$lang['INV_COMMENT'] = "Unknown comment.";
$lang['INV_POST_PERMISSIONS'] = "The supplied user cannot delete the requested post.";

// NO CHANGES MADE
$lang['NC_USER_PROFILE'] = "There was nothing to update. No changes were made.";
$lang['NC_SAME_PASS'] = "The passwords were equal. No change was made.";

// OPERATION FAILED (F_)
$lang['F_PASSWORD_CHANGE'] = "We're sorry, but we couldn't change your password. Please try again later.";
$lang['F_USER_PROFILE_UPDATE'] = "We're sorry, but we couldn't update your profile. Please try again later.";
$lang['F_POST_CREATE'] = "We're sorry, but we couldn't create your new post. Please try again later.";
$lang['F_POST_COUNT'] = "Could not get the post count.";
$lang['F_DATA_READ'] = "Could not get data from the cache or database.";
$lang['F_ADD_LIKE'] = "We're sorry, but we couldn't add your like to this post. Please try again later.";
$lang['F_DELETE_LIKE'] = "We're sorry, but we couldn't remove your like from this post. Please try again later.";
$lang['F_ADD_COMMENT'] = "We're sorry, but we couldn't add your comment to this post. Please try again later.";
$lang['F_DELETE_COMMENT'] = "We're sorry, but we couldn't delete your comment to this post. Please try again later.";
$lang['F_DELETE_POST'] = "We're sorry, but we couldn't delete your post. Please try again later.";
$lang['F_API_CONNECT'] = "Dude! WTF have you done? YOU BROKE THE INTERNET!";
$lang['F_USER_LIST'] = "We're sorry but couldn't get the user list. Please try again later.";

// OPERATION SUCCESS (S_)
$lang['S_PASSWORD_CHANGE'] = "Your password was successfully changed.";
$lang['S_USER_PROFILE_UPDATE'] = "Your user profile was updated successfully.";
$lang['S_OLD_PASSWORD_VALIDATION'] = "The old password is valid.";
$lang['S_ADD_LIKE'] = "Your like was successfully added to this post.";
$lang['S_DELETE_LIKE'] = "Your like was successfully removed from this post.";
$lang['S_ADD_COMMENT'] = "Your comment was successfully added to this post.";
$lang['S_DELETE_COMMENT'] = "Your comment was successfully deleted from this post.";
$lang['S_SESSION_KILLED'] = "The session was successfully terminated.";
$lang['S_DELETE_POST'] = "Your post was successfully deleted.";
$lang['S_USER_LIST'] = "The user list was successfully retrieved";

/* End of file api.php */
/* Location: ./system/language/english/api_lang.php */
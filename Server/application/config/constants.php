<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('ERROR',                    0);
define('SUCCESS',                  1);
define('PER_PAGE',              30);

define ('MSG_TYPE_ERROR',          0);
define ('MSG_TYPE_WARNING',        1);
define ('MSG_TYPE_INFO',           2);
define ('MSG_TYPE_SUCCESS',        3);

define('UPLOAD_FOLDER_PATH',         '/uploads/');

define('USER_TYPE_SUPER_ADMIN',        1);
define('USER_TYPE_ADMIN',        2);
define('USER_TYPE_SHOP_OWNER',        3);
define('USER_TYPE_GUEST',        4);

define('UNREAD_MSG', 0);
define('READ_MSG',   1);
define('ALL_MSG',    2);


/* Module Type */
define('MODULE_TYPE_PROFILE',            0);
define('MODULE_TYPE_HOME',               1);
define('MODULE_TYPE_MESSAGE',            2);

define('MODULE_TYPE_TOPIC',              3);
define('MODULE_TYPE_POST',               4);
define('MODULE_TYPE_COMMENT',            5);

/* Admin Panel */
define('MODULE_TYPE_USER',               20);
define('MODULE_TYPE_CATEGORY',           21);
define('MODULE_TYPE_AREA',               22);

define('ENTITY_NAME_USER', 'users');
define('ENTITY_NAME_MEMBER_LIKE', 'member_likes');
define('ENTITY_NAME_RESOURCE', 'resources');
define('ENTITY_NAME_MESSAGE', 'messages');
define('ENTITY_NAME_TOPIC', 'topics');
define('ENTITY_NAME_POST', 'posts');
define('ENTITY_NAME_COMMENT', 'comments');
define('ENTITY_NAME_TOPIC_LIKE', 'topic_likes');
define('ENTITY_NAME_TOPIC_FLAG', 'topic_flags');
define('ENTITY_NAME_POST_LIKE', 'post_likes');
define('ENTITY_NAME_POST_FLAG', 'post_flags');
define('ENTITY_NAME_POST_SHARE', 'post_shares');
define('ENTITY_NAME_COMMENT_LIKE', 'comment_likes');
define('ENTITY_NAME_COMMENT_FLAG', 'comment_flags');

define('MODULE_NAME_HOME', 'home');
define('MODULE_NAME_MESSAGE', 'message');
define('MODULE_NAME_MEMBER', 'member');
define('MODULE_NAME_USER', 'user');
define('MODULE_NAME_TOPIC', 'topic');
define('MODULE_NAME_POST', 'post');
define('MODULE_NAME_COMMENT', 'comment');

define('ERROR_CODE_UNKOWN', 100);
define('ERROR_CODE_NAME_OR_EMAIL_ALREADY_REGISTERED', 101);
define('ERROR_CODE_NAME_ALREADY_REGISTERED', 102);
define('ERROR_CODE_EMAIL_ALREADY_REGISTERED', 103);
define('ERROR_CODE_INVALID_EMAIL_OR_PASSWORD', 104);
define('ERROR_CODE_AUTHENTICATION_REQUIRED', 105);
define('ERROR_CODE_INVALID_NAME_OR_EMAIL', 106);
define('ERROR_CODE_TITLE_ALREADY_REGISTERED', 107);

/* End of file constants.php */
/* Location: ./application/config/constants.php */
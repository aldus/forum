<?php

/**
 *  @module         Forum
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2016 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );

$files_to_register = array(
	'/modules/forum/save_settings.php',
	'/modules/forum/add.php',
	'/modules/forum/addedit_forum.php',
	'/modules/forum/backend.php',
	'/modules/forum/class_forumcache.php',
	'/modules/forum/config.php',
	'/modules/forum/content.php',
	'/modules/forum/edit_post.php',
	'/modules/forum/forum_view.php',
	'/modules/forum/functions.php',
	'/modules/forum/include_search.LIKE-Version.php',
	'/modules/forum/include_search.php',
	'/modules/forum/include_searchform.php',
	'/modules/forum/include_sendmails.php',
	'/modules/forum/insertupdate_forum.php',
	'/modules/forum/install.php',
	'/modules/forum/modify_settings.php',
	'/modules/forum/modify.php',
	'/modules/forum/pagination.php',
	'/modules/forum/post_delete.php',
	'/modules/forum/post_edit.php',
	'/modules/forum/save_post.php',
	'/modules/forum/save_settings.php',
	'/modules/forum/searchtheforum.php',
	'/modules/forum/smilies.php',
	'/modules/forum/thread_create.php',
	'/modules/forum/thread_reply.php',
	'/modules/forum/thread_view.php',
	'/modules/forum/uninstall.php',
	'/modules/forum/upgrade.php',
	'/modules/forum/view.php'
);

$lepton_filemanager->register( $files_to_register );

?>
<?php
/**
 *  @module         Forum
 *  @version        see info.php of this module
 *  @authors        Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke (last)
 *  @copyright      2004-2016 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {   
   include(LEPTON_PATH.'/framework/class.secure.php');
} else {
   $oneback = "../";
   $root = $oneback;
   $level = 1;
   while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
      $root .= $oneback;
      $level += 1;
   }
   if (file_exists($root.'/framework/class.secure.php')) {
      include($root.'/framework/class.secure.php');
   } else {
      trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
   }
}
// end include class.secure.php

$admin_header = false;
// Tells script to update when this page was last updated
$update_when_modified = true;
// Include WB admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}
$admin->print_header();

$sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? '#'.SEC_ANCHOR.$section['section_id'] : '' );


// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

$forumdisplay_perpage = is_numeric($_POST['forumdisplay_perpage']) ? $_POST['forumdisplay_perpage'] : 5;
$showthread_perpage = is_numeric($_POST['showthread_perpage']) ? $_POST['showthread_perpage'] : 5;
$pagenav_sizes = isset($_POST['pagenav_sizes']) ? 1 : 0;
$display_subforums = isset($_POST['display_subforums']) ? 1 : 0;
$display_subforums_forumdisplay = isset($_POST['display_subforums_forumdisplay']) ? 1 : 0;
$forum_use_captcha = isset($_POST['forum_use_captcha']) ? 1 : 0;
$forum_use_smileys = isset($_POST['forum_use_smileys']) ? 1 : 0;
$forum_hide_editor = isset($_POST['forum_hide_editor']) ? 1 : 0;
$admin_group_id = is_numeric($_POST['admin_group_id']) ? $_POST['admin_group_id'] : 1;
$view_forum_search = isset($_POST['view_forum_search']) ? 1 : 0;
$forum_max_search_hits = is_numeric($_POST['forum_max_search_hits']) ? $_POST['forum_max_search_hits'] : 1;
$forum_sendmails_on_new_posts = isset($_POST['forum_sendmails_on_new_posts']) ? 1 : 0;
$forum_admin_info_on_new_posts = $admin->add_slashes($_POST['forum_admin_info_on_new_posts']);
$forum_mail_sender = $admin->add_slashes($_POST['forum_mail_sender']);
$forum_mail_sender_realname = $admin->add_slashes($_POST['forum_mail_sender_realname']);

// Update settings
$sql  = 'UPDATE `'.TABLE_PREFIX.'mod_forum_settings` SET ';
$sql .= '`FORUMDISPLAY_PERPAGE` = \''.$forumdisplay_perpage.'\', ';
$sql .= '`SHOWTHREAD_PERPAGE` = \''.$showthread_perpage.'\', ';
$sql .= '`PAGENAV_SIZES` = \''.$pagenav_sizes.'\', ';
$sql .= '`DISPLAY_SUBFORUMS` = \''.$display_subforums.'\', ';
$sql .= '`DISPLAY_SUBFORUMS_FORUMDISPLAY` = \''.$display_subforums_forumdisplay.'\', ';
$sql .= '`FORUM_USE_CAPTCHA` = \''.$forum_use_captcha.'\', ';
$sql .= '`FORUM_USE_SMILEYS` = \''.$forum_use_smileys.'\', ';
$sql .= '`FORUM_HIDE_EDITOR` = \''.$forum_hide_editor.'\', ';
$sql .= '`ADMIN_GROUP_ID` = \''.$admin_group_id.'\', ';
$sql .= '`VIEW_FORUM_SEARCH` = \''.$view_forum_search.'\', ';
$sql .= '`FORUM_MAX_SEARCH_HITS` = \''.$forum_max_search_hits.'\', ';
$sql .= '`FORUM_SENDMAILS_ON_NEW_POSTS` = \''.$forum_sendmails_on_new_posts.'\', ';
$sql .= '`FORUM_ADMIN_INFO_ON_NEW_POSTS` = \''.$forum_admin_info_on_new_posts.'\', ';
$sql .= '`FORUM_MAIL_SENDER` = \''.$forum_mail_sender.'\', ';
$sql .= '`FORUM_MAIL_SENDER_REALNAME` = \''.$forum_mail_sender_realname.'\' ';
$sql .= 'WHERE `section_id` = '.(int)$section_id;

if($database->query($sql)) {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}
// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}
// Print admin footer
$admin->print_footer();

<?php
/**
 *  @module         Forum
 *  @version        see info.php of this module
 *  @authors        Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke (last)
 *  @copyright      2004-2014 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
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

// Validation:
$post_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_post WHERE postid = '" . intval($_REQUEST['postid']) . "'");
$post = $post_query->fetchRow();

if(!$post)
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}

$thread_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_thread WHERE threadid = '" . intval($post['threadid']) . "'");
$thread = $thread_query->fetchRow();

if(!$thread)
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}

$forum_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_forum WHERE forumid = '" . intval($thread['forumid']) . "'");
$forum = $forum_query->fetchRow();

if(!$forum)
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}

$section_id = $forum['section_id'];
$page_id = $forum['page_id'];
define('SECTION_ID', $section_id);
//define('PAGE_ID', $page_id);

require_once(LEPTON_PATH . '/modules/forum/backend.php');

$query_page = $database->query("
	SELECT * FROM ".TABLE_PREFIX."pages AS p
	INNER JOIN ".TABLE_PREFIX."sections AS s USING(page_id)
	WHERE p.page_id = '$page_id' AND section_id = '$section_id'
");

if(!$query_page->numRows())
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}
else
{
	$page = $query_page->fetchRow();

	define('FORUM_DISPLAY_CONTENT', 'post_edit');
	define('PAGE_CONTENT', LEPTON_PATH . '/modules/forum/content.php');

	require(LEPTON_PATH . '/index.php');
}

?>
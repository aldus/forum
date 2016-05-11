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

/**
 * pr�fen, ob wir auf einen einzelnes posting weiterleiten sollen
 * wenn wir das an dieser stelle pr�fen, m�ssen wir $_pages nicht
 * f�r jeden link ausrechnen. sehr performant :)
 */

if (isset($_GET['goto']))
{ 
	$_post_id = intval($_GET['goto']);

	$sql = "SELECT f.title as forum,
				  p.postid, p.threadid, p.title, p.text, p.page_id, p.section_id

			FROM ".TABLE_PREFIX."mod_forum_post p
				JOIN  ".TABLE_PREFIX."mod_forum_thread t USING(threadid)
				JOIN  ".TABLE_PREFIX."mod_forum_forum f ON (t.forumid = f.forumid)

			WHERE p.postid = ".$_post_id."

			LIMIT 1";

	$res = $database->query($sql);

	if( isset($res) AND $res->numRows() > 0)
	{

	$f = $res->fetchRow( MYSQL_ASSOC );


		//anzahl Datens�tze z�hlen, die vor unserem liegen, brauch wir f�r den Link:
		$sql = 'SELECT COUNT(*) as total FROM '.TABLE_PREFIX.'mod_forum_post WHERE threadid = ' . $f['threadid'] . ' AND postid <= ' . $f['postid'];
		$res2 = $database->query($sql);
		$_count = $res2->fetchRow();
		$section_id = $f['section_id'];
		include_once LEPTON_PATH . '/modules/forum/config.php';
		$_pages = ceil($_count['total'] / SHOWTHREAD_PERPAGE);

	// Location Ziel
	$owd_link = LEPTON_URL.'/modules/forum/thread_view.php?' .
								'sid='.$f['section_id'].
								'&pid='.$f['page_id'].
								'&tid='.$f['threadid'].
								'&page='.$_pages .
								'#post'. $f['postid'];
	//die($owd_link);
	unset($_GET['goto']);

	exit(header('Location: ' . $owd_link));


	}//isset($res)


}
// Validation:
$thread_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_thread WHERE threadid = '" . intval($_REQUEST['tid']) . "'");
$thread = $thread_query->fetchRow( MYSQL_ASSOC );

if(!$thread)
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}

$forum_query = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_forum WHERE forumid = '" . intval($thread['forumid']) . "'");
$forum = $forum_query->fetchRow( MYSQL_ASSOC );

if(!$forum)
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}
else
{
	$section_id = $forum['section_id'];
	$page_id = $forum['page_id'];
	define('SECTION_ID', $section_id);
	// define('PAGE_ID', $page_id);
}

require_once(LEPTON_PATH . '/modules/forum/backend.php');

$query_page = $database->query("
	SELECT * FROM ".TABLE_PREFIX."pages AS p
	INNER JOIN ".TABLE_PREFIX."sections AS s USING(page_id)
	WHERE p.page_id = '$page_id' AND section_id = '$section_id'
");

if(0 == $query_page->numRows())
{
	exit(header('Location: ' . LEPTON_URL . PAGES_DIRECTORY));
}
else
{
	$page = $query_page->fetchRow( MYSQL_ASSOC );

	define('FORUM_DISPLAY_CONTENT', 'view_thread');
	define('PAGE_CONTENT', LEPTON_PATH . '/modules/forum/content.php');

	require(LEPTON_PATH . '/index.php');
}

?>
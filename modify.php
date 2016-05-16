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

define('SKIP_CACHE', 1);
require_once(WB_PATH . '/modules/forum/backend.php');

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader,$twig_modul_namespace;
require( dirname(__FILE__)."/register_parser.php" );

$forums = $database->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_forum` WHERE `section_id` = '".$section_id."' AND `page_id` = '".$page_id."' ORDER BY `displayorder` ASC");

if($database->is_error()) {
	/**
	 *	There has been an error during the last query: 
	 */
	$message = $database->get_error();
	$forum_list = "";
} elseif (0 == $forums->numRows()) {

	/**
	 *	No results found - no forums to list here
	 */
	$message = $MOD_FORUM['TXT_NO_FORUMS_B'];
	$forums_list = "";
	
} else {

	/**
	 *	List the forums
	 *	
	 */
	$message = "";
	
	ob_start();
	$forum_array = array();
	while ($forum = $forums->fetchRow( MYSQL_ASSOC ))
	{
		$forum_array[ $forum['parentid'] ][ $forum['forumid'] ] = $forum;
	}

	// Zuordnung Foren -> Level:
	$arrLevel = getForumLevel();

	print_forums(0);

	$forums_list = "<ul class='forum_list'>".ob_get_clean()."</ul>";
}

/**
 *	Collecting the values/datas for the page
 */
$page_data = array(
	'WB_PATH' => WB_PATH,
	'WB_URL' => WB_URL,
	'section_id'	=> $section_id,
	'page_id'	=> $page_id,
	'MOD_FORUM_TXT_CREATE_FORUM_B'	=> $MOD_FORUM['TXT_CREATE_FORUM_B'],
	'MOD_FORUM_TXT_FORUMS_B'	=> $MOD_FORUM['TXT_FORUMS_B'],
	'TEXT_SETTINGS'	=> $TEXT['SETTINGS'],
	'message'		=> $message,
	'forums_list'	=> $forums_list
);

echo $parser->render(
	$twig_modul_namespace."/modify.lte",
	$page_data
);


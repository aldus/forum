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

		require_once("functions.php");
		$thread_count = $database->query("SELECT COUNT(threadid) AS total FROM " . TABLE_PREFIX . "mod_forum_thread WHERE forumid = '" . intval($forum['forumid']) . "'");
		$thread_count = $thread_count->fetchRow( MYSQL_ASSOC );
		$page = (isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1);
		$pagecount = ceil($thread_count['total'] / FORUMDISPLAY_PERPAGE);
		if (($page * FORUMDISPLAY_PERPAGE) > $thread_count['total']) {
			// Go to last page
			$page = $pagecount;
		}
		$start = ($page * FORUMDISPLAY_PERPAGE) - FORUMDISPLAY_PERPAGE;
		if ($start < 0) {
			$start = 0;
		}
		$threads = $database->query("
			SELECT t.*, u.*, IF(NOT ISNULL(u.user_id), u.display_name, t.username) AS display_name
			FROM " . TABLE_PREFIX . "mod_forum_thread AS t
			LEFT JOIN " . TABLE_PREFIX . "users AS u ON(u.user_id = t.user_id)
			WHERE t.forumid = '" . intval($forum['forumid']) . "'
			ORDER BY t.lastpost DESC
			LIMIT $start, $perpage
		");
		
		// Construct Page Nav
		$page_url = LEPTON_URL.'/modules/forum/forum_view.php?sid='.$section_id.'&amp;pid='.$page_id.'&amp;fid='.$forum['forumid'];
		
		$pagenav = '';
		for($i = 1; $i <= $pagecount; $i++){
			if ($page == $i){
				$pagenav .= '<span '.(fetch_fontsize_from_page($i,$page)).'><strong>['.$i.']</strong></span>&nbsp;';
			} else {
				$pagenav .= '<span '.(fetch_fontsize_from_page($i,$page)).'><a href="'.$page_url.'&page='.$i.'">'.$i.'</a></span>&nbsp;';
			}
		}
?>
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

if (defined('VIEW_FORUM_SEARCH') AND VIEW_FORUM_SEARCH)
{


	$query = $database->query('SELECT link FROM '.TABLE_PREFIX.'pages WHERE page_id = ' . (int) PAGE_ID);


	if($query->numRows() > 0)
	{
		$trail = $query->fetchRow();
		$action = LEPTON_URL . PAGES_DIRECTORY . $trail['link'] . PAGE_EXTENSION;
		$searchVal = "";
		if(isset($_REQUEST['mod_forum_search']))
			$serachVal = htmlentities( htmlspecialchars( stripslashes($_REQUEST['mod_forum_search'])));

		echo '<div class="forum_suche" style="background:silver; border:1px solid #999; padding:2px;">';

		echo '<form action="' .  $action .'" method="get">';
		echo '<input type="hidden" name="search" value="1" />';
		echo '<label for="mod_forum_search">'.$TEXT["SEARCH"].': </label>'.
			 '<input type="text" id="mod_forum_search" name="mod_forum_search" value="'. $searchVal .'" />';
		echo '<input type="submit" value="OK" />';
		echo '</form></div>';

	}//if numRows


}//VIEW_FORUM_SEARCH
?>
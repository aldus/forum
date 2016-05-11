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

require_once(LEPTON_PATH . '/modules/forum/config.php');

if (!defined('SKIP_CACHE')) {
	$forumcache = array(0);
	$cache = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_cache WHERE section_id = '$section_id' AND page_id = '$page_id'");
	while ($cache_entry = $cache->fetchRow()) {
		${$cache_entry['varname']} = unserialize($cache_entry['data']);
	}
	$iforumcache = array();
	if(is_array($forumcache))
		foreach ($forumcache AS $forumid => $f) {
			$iforumcache[$f['parentid']]["$forumid"] = $forumid;
	}
}

require_once(LEPTON_PATH . '/modules/forum/functions.php');
$user_id = (isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : '');
$user = $database->query("SELECT * FROM " . TABLE_PREFIX . "users WHERE user_id = '" . $user_id . "'");

if ($user) {
	$user = $user->fetchRow();
} else {
	$user = null;
}
?>
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

/*
$database->query("DELETE FROM `".TABLE_PREFIX."search` WHERE `name` = 'module' AND `value` = 'guestbook'");
$database->query("DELETE FROM `".TABLE_PREFIX."search` WHERE `extra` = 'guestbook'");
*/

$database->query("DROP TABLE " . TABLE_PREFIX . "mod_forum_forum");
$database->query("DROP TABLE " . TABLE_PREFIX . "mod_forum_cache");
$database->query("DROP TABLE " . TABLE_PREFIX . "mod_forum_post");
$database->query("DROP TABLE " . TABLE_PREFIX . "mod_forum_thread");
$database->query("DROP TABLE " . TABLE_PREFIX . "mod_forum_settings");
?>
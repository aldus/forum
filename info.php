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

$module_directory	= 'forum';
$module_name		= 'Forum';
$module_function	= 'page';
$module_version		= '1.2.0';
$module_platform	= '2.1.0';
$module_license		= 'GNU General Public License';
$module_author		= 'Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke (last)';
$module_home		= 'http://addon.websitebaker.org/pages/en/browse-add-ons.php?type=1';
$module_guid		= '44CF11ED-D38A-4B51-AF80-EE95F7C4C00D';
$module_description	= 'Dieses Modul integriert ein einfaches Forum in ihre Webseite.<br/>';


?>
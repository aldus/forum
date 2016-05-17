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

require(LEPTON_PATH.'/modules/admin.php');		

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


require_once( dirname(__FILE__)."/libs/parsedown/Parsedown.php");
$source = file_get_contents( dirname(__FILE__)."/README.md");
$Parsedown = new Parsedown();
$html = $Parsedown->text($source);


$page_values = array(
	'LEPTON_URL'	=> LEPTON_URL,
	'ADMIN_URL'		=> ADMIN_URL,
	'TEXT_OK'		=> 'Ok',
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'content'		=> $html,
	'leptoken'	=> (isset($_REQUEST['leptoken']) ?  $_REQUEST['leptoken'] : 0)
);

echo $parser->render(
	$twig_modul_namespace."/help.lte",
	$page_values
);


$admin->print_footer();

?>

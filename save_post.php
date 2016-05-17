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

require_once( LEPTON_PATH."/framework/class.validate.request.php" );
$oValidate = new c_validate_request();

$fields = array(
	'section_id'	=> array('type'	=> 'integer+', 'default'	=> NULL),
	'page_id'		=> array('type' => 'integer+', 'default'	=> NULL),
	'forumid'		=> array('type' => 'integer+', 'default'	=> NULL),
	'postid'		=> array('type' => 'integer+', 'default'	=> NULL),
	'class'			=> array('type' => 'string', 'default'	=> NULL),
	'title'			=> array('type' => 'string', 'default'	=> NULL),
	'text'			=> array('type' => 'string', 'default'	=> NULL)
);

foreach($fields as $name => $options) {
	$temp = $oValidate->get_request( $name, $options['default'], $options['type'] );
	if( NULL === $temp) die();
	${$name} = $temp;
}

/**
 *	Some 'parsing'fpr 'title' and 'text'
 */
if(method_exists($database, "escapeString")) {
	$text = $database->escapeString($text);
	$title = $database->escapeString($title);
} else {
	$text = str_replace( array("<","#", "/*"), "", htmlspecialchars($text) ); 
	$title = str_replace( array("<","#", "/*"), "", htmlspecialchars($title) ); 
}

require( LEPTON_PATH.'/modules/admin.php' );

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang ); 

if ($class=="post") {
	$database->query( "UPDATE `".TABLE_PREFIX."mod_forum_post` set `title`='".$title."',`text`='".$text."' WHERE `postid`=".$postid );
} else {
	$database->query( "UPDATE `".TABLE_PREFIX."mod_forum_thread` set `title`='".$title."' WHERE `threadid`=".$postid );
	$database->query( "UPDATE `".TABLE_PREFIX."mod_forum_post` set `title`='".$title."',`text`='".$text."' WHERE `threadid`=".$postid." LIMIT 1" );
}

if($database->is_error()) die($database->get_error());

$admin->print_success(
	$MOD_FORUM["Tread/comment_saved"],
	ADMIN_URL . '/pages/modify.php?page_id=' . $page_id . '&section_id=' . $section_id
);
return 0;
?>
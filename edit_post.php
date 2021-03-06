<?php

/**
 *
 *	@module			Forum
 *	@version		0.5.9
 *	@authors		Julian Schuh, Bernd Michna, "Herr Rilke", Dietrich Roland Pehlke (last)
 *	@license		GNU General Public License
 *	@platform		2.8.x
 *	@requirements	PHP 5.4.x and higher
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
	'class'			=> array('type' => 'string', 'default'	=> NULL)
);

foreach($fields as $name => $options) {
	$temp = $oValidate->get_request( $name, $options['default'], $options['type'] );
	if( NULL === $temp) die();
	${$name} = $temp;
}

require(LEPTON_PATH . '/modules/admin.php');

/**
 *        Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang ); 

$result = ($class=="post")
	? $database->query("SELECT * from `".TABLE_PREFIX."mod_forum_post` where `postid`=".$postid)
	: $database->query("SELECT * from `".TABLE_PREFIX."mod_forum_thread` where `threadid`=".$postid)
	;

if($database->is_error()) die($database->get_error());
$post_data = $result->fetchRow();

$values = array(
	"section_id"	=> $section_id,
	"page_id"		=> $page_id,
	"class"			=> $class,
	"forumid"		=> $forumid,
	"postid"		=> $postid,
	"LEPTON_URL"		=> LEPTON_URL,
	"leptoken"			=> (isset($_REQUEST['leptoken'])) ? $_REQUEST['leptoken'] : "0",
	"title"			=> $post_data['title'],
	"text"			=> ($class=="post") 
		? $post_data['text'] 
		: $database->get_one( "SELECT `text` from `".TABLE_PREFIX."mod_forum_post` where `threadid`=".$postid)
);

$source = file_get_contents( dirname(__FILE__)."/templates/edit_post.lte");
foreach($values as $key=>$value){
	$source = str_replace("{{ ".$key." }}", $value, $source);
}

echo $source;

$admin->print_footer();
?>
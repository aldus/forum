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

$smilies = array (
	"bier" => "(B)",
	"biggrin" => ":D",
	"confused" => ":?",
	"duivel" => "=)",
	"gemeen" => "(8)",
	"hypocrite" => ":0:",
	"joint" => ":joint:",
	"mad" => ":(",
	"muur" => ":wall:",
	"pray" => ":pray:",
	"puke" => ":puke:",
	"rolleyes" => ":rolleyes:",
	"smile" => ":)",
	"tongue" => ":P",
	"wink" => ";)"
);
echo '<div>';
foreach ($smilies as $k => $v) { ?>
	<img class="forum_smilies" src="<?php echo LEPTON_URL; ?>/modules/forum/images/smile/<?php echo $k; ?>.gif" alt="<?php echo $v; ?>" onclick="addsmiley('<?php echo $v; ?>')" border="0" />
<?php } ?>
</div>
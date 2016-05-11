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
require_once(LEPTON_PATH . '/modules/forum/backend.php');

if(!file_exists(LEPTON_PATH . '/modules/forum/languages/' . LANGUAGE . '.php')) {
	require_once(LEPTON_PATH . '/modules/forum/languages/EN.php');
} else {
	require_once(LEPTON_PATH . '/modules/forum/languages/' . LANGUAGE . '.php');
}

?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" width="50%">
		<input type="button" value="<?php echo $MOD_FORUM['TXT_CREATE_FORUM_B']; ?>" onclick="javascript: window.location = '<?php echo LEPTON_URL; ?>/modules/forum/addedit_forum.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="left" width="50%">
		<input type="button" value="<?php echo $TEXT['SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo LEPTON_URL; ?>/modules/forum/modify_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
</tr>
</table>

<h3><?php echo $MOD_FORUM['TXT_FORUMS_B']; ?></h3>

<ul>
<?php

$forums = $database->query("SELECT * FROM " . TABLE_PREFIX . "mod_forum_forum WHERE section_id = '$section_id' AND page_id = '$page_id' ORDER BY displayorder ASC");

if (0 == $forums->numRows())
{
	?>
	<li><?php echo $MOD_FORUM['TXT_NO_FORUMS_B']; ?></li>
	<?php
}
else
{
	$forum_array = array();
	while ($forum = $forums->fetchRow( MYSQL_ASSOC ))
	{
		$forum_array["$forum[parentid]"]["$forum[forumid]"] = $forum;
	}

	// Zuordnung Foren -> Level:
	$arrLevel = getForumLevel();

	print_forums(0);
}
?>
</ul>
<hr />
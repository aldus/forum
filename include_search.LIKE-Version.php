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

include LEPTON_PATH . '/modules/forum/config.php';
include LEPTON_PATH . '/modules/forum/functions.php';

global $database;

//var_dump($_POST);
 $search_string = strip_tags( mysql_real_escape_string($_POST['mod_forum_search']));


if (!empty($search_string))
{
	$sql = "SELECT f.title as forum,
				   p.*

			FROM ".TABLE_PREFIX."mod_forum_post p
				JOIN  ".TABLE_PREFIX."mod_forum_thread t USING(threadid)
				JOIN  ".TABLE_PREFIX."mod_forum_forum f ON (t.forumid = f.forumid)

			WHERE f.title LIKE '%$search_string%' OR
				  p.title LIKE '%$search_string%' OR
				  p.text LIKE '%$search_string%'";

		// WHERE MATCH (p.title,p.text) AGAINST ('$search_string')

	$res = $database->query($sql);
}



if( isset($res) AND $res->numRows() > 0)
{
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_SEARCH_RESULT_F'] . ' ( '. $res->numRows() .' '.$MOD_FORUM['TXT_HITS_F'].' )</h3>';

		while($f = $res->fetchRow())
		{
			$out .= '<p class="mod_last_forum_entries_forum">'. $f['forum'] . '</p>';
			$out .= '<p class="mod_last_forum_entries_title">'. highlightPhrase( $f['title'], $search_string) . '</p>';

				// BB Code entfernen
				$text = strip_bbcode($f['text']);

				//Filter droplets from the page data
				preg_match_all('~\[\[(.*?)\]\]~', $text, $matches);

				foreach ($matches[1] as $match){
					$text = str_replace('[['.$match.']]', '', $text);
				}

				// highlightning
				$text = highlightPhrase( $text, $search_string );
				$text = buildPreview ( $text, $search_string, 120 );

			$out .= '<p class="mod_last_forum_entries_text">'. $text  . '</p>';
			$out .= '<span class="mod_last_forum_entries_link"><a href="'.
				LEPTON_URL.'/modules/forum/thread_view.php?' .
				'sid='.$f['section_id'].
				'&amp;pid='.$f['page_id'].
				'&amp;tid='.$f['threadid'].
				'#post'. $f['postid'].
				'">'.$MOD_FORUM['TXT_READMORE_F'] .'</a></span>';

		}//while

		$out .= '</div>';

}else{

	if ($search_string == '') {
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_NO_SEARCH_STRING_F'] . '</h3>';
		$out .= '</div>';
	}else{
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_NO_HITS_F'] . '</h3>';
		$out .= '</div>';
	}

}//else treffer

?>

<h1>Forum durchsuchen</h1>

<?php include LEPTON_PATH . '/modules/forum/include_searchform.php' ?>

<?php echo $out ?>
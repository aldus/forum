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

include LEPTON_PATH . '/modules/forum/config.php';
include LEPTON_PATH . '/modules/forum/functions.php';

global $database;

//var_dump($_POST);
$search_string = strip_tags( $database->mysql_escape($_GET['mod_forum_search']));
$_search_string = preg_replace("/\b([a-zˆ‰¸ﬂ0-9]{3})\b/i", "$1_x_$1", $search_string);

$arr_search_string = explode(' ', $search_string);
if (is_array($arr_search_string) AND count($arr_search_string) >= 1 )
{
	$strWHERE = implode(' OR ', $arr_search_string);
}
//echo $strWHERE;

if (!empty($search_string))
{
	$sql = "SELECT f.title as forum,
				  p.postid,  p.title, p.text

			FROM ".TABLE_PREFIX."mod_forum_post p
				JOIN  ".TABLE_PREFIX."mod_forum_thread t USING(threadid)
				JOIN  ".TABLE_PREFIX."mod_forum_forum f ON (t.forumid = f.forumid)

			WHERE f.title LIKE '%$search_string%' OR
				   MATCH(p.title, p.search_text) AGAINST('".$database->mysql_escape($_search_string)."' IN BOOLEAN MODE )

			LIMIT " . FORUM_MAX_SEARCH_HITS;


	$res = $database->query($sql);
	//echo $sql;
}

//var_dump($res);
$out = "";
if( isset($res) AND $res->numRows() > 0)
{
		$out .= '<div id="mod_last_forum_entries_heading"><h3>' . $MOD_FORUM['TXT_SEARCH_RESULT_F'] . ' ( '. $res->numRows() .' '.$MOD_FORUM['TXT_HITS_F'].' )</h3>';

		while($f = $res->fetchRow())
		{

			$owd_link = '<a href="'. LEPTON_URL.'/modules/forum/thread_view.php?goto=' . $f['postid']. '">';

			// und einen "weiter"-Link bauen, kann man auch noch brauchen
			//$owd_link_full = $owd_link . $MOD_FORUM['TXT_READMORE_F'] .'</a>';


			// output zusammenschrauben:
			$out .= '<div class="mod_forum_hits">' . $owd_link;
				$out .= $MOD_FORUM['TXT_FORUM_B'] . ':  <span class="mod_forum_hits_forum">'. $f['forum'] . '</span> &raquo; ';
				$out .= $MOD_FORUM['TXT_THEME_F'] . ': <span class="mod_forum_hits_title">'. highlightPhrase( $f['title'], $search_string) . '</span>';
			$out .= '</a></div>';

				// BB Code entfernen
				$text = strip_bbcode($f['text']);

				//Filter droplets from the page data
				preg_match_all('~\[\[(.*?)\]\]~', $text, $matches);

				foreach ($matches[1] as $match){
					$text = str_replace('[['.$match.']]', '', $text);
				}

				// highlightning
				$text = highlightPhrase( $text, $search_string );
				$text2 = buildPreview ( $text, $search_string, 120 );

				empty($text2) ? $text2 = owd_mksubstr($text, 80) : '';

			$out .= '<p class="mod_last_forum_entries_text">'. $text2 . '</p><br/><br/>' ;

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
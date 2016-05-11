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

class ForumCacheBuilder {
	public $db;
	public $section_id;
	public $page_id;
	public $icache;
	public $cache;
	
	private	$version = "1.1.0 - May 2016";
	
	public function __construct(&$database, $section_id, $page_id) {
		$this->db =& $database;
		$this->section_id = $section_id;
		$this->page_id = $page_id;
		$this->fetch_icache();
	}

	public function fetch_icache() {
		$forums = $this->db->query("SELECT * FROM `".TABLE_PREFIX."mod_forum_forum` WHERE `section_id` = '" . $this->section_id . "' AND page_id = '" . $this->page_id . "' ORDER BY displayorder ASC");
		while ($forum = $forums->fetchRow()) {
			foreach ($forum AS $key => $val)	{
				if (is_numeric($key) OR $key == 'lastpostinfo')	{
					unset($forum['key']);
				}
			}
			$this->icache["$forum[parentid]"]["$forum[forumid]"] = $forum;
		}
	}

	public function build_cache($parentid, $readperms = 'both', $writeperms = 'both') {
		if (empty($this->icache["$parentid"])) {
			return;
		}
		foreach ($this->icache["$parentid"] AS $forumid => $forum) {
			switch ($readperms) {
				case 'reg':
					if ($forum['readaccess'] == 'both') {
						$forum['readaccess'] = 'reg';
					} elseif ($forum['readaccess'] == 'unreg') {
						$forum['readaccess'] = 'none';
					}
					break;
				case 'unreg':
					if ($forum['readaccess'] == 'both') {
						$forum['readaccess'] = 'unreg';
					} elseif ($forum['readaccess'] == 'reg') {
						$forum['readaccess'] = 'none';
					}
				break;
			}
			switch ($writeperms) {
				case 'reg':
					if ($forum['writeaccess'] == 'both') {
						$forum['writeaccess'] = 'reg';
					} elseif ($forum['writeaccess'] == 'unreg') {
						$forum['writeaccess'] = 'none';
					}
					break;
				case 'unreg':
					if ($forum['writeaccess'] == 'both') {
						$forum['writeaccess'] = 'unreg';
					} elseif ($forum['writeaccess'] == 'reg') {
						$forum['writeaccess'] = 'none';
					}
					break;
			}
			$this->cache["$forumid"] = $forum;
			$this->build_cache($forumid, $forum['readaccess'], $forum['writeaccess']);
		}
	}

	public function save() {
		if (empty($this->cache)) {
			$this->db->query("REPLACE INTO `".TABLE_PREFIX."mod_forum_cache` (`page_id`, `section_id`, `varname`, `data`) VALUES ('".$this->page_id."', '".$this->section_id."', 'forumcache', '')");
			return;
		}
		$this->db->query("REPLACE INTO `".TABLE_PREFIX."mod_forum_cache` (`page_id`, `section_id`, `varname`, `data`) VALUES ('".$this->page_id."', '".$this->section_id."', 'forumcache', '".$this->db->mysql_escape(serialize($this->cache))."')");
	}
	
	// build new cache after forum delete
	public function update_cache(){
		$this->cache="";
		if(is_array($this->icache))
			foreach($this->icache as $parentId => $itemes)
				 $this->build_cache($parentId);
		$this->save();
	}
	
}
?>
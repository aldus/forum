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

class ForumCacheBuilder {
	public $db;
	public $section_id;
	public $page_id;
	public $icache;
	public $cache;
	
	/**
	 *	Version of this class
	 */
	protected $version = "0.2.0 - beta";
	
	/**
	 *	Constructor of this class
	 *
	 *	@param	object	A valid instance of (any) DB-Connector (pass-by-reference)
	 *	@param	int		A section id if the currend forum
	 *	@param	int		A referending page id
	 *
	 */
	public function __construct(&$database, $section_id, $page_id) {
		$this->db =& $database;
		$this->section_id = $section_id;
		$this->page_id = $page_id;
		$this->fetch_icache();
	}

	public function fetch_icache() {
		$forums = $this->db->query("SELECT * FROM `" . TABLE_PREFIX . "mod_forum_forum` WHERE `section_id` = '" . $this->section_id . "' AND `page_id` = '" . $this->page_id . "' ORDER BY displayorder ASC");
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
		if (empty($this->icache[ $parentid ])) {
			return;
		}
		foreach ($this->icache[ $parentid ] AS $forumid => $forum) {
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
			$this->cache[ $forumid ] = $forum;
			$this->build_cache($forumid, $forum['readaccess'], $forum['writeaccess']);
		}
	}

	public function save() {
		if (empty($this->cache)) {
			$this->db->query("REPLACE INTO `".TABLE_PREFIX."mod_forum_cache` (`page_id`, `section_id`, `varname`, `data`) VALUES ('".$this->page_id."', '".$this->section_id."', 'forumcache', '')");
			if( true === $this->db->is_error()) die( $this->db->get_error() );
			return;
		}
		$this->db->query("REPLACE INTO `".TABLE_PREFIX."mod_forum_cache` (`page_id`, `section_id`, `varname`, `data`) VALUES ('".$this->page_id."', '".$this->section_id."', 'forumcache', '".$this->db->escapeString(serialize($this->cache))."')");
		if( true === $this->db->is_error()) die( $this->db->get_error() );
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
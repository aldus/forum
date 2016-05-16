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

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_forum` (
  forumid int(10) unsigned NOT NULL auto_increment,
  section_id int(10) NOT NULL,
  page_id int(10) NOT NULL,
  title varchar(255) NOT NULL,
  description mediumtext NOT NULL,
  parentid int(10) unsigned NOT NULL default '0',
  displayorder int(10) unsigned NOT NULL default '0',
  lastpostinfo mediumtext NOT NULL,
  readaccess enum('reg','unreg','both') NOT NULL default 'both',
  writeaccess enum('reg','unreg','both') NOT NULL default 'both',
  PRIMARY KEY  (forumid)
);
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_cache` (
  varname varchar(255) NOT NULL,
  section_id int(10) unsigned NOT NULL default '0',
  page_id int(10) unsigned NOT NULL default '0',
  `data` mediumtext NOT NULL,
  UNIQUE KEY `UNIQUE` (varname,section_id,page_id)
) ;
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_post` (
  postid int(10) unsigned NOT NULL auto_increment,
  threadid int(10) unsigned NOT NULL default '0',
  username varchar(250) NOT NULL,
  userid int(10) unsigned NOT NULL default '0',
  title varchar(250) NOT NULL,
  dateline int(10) unsigned NOT NULL default '0',
  `text` mediumtext NOT NULL,
  `search_text` mediumtext NOT NULL,
  page_id int(10) unsigned NOT NULL default '0',
  section_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (postid),
  KEY threadid (threadid),
  FULLTEXT KEY Volltext (title,search_text)
);
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_thread` (
  threadid int(10) unsigned NOT NULL auto_increment,
  user_id int(10) unsigned NOT NULL default '0',
  username varchar(250) NOT NULL,
  title varchar(250) NOT NULL,
  firstpostid int(10) unsigned NOT NULL default '0',
  lastpostid int(10) unsigned NOT NULL default '0',
  lastpost int(10) unsigned NOT NULL default '0',
  forumid int(10) unsigned NOT NULL default '0',
  `open` int(2) NOT NULL,
  replycount int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  sticky tinyint(1) NOT NULL default '0',
  page_id int(10) unsigned NOT NULL default '0',
  section_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (threadid),
  KEY titel (title),
  KEY forumid (forumid)
) ;
");

$database->query("
CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_forum_settings` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `section_id` smallint(6) NOT NULL,
  `FORUMDISPLAY_PERPAGE` tinyint(4) NOT NULL,
  `SHOWTHREAD_PERPAGE` tinyint(4) NOT NULL,
  `PAGENAV_SIZES` tinyint(4) NOT NULL,
  `DISPLAY_SUBFORUMS` tinyint(4) NOT NULL,
  `DISPLAY_SUBFORUMS_FORUMDISPLAY` tinyint(4) NOT NULL,
  `FORUM_USE_CAPTCHA` tinyint(4) NOT NULL,
  `ADMIN_GROUP_ID` smallint(6) NOT NULL,
  `VIEW_FORUM_SEARCH` tinyint(4) NOT NULL,
  `FORUM_MAX_SEARCH_HITS` smallint(6) NOT NULL,
  `FORUM_SENDMAILS_ON_NEW_POSTS` tinyint(4) NOT NULL,
  `FORUM_ADMIN_INFO_ON_NEW_POSTS` varchar(30) COLLATE utf8_unicode_ci,
  `FORUM_MAIL_SENDER` varchar(30) COLLATE utf8_unicode_ci,
  `FORUM_MAIL_SENDER_REALNAME` varchar(30) COLLATE utf8_unicode_ci,
  `FORUM_USE_SMILEYS` tinyint(4) NOT NULL,
  `FORUM_HIDE_EDITOR` tinyint(4) NOT NULL,
  `FORUM_USERS` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ;
");
?>
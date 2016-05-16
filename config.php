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

// Get Settings from DB
$settings = $query_settings->fetchRow( MYSQL_ASSOC );

// EintrŠge, die in der ThemenŸbersicht je Seite angezeigt werden sollen
define('FORUMDISPLAY_PERPAGE', $settings['FORUMDISPLAY_PERPAGE']);

// EintrŠge, die je Seite in einem Thema angezeigt werden sollen
define('SHOWTHREAD_PERPAGE', $settings['SHOWTHREAD_PERPAGE']);

// Legt fest, ob fŸr die Zahlen in der Seitennavigation verschiedene Schriftgršßen verwendet werden sollen
define('PAGENAV_SIZES', $settings['PAGENAV_SIZES']);

// Unterforen auf der Foren-Startseite anzeigen?
define('DISPLAY_SUBFORUMS', $settings['DISPLAY_SUBFORUMS']);

// Unterforen in der ThemenŸbersicht anzeigen?
define('DISPLAY_SUBFORUMS_FORUMDISPLAY', $settings['DISPLAY_SUBFORUMS_FORUMDISPLAY']);

// Sollen fŸr GŠste Captchas zum schreiben verwendet werden?
define('FORUM_USE_CAPTCHA', $settings['FORUM_USE_CAPTCHA']);

// ID der Gruppe der Administratoren (DŸrfen BeitrŠge + Themen Šndern/lšschen)
define('ADMIN_GROUP_ID', $settings['ADMIN_GROUP_ID']);

// Soll das Suchformular angezeigt werden ?
define('VIEW_FORUM_SEARCH', $settings['VIEW_FORUM_SEARCH']);

// max. Ausgabe von x Treffern in der Suchfunktion
define('FORUM_MAX_SEARCH_HITS', $settings['FORUM_MAX_SEARCH_HITS']);

// sollen Mails versendet werden, wenn neue Posts eingehen?
define('FORUM_SENDMAILS_ON_NEW_POSTS', $settings['FORUM_SENDMAILS_ON_NEW_POSTS']);

// Diese Adresse bei neuen BeitrŠgen informieren?'
define('FORUM_ADMIN_INFO_ON_NEW_POSTS', $settings['FORUM_ADMIN_INFO_ON_NEW_POSTS']);

// Sender of notification emails on new posts
define('FORUM_MAIL_SENDER', $settings['FORUM_MAIL_SENDER']);

// Sender's name
define('FORUM_MAIL_SENDER_REALNAME', $settings['FORUM_MAIL_SENDER_REALNAME']);

// use smileys
define('FORUM_USE_SMILEYS', $settings['FORUM_USE_SMILEYS']);

// show hide/unhide button instead of post editor
define('FORUM_HIDE_EDITOR', $settings['FORUM_HIDE_EDITOR']);

// remember user data
define('FORUM_USERS', $settings['FORUM_USERS']);

?>
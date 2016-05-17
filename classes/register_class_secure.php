<?php

/**
 *  @module         Forum
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2016 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../../framework/class.lepton.filemanager.php" );

$files_to_register = array(
	'/modules/forum/classes/class_forumcache.php'
);

$lepton_filemanager->register( $files_to_register );

?>
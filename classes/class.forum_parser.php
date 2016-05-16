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

class forum_parser
{

	public function render($sFilename, &$aData) {
		if(!file_exists($sFilename)) {
			die( "File not found: ".$sFilename );
		}
		$sReturnvalue = file_get_contents($sFilename);
		
		foreach($aData as $key => $value) {
			$sReturnvalue = str_replace("{{ ".$key." }}", $value, $sReturnvalue);
		}
		
		return $sReturnvalue;
	}
}
?>
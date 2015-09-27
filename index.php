<?php
/**
 * The receiver. Code to activate the eocene system
 * Please set the correct path to eocene and config files below
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 *
*/
//$time_start = microtime(true);
	/* Please set the correct path below */
	header('Content-Type: text/html; charset=utf-8');
	
	
	$configFile="config.xml";
	$iniDelimiter=":"; 
	/* No need to change anything below this line */
	ini_set("include_path", "eocene". $iniDelimiter . ini_get("include_path"));
	include_once("FrontController.php");
	$fc =& FrontController::instance($configFile);
	$response=$fc->executeCommand();
	echo $response->getContent();
//$time_end = microtime(true);
//$time = $time_end - $time_start;
//echo $time;
?>



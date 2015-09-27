<?php
/**
 * Class DBConnect
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 * DBConnect class is to connect to a MYSQL database and return a database link.
 * It has methods to process success and failure templates.
 *
 * PUBLIC METHODS
 *	&getLink()			returns a mysql databse link
*/
ini_set('zend.ze1_compatibility_mode', 0);

class DBConnect{
	var $_link;		//private

	function &getLink(){
		$fc=&FrontController::instance();
		if(isset($this->_link)){
			return $this->_link;
		}
		if(!(class_exists("mysqli"))){
			FrontController::instance()->import("lib.Mysqli");
		}
		$this->_link=new mysqli($fc->dbInfo['host'],$fc->dbInfo['userid'],$fc->dbInfo['password'],$fc->dbInfo['database']);
		$this->_link->set_charset("utf8");
		if(!$this->_link){
			$msg="Error: Error connecting to database server: <i>".$this->_link->error."</i>";
			new Error($msg); 
		}
		return $this->_link;
	}
}
?>

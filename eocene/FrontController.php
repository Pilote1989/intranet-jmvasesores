<?php
/**
 * Class FrontController
 * Version 1.1.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modifcation history.
 *
 * Modificaciones importantes para proyecto SAGAC, marzo - julio 2005,
 * por Cristobal Campos y Daniel Cohen, Pontificia Universidad Catolica de Chile.
 *
 * $Id: FrontController.php 189 2005-06-26 07:56:14Z fcatalan $
 *
 * 10/01/2002 Initial creation.
 * 03/17/2003 Added templateEngine member variable, changed rootURL, added appSettings
 * 04/13/2003 pswitek changed _getCommandNameFromRequest() to remove redirect for default command
 * 07/01/2003 added instance method to obtain one and only instance of front controller (suggested by David Elias)
 * It is a front controller. Instantiate it using a config file and run 
 * executeCommand method to handle any action command. It calls the execute method
 * of an appropriate command controller defined in the config file.
 *
 * PUBLIC PROPERTIES
 *	var $paths		All the paths
 *	var $rootURL	root URL of the web. e.g. http://www.myweb.com
 *  var $defaultCommand		A default command if no command found
 *	var $request	a Request object
 *	var $response	a Response object
 *	var $configFile	the config file
 *	var $config		an XmlDoc object of $configFile. This variable should be unset after using it to conserve memory
 *	var $receivers	an associative array of receivers node
 *  var $templates	all the templates for the current command class
 *	var $commandClass	current command class (including any .). Use &getCommandClass() for the name of the class
 *  var $dbInfo			associative array with data base information
 *  var $appSettings	associative array for appSettings node in config file
 *  var $plugs			All the plugs
 *  var $templateEngine The type of template engine (e.g. Smarty)
 *PUBLIC METHODS
 *	FrontController($configFile)
 *  &instance						returns one and only one instance of front controller, e.g. $fc=&FrontController::instance
 *	executeCommand()				execute an action. Action is in fc.php?do=someCommand
 *  &getCommandClass()				returns the command class name only (without .)
 *  import($aString)				performs include_once. $aString is in format aaa.bbb.ccc where aaa is in paths of config.xml and ccc is file ccc.php
 *	vd($varibale,$message=Default)	utility method to print any variable if $this->debugMode=true
 *	setConfig()						sets $this->config which should be unset to preserve memory
 * 	&getLink()						return a database link in DBConnect class
 *  &getDBConnection()				return an instance of DBConnect class
 *  &getPlugContents(&$plugName)	executes plug class (plugName) and returns the content that is used by TemplateEngine
*/
//include bootstrap classes from core library
include_once("XmlDoc.php");
include_once("Error.php");
include_once("DBConnect.php");
include_once("Request.php");
include_once("Response.php");

class FrontController {	
	static $frontController; //instance()->__construct()
	var $rootURL;
	var $defaultCommand;
	var $defaultLayout;
	var $paths;
	var $debugMode;	
	var $configFile;
	var $config;
	var $receiver;
	var $templates;
	var $commandClass;
	var $dbInfo;
	var $appSettings;
	var $plugs;
	private $_import=array();
	private $_dbConnection;

	/******************************************************************************
	PUBLIC METHODS
	*******************************************************************************/
	//Constructor takes the config file (with path)
	private function __construct($configFile, $rootURL,$receiver){
		$this->configFile=$configFile;
		$this->rootURL=$rootURL; //http://www.domain.com/folder/to/site/
		$this->receiver=$receiver; //index.php
		//inicializar miembros de FrontController y liberar informacion de config.xml
		$this->setMembers();
		unset($this->config);
		//incluir librerias de importacion automatica
		$this->includeAutoLoadLib();
	}
	
	//Returns an instance of front controller.
	function &instance($configFile=NULL, $rootURL=NULL){ 
		if(!is_a(self::$frontController,"FrontController")){ 
			$receiver=basename($_SERVER['SCRIPT_FILENAME']);
			$URL="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
			$URLbaseSize = strpos($URL, $receiver);
			$rootURL = substr($URL, 0, $URLbaseSize);
			self::$frontController = new FrontController($configFile, $rootURL,$receiver);
        	}
        	return self::$frontController;
	}
	
	//Exceute an action. Action is in the form fc.php?do=someAction
	/****************************************************
	*All the action commands are in the format fc.php?do=someAction
	*A class SomeAction should exists and someAction should be
	*associated with this class in the config file.  Class SomeAction 
	*is the command controller that can serve one or more commands.
	*****************************************************/
	
	// -> Modificaciones a la ejecucion de commands
	
	// -> Un command ya no es alamacenado dentro de la variable response->content
	//    al momento de hacer el processTemplate. Ahora, todo command y plug es 
	//    instanciado por una unica funcion que es getCommand, y el
	//    almacenamiento del contenido para la la impresion final es hecho dentro
	//    del mismo command. Con esto, tambien
	//    commands y plugs tienen la misma logica, que es que deben incluir un
	//    processTemplate que recibe como parametro el nombre de archivo del 
	//    template. 
	// -> Tambien fueron eliminados los parametros de configuracion de los 
	//    commands y plugs, del config.xml
	
	function executeCommand($commandName=NULL){//NULL si command viene desde URL (incluye defaultCommand)
		ob_start();
		//obtencion del command adecuado
		$command=$this->getCommand($commandName);
		//ejecucion del command
		//$usuario=$command->getUsuario();	
		//$command->addVar("nombreUsuario", $usuario->getNombres());
		$response=$command->getResponse();
		// se imprime el codigo de response
		if(!($this->debugMode)){
			ob_end_clean();
		}
		return $response;
	}
	//importacion automatica de librerias
	function includeAutoLoadLib(){
		if(!isset($this->paths["autoLoad"])){
			return false;
		}
		$dir = $this->paths["autoLoad"];
		if (is_dir($dir)) {
			self::includePhpFilesInFolder($dir,"autoLoad");
		}
	}
	
	function includePhpFilesInFolder($folder,$pathKey,$recursive=TRUE){
		if ($dh = opendir($folder)) {
			while (($file = readdir($dh)) !== false) {
				if($recursive && is_dir($folder."/".$file) && $file!="." && $file!=".."){
					self::includePhpFilesInFolder($folder."/".$file,$pathKey.".".$file);
//					$subDir=opendir($folder."/".$file);
				}
				if(substr($file,-4)==".php"){
					$this->import($pathKey.".".substr($file,0,strlen($file)-4));
				}
			}
			closedir($dh);
		}
	}
	//Importacion y construccion de command especifico
	public function getCommand($commandName){//$commandName="category.action?a=1&b=2"
		$this->import("BaseCommand");
		if(!$commandName){
			//si el command no viene como parametro, se obtiene del request
			$commandName=$this->_getCommandNameFromRequest();
		}
		$pieces=explode("?",$commandName);
		$commandName=$pieces[0];
		$getQuery="do=".$commandName;
		if(isset($pieces[1])){	
			$getQuery.="&".$pieces[1];
		}
		if(!($this->import("commands.".$commandName))){
			//no se pudo importar el command
			$msg ="Error: Command <i>".$commandName."</i>";
			$msg.=" not found in <i>".$this->paths["commands"]."</i>";
			new Error($msg);
		}
		$className=self::getCommandClassName($commandName);
		return new $className($getQuery);
	}

	//An utility function to print a variable. $this->debugMode should be true;
	function vd($variable,$label="Unnamed var"){
		if(!$this->debugMode){
			return;
		}
		echo $label.":<pre>"; 
		if (!is_array($variable) && !is_object($variable)){ 
			$variable=htmlspecialchars($variable); 
		} 
		print_r($variable); 
		if ( is_array($variable) || is_object($variable)){ 
			reset($variable); 
		}
		echo "</pre><hr>"; 
	}


	public function import($aString,$alert=true){
		if(isset($this->_import[$aString])){
			return true;
		}
		$theArray=explode(".",$aString);
		$theSize=count($theArray);
		if($theSize==1){
			$theFile=$aString.".php";
			include_once($theFile);
			$this->_import[$aString]=1;
			return true;
		}
		$pathAlias=$theArray[0];
		if(!isset($this->paths[$pathAlias])){
			$msg="Error: Alias for path <i>".$pathAlias."</i> not found in config file";
			new Error($msg);
		}
		unset($theArray[0]);
		$theFile=$this->paths[$pathAlias].implode("/",$theArray).".php";
		if(file_exists($theFile)){
			include_once($theFile);
			$this->_import[$aString]=1;
			return true;
		}
		if(!$alert){
			return false;
		}
		$msg="Error: File <i>".$theFile."</i> not found.";
		new Error($msg);
	}

	function &getLink(){
		if(isset($this->_dbConnection)){
			return $this->_dbConnection->getLink();
		}
		$this->_dbConnection=new DBConnect();
		return $this->_dbConnection->getLink();	
	}

	function getCommandClassName($string){
		$explodedCC=explode(".",$string);
		return $explodedCC[count($explodedCC)-1];	
	}

	function _getCommandNameFromRequest(){
		if(!isset($_GET['do'])){
			if(isset($_POST['do'])){
				$_GET['do']=$_POST['do'];
			}else{
				//acceso a receiver, sin parámetros
				$urlParts=explode("?",$this->defaultCommand);
				$_GET['do']=$urlParts[0];
				if(isset($urlParts[1])){
					$definitions=explode("&",$urlParts[1]);
					foreach($definitions as $def){
						if(empty($def)){
							continue;
						}
						list($varName,$varValue)=explode("=",$def);
						$_GET[trim($varName)]=trim($varValue);
					}
				}
			}
		}
		//acceso a receiver, con $_GET definido
		$commandName=$_GET['do'];
		$params=array();
		foreach($_GET as $k=>$v){
			if($k!="do"){
				$params[].=$k."=".$v;
			}
		}
		if(sizeof($params)){
			$commandName.="?".implode("&",$params);
		}
		if(empty($commandName)){
			$msg="Error: Empty command is invalid";
			new Error($msg);
		}
		return $commandName;
	}
	//movido desde Response
	function redirect($location) {
		ob_end_clean();
		header("Location: ".$location);
		exit();
	}
	/***********************************************************
	PRIVATE METHODS
	************************************************************/
	//Initializes the instance variables
	private function setMembers(){
		//set $this->config
		$this->setConfig();
		//set debugMode
		$this->setDebugMode();
		//$this->defaultCommand
		$this->defaultCommand=$this->config->root->getChildNodeData("defaultCommand");
		if(empty($this->defaultCommand)){
			$msg="Error: <i>defaultCommand</i> node not found in config file";
			new Error($msg);
		}
		//$this->defaultLayout
		$this->defaultLayout=$this->config->root->getChildNodeData("defaultLayout");
		if(empty($this->defaultLayout)){
			$msg="Error: <i>defaultLayout</i> node not found in config file";
			new Error($msg);
		}
		//Set $this->paths[]
		$this->_setPaths();
		//set $this->dbinfo
		$this->_setDBInfo();
		//set $this->appSettings
		$this->_appSettings();
	}
	//sets $this-config. Should be unset after use to preserve memory
	function setConfig(){
		$config= new XmlDoc();
		$config->parse($this->configFile);
		if($config->isError()){
			$msg='Error: Config file error: <i>'.$config->error.'</i>';
			new Error($msg);
		}
		$this->config=&$config;
	}
	//sets $this->debugMode=[true|false]
	function setDebugMode(){
		$this->debugMode=$this->config->root->getChildNodeData("debugMode");
		if(empty($this->debugMode)){
			$msg="Error: <i>eocene/debugMode</i> node not found in config file";
			new Error($msg);
		}
		if($this->debugMode=="true"){
			$this->debugMode=true;
		}elseif($this->debugMode=="false"){
			$this->debugMode=false;
		}else{
			$msg="Wrong value in <i>debugMode</i> section of config file [true|false].";
			new Error($msg);
		}
	}
	//function convert children of a node to array
	//sets $this->paths
	function _setPaths(){
		$this->paths=array();
		$node=&$this->config->root->getChild("paths");
		if($node == NULL){
			$msg="Error: paths node not found in config file";
			new Error($msg);
		}
		$n=$node->numChildren();
		for($i=0;$i<$n;$i++){
			$child=&$node->children[$i];
			$path=realpath($child->charData);
			if( empty( $path ) ){
				$msg="Error: path './".$child->charData."' not found.";
				new Error($msg);
			}
			$this->paths[$child->name]=$path."/";
		}
	}
	function _setDBInfo(){
		$this->db=array();
		$dbinfoNode=&$this->config->root->getChild("dbInfo");
		if($dbinfoNode == NULL) return;		
		$n=$dbinfoNode->numChildren();
		for($i=0;$i<$n;$i++){
			$adbinfo=&$dbinfoNode->children[$i];
			$this->dbInfo[$adbinfo->name]=$adbinfo->charData;
		}
	}
	
	function _appSettings(){
		$this->appSettings=array();
		$appSeetingNode=&$this->config->root->getChild("appSettings");
		if($appSeetingNode == NULL) return;
		$n=$appSeetingNode->numChildren();
		for($i=0;$i<$n;$i++){
			$aSetting=&$appSeetingNode->children[$i];
			$this->appSettings[$aSetting->name]=$aSetting->charData;
		}
	}
}
?>

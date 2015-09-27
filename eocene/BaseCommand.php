<?php
/**
 * Class BaseCommand
 * Version 1.1.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 * 03/17/2003 Modified to adapt SmartWrapper
 * BaseCommand is the super class of all command classes.
 * It has methods to facilitate easy template handling.
 *
 * PUBLIC PROPERTIES
 *	var $templateEngine							An instance of the template engine
 * PUBLIC METHODS
 *	initialize()								create the template and add vars: t_receiver, t_rootURL
 *	initFormVars(&$formVariables)				replaces all form variables (values obtained from $this->request) in the template
 *	addVar($tokenName,&$theValue)				add a value of the replacement variable ($tokenName) in the template
 *	addLoop($loopName,$tokenName,&$theValue)	add value (an array of array) for loop processing
 *	addSelectLoop($loopName,$tokenName,$selectName,&$theValue)	A loop for html <option> construct. 
 *	addEmptyVar($tokenName)						does an addVar with an empty string
 *	addBlock($blockName)						adds a block to the template. By default all blocks are removed
 *  processTemplate($templateAlias)				processed the template aliased as $templateAlias
 *	processSuccess()							process the success template
 *	processFailure()							process the failure template
 *	&getContents($templateName,$includePath)	get the processed template. Subclasses should overwrite if there is no template
*/
include_once('Smarty.class.php');

class BaseCommand {
	var $request;
	var $templateEngine;
	var $response;
	var $fc;//link a FrontController::instance();

    // Adaptación de Smarty
    var $smartyEngine;

	function __construct($getQuery){
		$this->fc=&FrontController::instance();
		$this->request=new Request($getQuery);
		$this->initializeEoceneTemplateEngine();

        $this->smartyEngine = new Smarty();
        $this->smartyEngine->template_dir = "templates";
        $this->smartyEngine->compile_dir = "templates_c";
	}
	
	function initFormVars(&$formVariables){
		$size=count($formVariables);
		for($i=0;$i<$size;$i++){
			$v=$formVariables[$i];
			if(!isset($this->request->$v))
				$this->request->$v='';
			$this->addVar($v,$this->request->$v);
		}
	}
	
	function addVar($tokenName,$theValue){
		$this->templateEngine->setVar($tokenName,$theValue);
	}
	
	function addEmptyVar($tokenName){
		$emptyString='';
		$this->addVar($tokenName,$emptyString);
	}
	
	function addBlock($blockName){
		$this->templateEngine->setBlock($blockName);
	}
	
	function addLoop($loopName,$loopValues){
		$this->templateEngine->setLoop($loopName,$loopValues);
	}
	function addLayout($layoutName){
		$this->templateEngine->addLayout($layoutName);
	}
	
	function addLoopUsingDBResults($loopName,&$dbResultArray){
		$loopValues=array();
		foreach($dbResultArray as $i=>$row){
			foreach($row as $dbColumnName=>$value){
				$loopValues[$i][$dbColumnName]=$value;
			}
		}
		$this->addLoop($loopName,$loopValues);
	}

    function assign($name, $value) {
        $this->smartyEngine->assign($name, $value); 
    }

    function display($template) {
        $this->templateEngine->fileContents = $this->smartyEngine->fetch($template);
		$this->templateEngine->processLayouts();
        $this->templateEngine->processVariables();
        $this->templateEngine->processPlugs();
    }
	
	function processTemplate($templateName){
		$this->templateEngine->process($templateName);
	}
	function getResponse(){
		$this->response=new Response();
		$this->execute();//en command puede modificarse objeto response
        $this->response->write($this->templateEngine->fileContents);
		return $this->response;
	}
	
	/******************************************************************************
	PRIVATE METHODS AND PROPERTIES
	*******************************************************************************/
	function execute(){
		$msg ="Command error: Must overwrite <i>".get_class($this)."::execute()</i> method.";
		new Error($msg);
	}

	function initializeEoceneTemplateEngine(){
		$this->fc->import('TemplateEngine');
		$this->templateEngine=new TemplateEngine();
		$fcVars["receiver"]=$this->fc->receiver;
		$fcVars["rootURL"]=$this->fc->rootURL;
		$this->addVar("fc",$fcVars);
	}
}
?>

<?
/**
 * Class TemplateEngine
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcation to the line above
 * Please include any modification history
 * 10/01/2002 Initial creation
 * TemplateEngine class is used to parse the template
 *
 * PUBLIC PROPERTIES
 *	var $fileName		Name of the file including the path
 *  var $fileContents	Entire contents of the file
 *  var $variables		array of all the variables (anything between {} in the template)
 *  var $loops			name of all the loops
 *  var $blockNames		name of all the blocks
 *  var $unresolvedVars	variables that were found in template but not in $variables
 * PUBLIC METHODS
 *	setVar(&$varName,&$varValue)		sets variables
 *	setLoop(&$loopName, &$loopData)		sets loops 
 *	setBlock(&$aName)					sets blocks 
 *	process(&$fileName,&$templateRoot)	process the template. Should be called after setting var, loops, and blocks
 * 										The processed template can be extracted from $fileContents 
*/
class TemplateEngine{
	var $fileContents;
	var $layouts = array();
	var $variables = array();
	var $loops = array();
	var $blockNames = array();

	//Use it to set variables
	function setVar(&$varName, &$varValue){
		if (is_array($varValue)) {
			foreach($varValue as $key=>$value){
				$newName=$varName . '.' . $key;
				$this->setVar($newName, $value);
			}
		}else{
			$this->variables[trim($varName)] = $varValue;
		}
	}

	//Use it to set loops
	function setLoop(&$loopName, &$loopData){
		if (is_array($loopData)){
			$this->loops[$loopName] = $loopData;
		}
	}

	//Use it to set blocks
	function setBlock(&$aName){
		$this->blockNames[trim($aName)]=1;
	}
	
	function addLayout($layoutName){
		if(!empty($layoutName)){
			$fc=&FrontController::instance();
			$this->layouts[]=$fc->paths["layouts"].$this->formatTemplateName($layoutName);
		}
	}
	function formatTemplateName($templateName){
		if(substr($templateName,-5)!=".html"){
			if(substr($templateName,-4)==".htm"){
				$templateName=substr($templateName,0,-4);
			}
			if(substr($templateName,-1)=="/"){
				$templateName=substr($templateName,0,-1);
			}
			$templateName.=".html";
		}
		if(substr($templateName,0,1)=="/"){
			$templateName=substr($templateName,1);
		}
		return $templateName;
	}
	//Call this method to process template after everything is set
	//$fileName has full path, $templateRoot is used to resolve includes
	function process($templateName){
//		header('Content-type: text/html; charset=utf-8');
		$fc=&FrontController::instance();
		$mainTemplate=$fc->paths["templates"].$this->formatTemplateName($templateName);
		$this->fileContents=$this->getFileContents($mainTemplate);
		//hacer crecer el template
		$this->processLayouts();
		$this->processBlocks();
		$this->processInclude();
		//rellenar el template	
		$this->processBlocks();
		$this->processLoops();
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		//Salida: Viernes 24 de Febrero del 2012
		$this->variables["fecha"] = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
		$this->variables["nombreSistema"] = $fc->appSettings["nombreSistema"];
		$this->variables["nombreCompania"] = $fc->appSettings["nombreCompania"];
		$this->variables["siglasCompania"] = $fc->appSettings["siglasCompania"];
		$this->processVariables();
		//recortar el template
		$this->processBlocks();
		//ejecutar plugs
		$this->processPlugs();
		//ejecutar plugs con variable extra
		$this->processPlugsExtra();
	}
	/******************************************************************************
	PRIVATE METHODS AND PROPERTIES
	*******************************************************************************/
	function processLayouts(){
		$this->layouts=array_reverse($this->layouts);
		foreach($this->layouts as $layoutFileName){
			$layoutContents=$this->getFileContents($layoutFileName);
			$this->fileContents=str_replace("<MainCommand/>",$this->fileContents,$layoutContents);
		}
	}
	function getFileContents($templateName){
		if(($content=@file_get_contents($templateName))!==false){
			return $content;
		}

		$msg="TemplateEngine: file '<i>".$templateName."</i>' does not exist.";
		new Error($msg);
	}

	function processInclude(){
		$fileContents = &$this->fileContents;
		while( ($position = strpos($fileContents, '<include template="'))!==false ){
			$position += 19;
			$endPosition = strpos($fileContents, '"/>', $position);
			$includeFileName = substr($fileContents, $position, $endPosition-$position);
			$replaceThis = '<include template="'.$includeFileName.'"/>';
			$includeContents = &$this->getFileContents("templates/".$includeFileName);
			$fileContents = str_replace($replaceThis, $includeContents, $fileContents);
		}
	}
	
	function processPlugs(){
		$fileContents = &$this->fileContents;
		while(is_long($position = strpos($fileContents, '<plug command="'))){
			$position += 15;
			$endPosition = strpos($fileContents, '"/>', $position);
			$plugAction = substr($fileContents, $position, $endPosition-$position);
			$replaceThis = '<plug command="'.$plugAction.'"/>';
			$urlParts=explode("?",$plugAction);
			$fc=&FrontController::instance();
			$response=$fc->executeCommand($plugAction);
			$plugContents=$response->getContent();
			$fileContents = str_replace($replaceThis, $plugContents, $fileContents);
		}
	}
	
	function processPlugsExtra(){
		$fileContents = &$this->fileContents;
		while(is_long($position = strpos($fileContents, '<plugextra command="'))){
			$position += 20;
			$endPosition = strpos($fileContents, '"/>', $position);
			$plugAction = substr($fileContents, $position, $endPosition-$position);
			$replaceThis = '<plugextra command="'.$plugAction.'"/>';
			$urlParts=explode("?",$plugAction);
			$fc=&FrontController::instance();
			if($this->variables["doFalso"] == ""){
				$response=$fc->executeCommand($plugAction);
			}else{
				$response=$fc->executeCommand($plugAction . "?doFalso=" . $this->variables["doFalso"]);
			}
			$plugContents=$response->getContent();
			$fileContents = str_replace($replaceThis, $plugContents, $fileContents);
		}
	}
	
	function processLoops(){
		while (list($loopName, $loopArgs) = each($this->loops) ){
			$this->fileContents=$this->processMultiLoop($loopName,$loopArgs,$this->fileContents);
		}
	}
	function processMultiLoop($loopName,$loopData,$fileContents){
		$loopStartPrefix='<loop start="';
		$loopEndPrefix='<loop end="';
		$loopSuffix='"/>';
		$startTag = $loopStartPrefix.$loopName.$loopSuffix;
		$endTag = $loopEndPrefix.$loopName.$loopSuffix;
		while(($startTagPos = strpos($fileContents, $startTag))!==false){
			$startContentPos = $startTagPos + strlen($startTag);
			$endTagPos = strpos($fileContents, $endTag);
			if($endTagPos === false){
				//error de escritura de loop
				$msg="Cannot find end tag for loop name ".$loopName;
				new Error($msg);
			}
			$loopContents = substr($fileContents, $startContentPos, $endTagPos-$startContentPos);
			$originalLoopContents = $loopContents;
			if($loopContents == ''){
				continue;
			}
			$parsedLoop=array();
			if(count($loopData) > 0){
				foreach($loopData as $i=>$data){
					
					$parsedLoop[$i]=$loopContents;
					foreach($data as $key=>$value){
						//print_r($data);
						
						if(!(is_array($value))){
							//echo '<div>_1_'.$parsedLoop[$i].'</div>';
							$parsedLoop[$i]=str_replace("{".$loopName.".".$key."}",$value,$parsedLoop[$i]);
							//echo '<div>_2_</div>';
						}else{
							$parsedLoop[$i]=$this->processMultiLoop($loopName.".".$key,$value,$parsedLoop[$i]);
						}
					}
					//borrar loops vacios internos
					$startStartSubTag = $loopStartPrefix.$loopName.'.';
					while(($startStartSubTagPos = strpos($parsedLoop[$i], $startStartSubTag))!==false){
						$startSubLoopNamePos = $startStartSubTagPos+strlen($loopStartPrefix);
						$endSubLoopNamePos = strpos($parsedLoop[$i], $loopSuffix,$startSubLoopNamePos);
						$subLoopName=substr($parsedLoop[$i],$startSubLoopNamePos,$endSubLoopNamePos-$startSubLoopNamePos);
						$endSubTag=$loopEndPrefix.$subLoopName.$loopSuffix;
						$startEndSubTagPos=strpos($parsedLoop[$i],$endSubTag);
						if($startEndSubTagPos === false){
							//error de escritura de loop
							$msg="Cannot find end tag for loop name ".$subLoopName;
							new Error($msg);
						}
						$endEndSubTagPos=$startEndSubTagPos+strlen($endSubTag);
						$parsedLoop[$i]=substr_replace($parsedLoop[$i],"",$startStartSubTagPos,$endEndSubTagPos-$startStartSubTagPos);
					}
				}
			}else{
				// Si no tiene contenido el Loop
				$fileContents =str_replace($startTag.$originalLoopContents.$endTag,"",$fileContents);				
			}
			$fileContents =str_replace($startTag.$originalLoopContents.$endTag,implode("",$parsedLoop),$fileContents);
		}
		return $fileContents;
	}
	
	/* Version de dcohenp: Mucho mas simple y no se marea con las llaves */
	function processVariables(){
		if(empty($this->variables)){
			return;
		}
		$replacetoks = array();
		foreach($this->variables as $varname => $varval){
			$replacetoks[] = "{".$varname."}";
		}
		$this->fileContents = str_replace($replacetoks, $this->variables, $this->fileContents);
	}
	/* Version de ccampos: Mas simple y no restringe largo de nombre de blocks*/
	function processBlocks(){
		$fileContents=&$this->fileContents;
		$blockStartPrefix='<block start="';
		$blockEndPrefix='<block end="';
		$blockSuffix='"/>';
		$pos=0;
		while(($startBlockStart=strpos($fileContents,$blockStartPrefix,$pos))!==false){
			$startBlockName=$startBlockStart+strlen($blockStartPrefix);
			$endBlockName=strpos($fileContents,$blockSuffix,$startBlockName);
			$blockNameLength=$endBlockName-$startBlockName;
			$blockName=substr($fileContents,$startBlockName,$blockNameLength);
			$blockStartTag=$blockStartPrefix.$blockName.$blockSuffix;
			$endBlockStart=$startBlockStart+strlen($blockStartTag);
			$blockEndTag=$blockEndPrefix.$blockName.$blockSuffix;
			$startBlockEnd=strpos($fileContents,$blockEndTag,$startBlockStart+strlen($blockStartTag));
			if($startBlockEnd===false){
				$msg="Cannot find end tag for block name ".$blockName;
				new Error($msg);
			}
			$endBlockEnd=$startBlockEnd+strlen($blockEndTag);
			$replace="";
			if(array_key_exists($blockName, $this->blockNames)){
				$replace=substr($fileContents,$endBlockStart,$startBlockEnd-$endBlockStart);
			}
			$pos=$startBlockStart;
			$fileContents=substr_replace($fileContents,$replace,$startBlockStart,$endBlockEnd-$startBlockStart);
		}
	}
}
?>
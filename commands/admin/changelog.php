<?php
class changelog extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		
		/*
		ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
		*/
		
		//IMPORTANTE!!!!
		//ejecutar 
		//git log --no-merges --pretty=format:'%at %s' > log.csv
		$file = fopen("log.csv","r");
		while (($line = fgetcsv($file)) !== FALSE) {
			
    	$tabla .= "<tr>";
			//$line is an array of the csv elements
			$temp = implode($line);
			//echo substr($temp, 0, 10) . " && " . substr($temp, 11) . "<br />";
			$tabla .= "<td>" . date('d/m/Y', substr($temp, 0, 10)) . "</td>";
			
			$tabla .= "<td>";
			$exploded = preg_split('@-@', substr($temp, 11), NULL, PREG_SPLIT_NO_EMPTY);
			
			$tabla .= "<ul>";
			foreach($exploded as $exp){
				$tabla .= "<li>" . $exp . "</li>";
			}
			$tabla .= "</ul>";
			
			$tabla .= "</td>";
			
			//$tabla .= "<td>" . $exploded[0] . "</td>";
			$tabla .= "</tr>";
		}
    fclose($file);
    
		$this->addVar("tabla",$tabla);
    
		$this->addLayout("admin");
		$this->processTemplate("admin/changelog.html");
	}
}
?>
<?php
class historico extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		
		
		$listaReportes = Fabrica::getAllFromDB("Reporte",array(),"fecha DESC");	
		
		$i=0;
		
		$reportes=array();
		
		foreach($listaReportes as $reporte){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$reportes[$i]["id"] = $i + 1;;
			$dias = strtotime(str_replace('/', '-', $reporte->getFecha("DATE"))) - strtotime(date('d-m-Y'));
			$dias = floor(abs($dias/86400));
			$reportes[$i]["antiguedad"] = $dias . " dias de antiguedad";
			$reportes[$i]["fecha"] = $reporte->getFecha("DATETIME"); 
			$reportes[$i]["link"] = $reporte->getLink();
			$reportes[$i]["datos"] = $reporte->getDatos();
			$i++;				
		}

		$this->addLoop("reportes",$reportes);
		
		$this->addLayout("ace");
		
		$this->processTemplate("reportes/historico.html");
	}
}
?>
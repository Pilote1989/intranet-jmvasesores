<?php
class obtenerComision extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		//$fc->import("lib.Endoso");
		$response["comision"] = 12.5;
		if($this->request->idRamo && $this->request->idRamo){
			$comision = Fabrica::getAllFromDB("Comision",array("idRamo = '".$this->request->idRamo."'","idRamo = '".$this->request->idRamo."'"));
			if(count($comision)>0){
				$response["comision"] = $comision[0]->getComision();
			}
		}
		echo json_encode($response);
	}
}
?>
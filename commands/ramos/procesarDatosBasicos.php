<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Ramo");
		$fc->import("lib.Comision");
		$nuevo = true;
		if($this->request->idRamo){
			$ramo=Fabrica::getFromDB("Ramo",$this->request->idRamo);
			$nuevo = false;
		}else{
			$ramo=new Ramo();
			$nuevo = true;
		}
		$ramo->setNombre($this->request->nombre);
		$ramo->setSigla($this->request->sigla);
		$ramo->storeIntoDB();
		$dbLink=&FrontController::instance()->getLink();
		if($this->request->idRamo){
			$id=$this->request->idRamo;
		}else{
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$dbLink->next_result();
		}
		if($nuevo == true){
			$companias=Fabrica::getAllFromDB("Compania",array());
			foreach($companias as $compania){
				$comision=new Comision();
				$comision->setIdCompania($compania->getId());
				$comision->setIdRamo($id);
				$comision->setComision("12.5");
				$comision->storeIntoDB();
				$dbLink->next_result();
				$dbLink->next_result();
			}
		}
		$fc->redirect("?do=ramos.ver&idRamo=".$id);
	}
}
?>
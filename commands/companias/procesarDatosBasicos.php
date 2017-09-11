<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Compania");
		$fc->import("lib.Comision");
		$nuevo = true;
		if($this->request->idCompania){
			$compania=Fabrica::getFromDB("Compania",$this->request->idCompania);
			$nuevo = false;
		}else{
			$compania=new Compania();
			$nuevo = true;
		}
		$compania->setNombre($this->request->nombre);
		$compania->setSigla($this->request->sigla);
		$compania->storeIntoDB();
		$dbLink=&FrontController::instance()->getLink();
		if($this->request->idCompania){
			$id=$this->request->idCompania;
		}else{
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$dbLink->next_result();
		}
		foreach($this->request->comisiones as $clave => $comision){
			if($nuevo){
				$comisionTemp = new Comision();
				$comisionTemp->setIdCompania($id);
				$comisionTemp->setIdRamo($clave);
				$comisionTemp->setComision($comision);
				$comisionTemp->storeIntoDB();
				$dbLink->next_result();
				$dbLink->next_result();
			}else{
				$comisionTemp = Fabrica::getAllFromDB("Comision", 
					array(
						"idCompania = " . $id,
						"idRamo = " . $clave,
					)
				);	
				$comisionTemp[0]->setComision($comision);
				$comisionTemp[0]->storeIntoDB();
				$dbLink->next_result();
				$dbLink->next_result();
			}
		}
		$fc->redirect("?do=companias.verDatosBasicos&idCompania=".$id);
	}
}
?>
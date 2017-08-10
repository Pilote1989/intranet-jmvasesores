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
		$compania->setDireccion($this->request->direccion);
		$compania->setCorreo($this->request->correo);
		$compania->setCorreoAlternativo($this->request->correo2);
		
		$compania->storeIntoDB();
		
		$dbLink=&FrontController::instance()->getLink();
		
		if($this->request->idCompania){
			$id=$this->request->idCompania;
		}else{
			$dbLink->next_result();
			$id=$dbLink->insert_id;
		}
		
		
		foreach($this->request->comisiones as $clave => $comision){
			if($nuevo){
				$comisionTemp = new Comision();
				$comisionTemp->setIdCompania($id);
				$comisionTemp->setIdRamo($clave);
				$comisionTemp->setComision($comision);
				$comisionTemp->storeIntoDB();
			}else{
				$comisionTemp = Fabrica::getAllFromDB("Comision", 
					array(
						"idCompania = " . $id,
						"idRamo = " . $clave,
					)
				);	
				$comisionTemp[0]->setComision($comision);
				$comisionTemp[0]->storeIntoDB();
			}
			
			//echo $clave . " - " . $comision . "<br />";
		}
		$fc->redirect("?do=companias.verDatosBasicos&idCompania=".$id);
		//print_r($this->request);
	}
}
?>
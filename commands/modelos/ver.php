<?php
class ver extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$fc->import("lib.Modelo");
		$selectModelos = '\n<option></option>';	
		
		if($this->request->idMarca){
			$modelos = Fabrica::getAllFromDB("Modelo",array("idMarca = " . $this->request->idMarca),"modelo ASC");
			foreach($modelos as $modelo){
				$selectModelos .= '\n<option value="'.$modelo->getId().'" >'.$modelo->getModelo().'</option>';
			}
		}
		echo $selectModelos;
	}
}
?>
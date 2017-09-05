<?php
class obtenerProvincias extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->departamento){
			$ubigeo = Fabrica::getFromDB("Ubigeo",$this->request->departamento);
			$provincias = Fabrica::getAllFromDB("Ubigeo",array("departamento = '". $ubigeo->getDepartamento() ."'","provincia <> '00'","distrito = '00'"));
			$selectProvincias = "<option></option>";
			foreach($provincias as $provincia){
				$selectProvincias .= "<option value='" . $provincia->getId() . "'>" . $provincia->getNombre() . "</option>";
			}	
			echo $selectProvincias;

		}
	}
}
?>
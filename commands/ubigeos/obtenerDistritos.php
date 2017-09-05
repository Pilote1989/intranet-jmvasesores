<?php
class obtenerDistritos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->provincia){
			$ubigeo = Fabrica::getFromDB("Ubigeo",$this->request->provincia);
			$distritos = Fabrica::getAllFromDB("Ubigeo",array("distrito <> '00'","provincia = '".$ubigeo->getProvincia()."'","departamento = '".$ubigeo->getDepartamento()."'"));
			$selectDistritos = "<option></option>";
			foreach($distritos as $distrito){
				$selectDistritos .= "<option value='" . $distrito->getId() . "'>" . $distrito->getNombre() . "</option>";
			}	
			echo $selectDistritos;

		}
	}
}
?>
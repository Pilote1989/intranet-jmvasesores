<?php
class verVehiculos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaVehiculos"]["contratante"]="";
			$_SESSION["busquedaVehiculos"]["limite"]="";
			$_SESSION["busquedaVehiculos"]["placa"]="";
		}
	
		
		/* Todo para el placa */
		if($_SESSION["busquedaVehiculos"]["placa"]){
			$this->addVar("placa",$_SESSION["busquedaVehiculos"]["placa"]);
		}
		else{
			$this->addEmptyVar("placa");
		}

		/* Seleccinar el nombre 	
		if($_SESSION["busquedaVehiculos"]["contratante"]){
			$this->addVar("contratante",$_SESSION["busquedaVehiculos"]["contratante"]);
		}
		else{
			$this->addEmptyVar("contratante");
		}*/
		
		/* Limite por pagina */	
		if($_SESSION["busquedaVehiculos"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaVehiculos"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		//print_r($_SESSION);
		$this->addLayout("ace");
		$this->processTemplate("vehiculos/verVehiculos.html");
	}
}
?>
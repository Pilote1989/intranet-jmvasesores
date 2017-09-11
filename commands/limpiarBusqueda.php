<?php
class limpiarBusqueda extends sessionCommand{
	function execute(){
		$usuario=$this->getUsuario();
		$mapa = array(
			"clientes" => "busquedaClientes",
			"ramos" => "busquedaRamos",
			"companias" => "busquedaCompanias"
		);
		$response = false;
		$matriz=$this->request->matriz;
		if(array_key_exists($matriz,$mapa)){
			$_SESSION[$mapa[$this->request->matriz]] = array();
			$response = true;
		}
		echo json_encode($response);
	}
}
?>
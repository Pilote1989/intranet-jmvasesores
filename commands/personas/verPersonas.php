<?php
class verPersonas extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		if($this->request->limpiar){
			$_SESSION["busquedaPersonas"]["nombre"]="";
			$_SESSION["busquedaPersonas"]["apellidoPaterno"]="";
			$_SESSION["busquedaPersonas"]["apellidoMaterno"]="";
			$_SESSION["busquedaPersonas"]["usuario"]="";
			$_SESSION["busquedaPersonas"]["correo"]="";
			$_SESSION["busquedaPersonas"]["limite"]="";
		}
	
		/* Todo para el fechaInicio */
		if($_SESSION["busquedaPersonas"]["nombre"]){
			$this->addVar("nombre",$_SESSION["busquedaPersonas"]["nombre"]);
		}
		else{
			$this->addEmptyVar("nombre");
		}

		
		/* Todo para el fechaInicio */
		if($_SESSION["busquedaPersonas"]["apellidoPaterno"]){
			$this->addVar("apellidoPaterno",$_SESSION["busquedaPersonas"]["apellidoPaterno"]);
		}
		else{
			$this->addEmptyVar("apellidoPaterno");
		}
		
		/* Seleccinar el tipo */	
		if($_SESSION["busquedaPersonas"]["apellidoMaterno"]){
			$this->addVar("apellidoMaterno",$_SESSION["busquedaPersonas"]["apellidoMaterno"]);
		}
		else{
			$this->addEmptyVar("apellidoMaterno");
		}
		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaPersonas"]["usuario"]){
			$this->addVar("usuario",$_SESSION["busquedaPersonas"]["usuario"]);
		}
		else{
			$this->addEmptyVar("usuario");
		}
		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaPersonas"]["correo"]){
			$this->addVar("correo",$_SESSION["busquedaPersonas"]["correo"]);
		}
		else{
			$this->addEmptyVar("correo");
		}
		//print_r($_SESSION);
		$this->addLayout("admin");
		$this->processTemplate("personas/verPersonas.html");
	}
}
?>
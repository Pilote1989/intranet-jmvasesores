<?php
class recordatorios extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		if($this->checkAccess("crearUsuario", true)){
			$this->addBlock("admin");
		}
		if($this->request->limpiar){
			$_SESSION["busquedaCupones"]["rango"] = "";
			$_SESSION["busquedaCupones"]["porEnviar"] = "";
			$_SESSION["busquedaCupones"]["enviado"] = "";
			$_SESSION["busquedaCupones"]["enviadoHoy"] = "";
			$_SESSION["busquedaCupones"]["pagina"] = "";
		}
		
		if($_SESSION["busquedaCupones"]["porEnviar"]){
			$this->addVar("porEnviar",$_SESSION["busquedaCupones"]["porEnviar"]);
		}
		else{
			$this->addVar("porEnviar",'checked="checked"');
		}
		
		if($_SESSION["busquedaCupones"]["enviado"]){
			$this->addVar("enviado",$_SESSION["busquedaCupones"]["enviado"]);
		}
		else{
			$this->addVar("enviado",'checked="checked"');
		}
		
		if($_SESSION["busquedaCupones"]["enviadoHoy"]){
			$this->addVar("enviadoHoy",$_SESSION["busquedaCupones"]["enviadoHoy"]);
		}
		else{
			$this->addVar("enviadoHoy",'checked="checked"');
		}
		
		/* Todo para el rango */
		if($_SESSION["busquedaCupones"]["rango"]){
			$this->addVar("rango",$_SESSION["busquedaCupones"]["rango"]);
		}
		else{
			$this->addEmptyVar("rango");
		}
		
		$fecha = date('Y-m-j');
		$nuevafecha = strtotime('-14 day',strtotime($fecha));
		$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
		$this->addVar("minimo",$nuevafecha);
		// Nombre
		$this->addBlock("bloqueNombre");
		//$this->addVar("nombreUsuario", $usuario->getNombres());
		$this->addLayout("admin");
		$this->processTemplate("admin/recordatorios.html");
	}
}
?>
<?php
class Persona extends DBObject{
	var $tableName="Persona";
	function login($mail,$password)
	{
		if(empty($mail)||empty($password))
			return false;
		$filtros=array();
		$filtros[]="mail='".$mail."'";
		$filtros[]="password='".md5($password)."'";
		$filtros[]="habilitado='1'";
		$usuarios=Fabrica::getAllFromDB("Persona",$filtros);
		if(sizeof($usuarios)==1)
		{
			$_SESSION["TIPO_LOGIN"]="CLIENTES";
			$_SESSION["ID"]=$usuarios[0]->getId();
			SessionCommand::setUsuario($usuarios[0]);
			return true;
		}else{
			$filtros_alt=array();
			$filtros_alt[]="userName='".$mail."'";
			$filtros_alt[]="password='".md5($password)."'";
			$filtros_alt[]="habilitado='1'";
			$usuarios=Fabrica::getAllFromDB("Persona",$filtros_alt);
			if(sizeof($usuarios)==1)
			{
				SessionCommand::setUsuario($usuarios[0]);
				$_SESSION["TIPO_LOGIN"]="CLIENTES";
				$_SESSION["ID"]=$usuarios[0]->getId();
				return true;
			}
		}
		return false;
	}
	
	static function permission($permiso,$idPersona){
		$query="
			SELECT 	pe.idPerfil
			FROM	PersonaEnPerfil pp, Perfil pe
			WHERE	pp.idPersona='".$idPersona."' AND
					pp.idPerfil=pe.idPerfil AND
					".$permiso."='1'
		";
		$fc=FrontController::instance();
		$query=utf8_decode($query);
		//echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		$link=$fc->getLink();
		if($result=$link->query($query)){
			$resultados=array();
			$row=$result->fetch_assoc();
			if($row!=NULL){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			printf("Error: %s\n", $link->error);
			return null;
		}
	}
	function getPerfiles(){
		$query="
			SELECT 	pp.idPerfil
			FROM	PersonaEnPerfil pp, Perfil pe
			WHERE	pp.idPersona='".$this->getIdPersona()."' AND
					pp.idPerfil=pe.idPerfil
		";
		$fc=FrontController::instance();
		$query=utf8_decode($query);
		//echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		$link=$fc->getLink();
		if($result=$link->query($query)){
			$resultados=array();
			while($row=$result->fetch_assoc()){
				$resultados[]=Fabrica::getFromDB("Perfil",$row["idPerfil"]);
			}
			if(count($resultados)>0){
				return $resultados;
			}
			else{
				return null;
			}
		}
		else{
			printf("Error: %s\n", $link->error);
			return null;
		}
	}
	function agregarPerfil($idPerfil){
		$query="INSERT INTO PersonaEnPerfil (idPersona, idPerfil) values ('".$this->getIdPersona()."','".$idPerfil."')";
		$fc=FrontController::instance();
		$query=utf8_decode($query);
		//echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		$link=$fc->getLink();
		if($result=$link->query($query)){
			return true;
		}
		else{
			printf("Error: %s\n", $link->error);
			return null;
		}
	}
	function quitarPerfil($idPerfil){
		$query="DELETE FROM PersonaEnPerfil WHERE idPersona='".$this->getIdPersona()."' AND idPerfil='".$idPerfil."'";
		$fc=FrontController::instance();
		$query=utf8_decode($query);
		echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		$link=$fc->getLink();
		if($result=$link->query($query)){
			return true;
		}
		else{
			printf("Error: %s\n", $link->error);
			return null;
		}
	}
	function getMenus(){
		$menus=array();
		if($this->revisaPermiso("solicitudes")){
			$menus[]="solicitudes";
		}
		if($this->revisaPermiso("administracion")){
			$menus[]="administracion";
		}
		if($this->revisaPermiso("contabilidad")){
			$menus[]="contabilidad";
		}
		if($this->revisaPermiso("polizas")){
			$menus[]="polizas";
		}
		if($this->revisaPermiso("comisiones")){
			$menus[]="comisiones";
		}
		if($this->revisaPermiso("compras")){
			$menus[]="compras";
		}
		if($this->revisaPermiso("clientes")){
			$menus[]="clientes";
		}
		if($this->revisaPermiso("companias")){
			$menus[]="companias";
		}
		if($this->revisaPermiso("reportes")){
			$menus[]="reportes";
		}
		return $menus;
	}
	function revisaPermiso($permiso){
		$query="
			SELECT 	pe.idPerfil
			FROM	PersonaEnPerfil pp, Perfil pe
			WHERE	pp.idPersona='".$this->getId()."' AND
					pp.idPerfil=pe.idPerfil AND
					".$permiso."='1'
		";
		$fc=FrontController::instance();
		$query=utf8_decode($query);
		$link=$fc->getLink();
		if($result=$link->query($query)){
			$resultados=array();
			$row=$result->fetch_assoc();
			if($row!=NULL){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			printf("Error: %s\n", $link->error);
			return null;
		}
	}
}
?>
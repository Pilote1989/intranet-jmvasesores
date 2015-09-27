<?php
class crearPoliza extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		$persona = Fabrica::getFromDB('Persona', $usuario->getId());
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		//para escribir datos basicos
		$this->addEmptyVar("readonly");
		//variables de formulario
		$this->addEmptyVar("contratante");
		$this->addEmptyVar("direccion");
		$this->addEmptyVar("ruc");
		$this->addEmptyVar("tipoDeMercaderia");
		$this->addEmptyVar("descripcionMercaderia");
		$this->addEmptyVar("lugarOrigen");
		$this->addEmptyVar("fechaSalida");
		$this->addEmptyVar("lugarDestino");
		$this->addEmptyVar("fechaLlegada");
		$this->addEmptyVar("proovedor");
		$this->addEmptyVar("nombreBuque");
		$this->addEmptyVar("tipoEmbalaje");
		$this->addEmptyVar("fob");
		$this->addEmptyVar("flete");
		$this->addEmptyVar("aduana");
		$this->addEmptyVar("sobreseguro");
		$this->addEmptyVar("observaciones");
		$this->addVar("nombre", $usuario->getNombres()." ".$usuario->getApellidoPaterno());
		$this->addLayout("admin");
		$this->processTemplate("transportes/crearPoliza.html");
	}
}
?>
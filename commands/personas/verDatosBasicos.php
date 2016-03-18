<?php
class verDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		if($this->request->idPersona){
			$persona = Fabrica::getFromDB("Persona",$this->request->idPersona);
			$this->addVar("idPersona", $this->request->idPersona);
			$this->addVar("nombre", $persona->getNombres());
			$this->addVar("apellidoPaterno", $persona->getApellidoPaterno());
			$this->addVar("apellidoMaterno", $persona->getApellidoMaterno());
			$this->addVar("ruc", $persona->getRuc());
			$this->addVar("correo", $persona->getMail());
			$this->addVar("usuario", $persona->getUserName());
			$this->addVar("comision", $persona->getComision());
			if($persona->getHabilitado() == "1"){
				$this->addVar("estado", '<i class="ace-icon fa fa-circle light-green middle"></i> Habilitado');
			}else{
				$this->addVar("estado", '<i class="ace-icon fa fa-circle red middle"></i> Deshabilitado');
			}
			$this->addLayout("ace");
			$this->processTemplate("personas/verDatosBasicos.html");
		}
	}
}
?>

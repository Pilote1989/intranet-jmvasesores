<?php
class reportes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$personas=Fabrica::getAllFromDB("Persona",array("vendedor = '1'"),"nombres ASC");	
		$selectPersona = '<option value=""></option>';
		foreach($personas as $persona){
			$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
		}
		$this->addVar("selectAsesores",$selectPersona);		
		
		$this->addLayout("ace");
		$this->processTemplate("liquidaciones/reportes.html");
	}
}
?>
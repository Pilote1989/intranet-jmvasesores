<?php
class resetearClave extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		if($this->checkAccess("cambiarClaves", true) && !(is_null($this->request->idPersona))){
			$persona = Fabrica::getFromDB("Persona",$this->request->idPersona);
			$persona->setPassword("12345", "MD5");
			$persona->storeIntoDB();
			print json_encode('
			<div class="alert alert-success">
			  <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-remove"></i> </button>
			  <p> <strong> <i class="ace-icon fa fa-ok"></i> ¡Exito! </strong> La clave ha sido reseteada. </p>
			</div>
			');
			//$fc->redirect("?do=personas.verDatosBasicos&idPersona=".$this->request->idPersona."&cc=1");
		}else{
			print json_encode('
			<div class="alert alert-danger">
			  <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-remove"></i> </button>
			  <p> <strong> <i class="ace-icon fa fa-ok"></i> ¡Error! </strong> No se ha podido resetear la clave. </p>
			</div>
			');
			//$fc->redirect("?do=personas.verPortada");
		}
	}
}
?>
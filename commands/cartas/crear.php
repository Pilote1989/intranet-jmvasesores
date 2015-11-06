<?php
class crear extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			if($poliza->getIdRamo()=="2"){
				$this->addBlock("vehiculo");
			}else if($poliza->getIdRamo()=="4"){
				$this->addBlock("amed");
			}
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->processTemplate("cartas/crear.html");
		}
	}
}
?>
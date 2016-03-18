<?php
class editarDatosParticulares extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$this->addBlock("bloqueEditarDatosParticulares");
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$this->addVar("intereses",$poliza->getIntereses());
			$this->addVar("renovacion",$poliza->getRenovacion());
			$this->addVar("derecho",number_format($poliza->getDerecho(),2,'.',''));
			$this->addVar("igv",number_format($poliza->getIgv(),2,'.',''));
			$this->addVar("total",number_format($poliza->getTotal(),2,'.',''));
			$this->addVar("comision",number_format($poliza->getComision(),2,'.',''));
			$this->addVar("nuevoCliente","1");
			$marcas=Fabrica::getAllFromDB("Marca",array(),"marca ASC");	
			$selectMarca = '<option value=""></option>';
			foreach($marcas as $marca){
				$selectMarca=$selectMarca.'<option value="'.$marca->getId().'" >'.$marca->getMarca().'</option>';
			}
			$this->addVar("idRamo",$poliza->getIdRamo());
			$this->addVar("marcas",$selectMarca);
			
		}else{
			$fc->redirect("?do=personas.verPortada");
		}
		
		$this->addLayout("ace");

		//if($this->request->idPoliza){
		//	$this->addLayout("ficha");
		//	$this->addVar("nombreFicha", "Ficha Poliza");
		//	$this->addVar("menu", "menuPolizas?idPoliza=".$this->request->idPoliza);
		//}

		$this->processTemplate("polizas/editarDatosParticulares.html");
	}
}
?>
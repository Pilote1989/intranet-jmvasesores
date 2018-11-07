<?php
class despacho extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idCarta){
			//editando carta			
			$carta = Fabrica::getFromDB("Carta",$this->request->idCarta);
			$pieces = explode("-", $carta->getFecha());	
			$this->addVar("idCarta", $this->request->idCarta);
			$this->addVar("anio", $pieces[0]);
			$this->addVar("numero", sprintf('%05d', $this->request->idCarta));
			//$this->addVar("carta", $carta->getCarta("CARTA"));
			$this->addVar("detalle", $carta->getDetalle());
			$this->addVar("idPoliza", $carta->getIdPoliza());
			$poliza = Fabrica::getFromDB("Poliza",$carta->getIdPoliza());
			$ramo = Fabrica::getFromDB("Ramo", $poliza->getIdRamo());
			$cliente = Fabrica::getFromDB("Cliente", $poliza->getIdCliente());
			$this->addVar("asunto", "Despacho de Documentos - Cliente : ".$cliente->getNombre()." - Poliza : " . $ramo->getSigla() . "-". $poliza->getNumeroPoliza() );
			$this->addVar("pdf", $poliza->getPdf());
			if($poliza->getPdf()==""){
				$this->addBlock("noPDF");
			}else{
				$this->addBlock("siPDF");
			}
			$this->processTemplate("cartas/despacho.html");
		}
	}
}
?>
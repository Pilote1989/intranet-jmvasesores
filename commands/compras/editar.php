<?php
class editar extends sessionCommand{
	function execute(){
		// -> Banner
		$distrito = 99;
		$this->addVar("doFalso", $this->request->do);
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		if($this->request->idCompra!=""){
			$compra=Fabrica::getFromDB("Compra",$this->request->idCompra);
			$this->addVar("idCompra", $this->request->idCompra);
			$this->addVar("numeroFactura", $compra->getNumeroFactura());
			$this->addVar("idCliente", $compra->getIdCliente());
			$cliente=Fabrica::getFromDB("Cliente", $compra->getIdCliente());
			$this->addVar("ruc", $cliente->getDoc());
			$this->addVar("nombre", $cliente->getNombre());
			$this->addVar("concepto", $compra->getConcepto());
			$this->addVar("tipo", $compra->getTipo());
			$this->addVar("moneda", $compra->getMoneda());
			$this->addVar("subtotal", $compra->getSubtotal());
			$this->addVar("igv", $compra->getIgv());
			$this->addVar("otros	", $compra->getOtros());
			$this->addVar("total", $compra->getTotal());
			$this->addVar("comando", "Editar");
			$this->addVar("fechaFac", $compra->getFecha("DATE"));
			$this->addVar("fechaPre", $compra->fechaPresentacion());
			$this->addLayout("ace");
			$this->processTemplate("compras/editar.html");
		}else{
			$fc->redirect("?do=compras.ver");
		}
	}
}
?>
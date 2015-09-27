<?php
class editarFacturacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Endoso");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");
		if($this->request->idPoliza){
			$this->addVar("idPoliza", $this->request->idPoliza);
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$this->addVar("cobranza", $cobro->getAvisoDeCobranza());
			$this->addVar("primaNeta", $cobro->getPrimaNeta());
			$this->addVar("derechoEmision", $cobro->getDerechoEmision());
			$this->addVar("primaComercial", $cobro->getPrimaComercial());
			$this->addVar("igv", $cobro->getIgv());
			$this->addVar("intereses", $cobro->getIntereses());
			$this->addVar("totalFactura", $cobro->getTotalFactura());
			$this->addVar("comisionP", $cobro->getComisionP());
			$this->addVar("comision", $cobro->getComision());
			$this->addBlock("bloqueEditarFacturacion");
		}
		$this->processTemplate("polizas/editarFacturacion.html");
	}
}
?>
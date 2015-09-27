<?php
class verEndoso extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Endoso");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");
		if($this->request->idEndoso){
			$endoso = Fabrica::getFromDB("Endoso",$this->request->idEndoso);				
			$this->addVar("accion", "Editar Endoso");
			$this->addBlock("bloqueIdEndoso");
			$this->addVar("idEndoso", $this->request->idEndoso);
			$this->addVar("documento", $endoso->getDocumento());
			$this->addVar("detalle", $endoso->getDetalle());
			$this->addVar("inicioVigencia", $endoso->getInicioVigencia());
			$this->addVar("finVigencia", $endoso->getFinVigencia());
			$cobro = Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
			$this->addVar("cobranza", $cobro->getAvisoDeCobranza());
			$this->addVar("primaNeta", $cobro->getPrimaNeta());
			$this->addVar("derechoEmision", $cobro->getDerechoEmision());
			$this->addVar("primaComercial", $cobro->getPrimaComercial());
			$this->addVar("igv", $cobro->getIgv());
			$this->addVar("intereses", $cobro->getIntereses());
			$this->addVar("totalFactura", $cobro->getTotalFactura());
			$this->addVar("comisionP", $cobro->getComisionP());
			$this->addVar("comision", $cobro->getComision());
			$this->addVar("comisionVendedorP", $cobro->getComisionCedidaP());
			$this->addVar("comisionVendedor", $cobro->getComisionCedida());
			$persona = Fabrica::getFromDB("Persona", $cobro->getIdPersona());
			$this->addVar("asesor", $persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno());

			if($cobro->getIdLiquidacion() == ""){
				$this->addVar("estadoCobranza", '<i class="icon-circle red"></i>&nbsp;&nbsp;&nbsp;Pendiente');
			}else{
				$liquidacion = Fabrica::getFromDB("Liquidacion", $cobro->getIdLiquidacion());
				$this->addVar("estadoCobranza", '<i class="icon-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.ver&idLiquidacion=' . $cobro->getIdLiquidacion() . '">Liquidada - Factura : ' . $liquidacion->getFactura() . '</a>');
			}
			
			if($cobro->getIdPersona()!=1){
    		    $this->addBlock("datosComision");
            }
			if($cobro->getIdCedida() == ""){
				$this->addVar("estadoComisionCedida", '<i class="icon-circle red"></i>&nbsp;&nbsp;&nbsp;Pendiente');
			}else{
				$cedida = Fabrica::getFromDB("Cedida", $cobro->getIdCedida());
				$this->addVar("estadoComisionCedida", '<i class="icon-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.verCedida&idCedida=' . $cobro->getIdCedida() . '">Liquidada - Factura : ' . $cedida->getFactura() . '</a>');
			}			
			
			
			
			
			$this->processTemplate("polizas/verEndoso.html");
		}
	}
}
?>
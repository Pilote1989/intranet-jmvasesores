<?php
class buscaMes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		setlocale(LC_ALL,"es_ES");
		$codMes = array("ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic");
		$codigoMes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre");
		$nomMeses = array("01 - Ene.","02 - Feb.","03 - Mar.","04 - Abr.","05 - May.","06 - Jun.","07 - Jul.","08 - Ago.","09 - Sep.","10 - Oct.","11 - Nov.","12 - Dic.");
		/*
		ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
		*/
		if($this->request->id){
			$mes = Fabrica::getFromDB("Mes",$this->request->id);
			$cliente = Fabrica::getFromDB("Cliente",$this->request->idCliente);
			
			$this->addVar("mes",$codigoMes[$mes->getMes()-1]);
			$this->addVar("anio",$mes->getAnio());
			if($mes->getEstado()=="0"){
				$this->addVar("estado","Abierto");
				$this->addVar("tarea","Cerrar");
			}else{
				$this->addVar("estado","Cerrado");
				$this->addVar("tarea","Abrir");
			}
			$this->addVar("idMes",$this->request->id);
			$this->addVar("color",$mes->estadoColor());
			
			$this->addVar("tc",number_format($mes->getTc(),2));
			$this->addVar("primaNetaDolares",number_format($mes->getPrimaNetaDolares(),2));
			$this->addVar("comisionDolares",number_format($mes->getComisionDolares(),2));
			$this->addVar("igvFacturasDolares",number_format($mes->getIgvFacturasDolares(),2));
			$this->addVar("totalFacturasDolares",number_format($mes->getTotalFacturasDolares(),2));
			$this->addVar("comisionCedidaDolares",number_format($mes->getComisionCedidaDolares(),2));
			$this->addVar("igvCedidasDolares",number_format($mes->getIgvCedidasDolares(),2));
			$this->addVar("totalCedidasDolares",number_format($mes->getTotalCedidasDolares(),2));
			$this->addVar("subtotalComprasDolares",number_format($mes->getSubtotalComprasDolares(),2));
			$this->addVar("igvComprasDolares",number_format($mes->getIgvComprasDolares(),2));
			$this->addVar("otrosComprasDolares",number_format($mes->getOtrosComprasDolares(),2));
			$this->addVar("totalComprasDolares",number_format($mes->getTotalComprasDolares(),2));

			$this->addVar("primaNetaSoles",number_format($mes->getPrimaNetaSoles(),2));
			$this->addVar("comisionSoles",number_format($mes->getComisionSoles(),2));
			$this->addVar("igvFacturasSoles",number_format($mes->getIgvFacturasSoles(),2));
			$this->addVar("totalFacturasSoles",number_format($mes->getTotalFacturasSoles(),2));
			$this->addVar("comisionCedidaSoles",number_format($mes->getComisionCedidaSoles(),2));
			$this->addVar("igvCedidasSoles",number_format($mes->getIgvCedidasSoles(),2));
			$this->addVar("totalCedidasSoles",number_format($mes->getTotalCedidasSoles(),2));
			$this->addVar("subtotalComprasSoles",number_format($mes->getSubtotalComprasSoles(),2));
			$this->addVar("igvComprasSoles",number_format($mes->getIgvComprasSoles(),2));
			$this->addVar("otrosComprasSoles",number_format($mes->getOtrosComprasSoles(),2));
			
			
			$this->addVar("totalComprasSoles",number_format($mes->getTotalComprasSoles(),2));			
			
			$this->processTemplate("contabilidad/buscaMes.html");
		}else{
			echo "Error";
		}
		
    	
	}
}
?>
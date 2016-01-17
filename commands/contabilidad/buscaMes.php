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
			$this->addVar("primaNeta",number_format($mes->getPrimaNeta(),2));
			$this->addVar("comision",number_format($mes->getComision(),2));
			$this->processTemplate("contabilidad/buscaMes.html");
		}else{
			echo "Error";
		}
		
    	
	}
}
?>
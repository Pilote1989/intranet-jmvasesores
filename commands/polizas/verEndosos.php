<?php
class verEndosos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.Cliente");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");		
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$this->addVar("idPoliza",$this->request->idPoliza);
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			//primero busco los datos de cobro de la poliza madre
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$this->addVar("documento",$poliza->getDocumento());
			$this->addVar("avisoDeCobranza",$cobro->getAvisoDeCobranza());		
			$this->addVar("primaNeta",number_format($cobro->getPrimaNeta(),2));			
			$this->addVar("comision",number_format($cobro->getComision(),2));	
			$endosos = Fabrica::getAllFromDB("Endoso", array("idPoliza = " . $this->request->idPoliza));
			$listaEndosos = array();
			$i = 0;
			if(count($endosos)){
				$this->addBlock("bloqueResultados");	
			}else{
				$this->addBlock("bloqueNoResultados");				
			}
			foreach($endosos as $endoso){
				$cobro = Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
				$listaEndosos[$i]["idLista"] = $i + 1;
				$listaEndosos[$i]["documento"] = $endoso->getDocumento();
				$listaEndosos[$i]["avisoCobranza"] = $cobro->getAvisoDeCobranza();
				$listaEndosos[$i]["primaNeta"] = number_format($cobro->getPrimaNeta(),2);
				$listaEndosos[$i]["comision"] = number_format($cobro->getComision(),2);;
				$listaEndosos[$i]["idEndoso"] = $endoso->getId();
				if($cobro->getIdLiquidacion()!="" || $cobro->getIdCedida()!=""){
					//NO puedo eliminarlo ni anularlo					
					$listaEndosos[$i]["verEliminar"] = "style='display:none'";
					$listaEndosos[$i]["anula"] = "";
					$listaEndosos[$i]["estadoCobranza"] = '<i class="ace-icon fa fa-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.ver&idLiquidacion=' . $cobro->getIdLiquidacion() . '">Liquidada</a>';
				}else{				
					//SI puedo eliminarlo o anularlo
					$listaEndosos[$i]["verEliminar"] = "";
					if($endoso->getAnulada() == "1"){
						$listaEndosos[$i]["anula"] = '<a class="blue rehabilitaEndoso" x-id="' . $listaEndosos[$i]["idEndoso"] . '" href="#"> <i class="ace-icon fa fa-arrow-circle-o-up bigger-130"></i> </a>';
						$listaEndosos[$i]["estadoCobranza"] = '<i class="ace-icon fa fa-circle"></i>&nbsp;&nbsp;&nbsp;Anulado';
					}else{
						$listaEndosos[$i]["anula"] = '<a class="blue anulaEndoso" x-id="' . $listaEndosos[$i]["idEndoso"] . '" href="#"> <i class="ace-icon fa fa-arrow-circle-o-down bigger-130"></i> </a>';
						if($cobro->getIdLiquidacion() == ""){
							$listaEndosos[$i]["estadoCobranza"] = '<i class="ace-icon fa fa-circle red"></i>&nbsp;&nbsp;&nbsp;Pendiente';
						}else{
							$listaEndosos[$i]["estadoCobranza"] = '<i class="ace-icon fa fa-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.ver&idLiquidacion=' . $cobro->getIdLiquidacion() . '">Liquidada</a>';
						}					
					}					
				}
				$i++;
			}
			$this->addLoop("endosos", $listaEndosos);
			$this->processTemplate("polizas/verEndosos.html");
		}
	}
}
?>
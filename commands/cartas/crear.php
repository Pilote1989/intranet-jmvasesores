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
			//inicio - despacho de endosos
			if($this->request->idPoliza){
				$this->addVar("idPoliza",$this->request->idPoliza);
				$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
				//primero busco los datos de cobro de la poliza madre
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
					
					if($cobro->getIdLiquidacion() == ""){
						$listaEndosos[$i]["estadoCobranza"] = '<i class="icon-circle red"></i>&nbsp;&nbsp;&nbsp;Pendiente';
					}else{
						$listaEndosos[$i]["estadoCobranza"] = '<i class="icon-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.ver&idLiquidacion=' . $cobro->getIdLiquidacion() . '">Liquidada</a>';
					}
					$i++;
				}
				$this->addLoop("endosos", $listaEndosos);
			}
			//fin - despacho de endosos
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->processTemplate("cartas/crear.html");
		}
	}
}
?>
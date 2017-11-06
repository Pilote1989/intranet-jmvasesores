<?php
class verCupones extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cupones = Fabrica::getAllFromDB("Cupon", array("idPoliza = " . $poliza->getId()), "fechaVencimiento ASC");
			$listaCupones = array();
			$i = 0;
			if(count($cupones)){
				$this->addBlock("bloqueResultados");	
			}else{
				$this->addBlock("bloqueNoResultados");				
			}
			$total = 0;
			foreach($cupones as $cupon){
				//$listaCupones[$i]["fechaVencimiento"] = $cupon->getFechaVencimiento();
				$pieces = explode("-", $cupon->getFechaVencimiento());
				//print_r($pieces);
				//echo $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0] . "<br>";
				$listaCupones[$i]["fechaVencimiento"] = $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0];
				$listaCupones[$i]["monto"] = number_format($cupon->getMonto(),2);
				$total += $cupon->getMonto();
				$listaCupones[$i]["numeroCupon"] = $cupon->getNumeroCupon();
				$listaCupones[$i]["idLista"] = $i + 1;
				$listaCupones[$i]["idCupon"] = $cupon->getId();
				//echo $cupon->getId();
				$i++;
			}
			$this->addVar("sumaCupones",number_format($total,2));
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			if(number_format($total,2)==number_format($cobro->getTotalFactura(),2)){
				$this->addBlock("iguales");
			}else if(number_format($total,2)>number_format($cobro->getTotalFactura(),2)){
				$this->addBlock("mayor");
			}else{
				$this->addBlock("menor");
			}
			$this->addVar("totalFactura",number_format($cobro->getTotalFactura(),2));
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addVar("menu", "menuPolizas?idPoliza=".$this->request->idPoliza);
			$this->addLoop("cupones", $listaCupones);
			$this->processTemplate("polizas/verCupones.html");
		}
	}
}
?>
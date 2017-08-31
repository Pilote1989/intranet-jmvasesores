<?php
class verCorrespondencia extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			//$cartas = Fabrica::getAllFromDB("Cartas", array("idPoliza = " . $poliza->getId()), "fechaVencimiento ASC");
			$cartas = Fabrica::getAllFromDB("Carta", array("idPoliza = " . $poliza->getId()));
			$listaCartas = array();
			$i = 0;
			if(count($cartas)){
				$this->addBlock("bloqueResultados");	
			}else{
				$this->addBlock("bloqueNoResultados");				
			}
			foreach($cartas as $carta){
				//$listaCupones[$i]["fechaVencimiento"] = $cupon->getFechaVencimiento();
				$pieces = explode("-", $carta->getFecha());
				//print_r($pieces);
				//echo $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0] . "<br>";
				$listaCartas[$i]["fecha"] = $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0];
				$listaCartas[$i]["numero"] = $fc->appSettings["siglasCompania"] . " - " . sprintf('%05d', $carta->getId()) . " - " . $pieces[0];
				$listaCartas[$i]["detalle"] = $carta->getDetalle();
    			$listaCartas[$i]["idCarta"] = $carta->getId();
				//echo $cupon->getId();
				$i++;
			}
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addLoop("cartas", $listaCartas);
			$this->processTemplate("polizas/verCorrespondencia.html");
		}
	}
}
?>
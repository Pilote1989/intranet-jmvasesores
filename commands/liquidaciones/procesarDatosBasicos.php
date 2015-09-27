<?php
class procesarDatosBasicos extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Liquidacion");
		$fc->import("lib.Cobro");
		echo $this->request->fecha;
		if($this->request->idLiquidacion){
			//edito
			$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
			$id = $this->request->idLiquidacion;
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->storeIntoDB();
			$i = 0;
			foreach($this->request->idCobro as $idCobro){
				$cobro = Fabrica::getFromDB("Cobro",$idCobro);
				$cobro->setComisionP($this->request->facComisionP[$i]);
				$cobro->setComision($this->request->facComision[$i]);
				$cobro->setIdLiquidacion($id);
				$cobro->storeIntoDB();
				$i++;
			}			
		}else{
			//nuevo
			$liquidacion = new Liquidacion();
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$id=$dbLink->insert_id;
			$i = 0;
			foreach($this->request->idCobro as $idCobro){
				$cobro = Fabrica::getFromDB("Cobro",$idCobro);
				$cobro->setComisionP($this->request->facComisionP[$i]);
				$cobro->setComision($this->request->facComision[$i]);
				$cobro->setIdLiquidacion($id);
				$cobro->storeIntoDB();
				$i++;
			}
			print_r($this->request);
		}
		$fc->redirect("?do=liquidaciones.ver&idLiquidacion=" . $id);
	}
}
/*

Request Object
(
    [param] => Array
        (
            [0] => do
            [1] => do
            [2] => compania
            [3] => buscarAsegurado
            [4] => asegurado
            [5] => resultados_length
            [6] => resultadosSeleccionados_length
            [7] => test
            [8] => factura
            [9] => fecha
            [10] => idCobro
            [11] => facComisionP
            [12] => facComision
            [13] => totalFac
            [14] => obser
        )

    [do] => liquidaciones.procesarDatosBasicos
    [compania] => 9
    [buscarAsegurado] => goku
    [asegurado] => 294
    [resultados_length] => 5
    [resultadosSeleccionados_length] => 5
    [test] => 247
    [factura] => 
    [fecha] => 
    [idCobro] => Array
        (
            [0] => 414
            [1] => 247
        )

    [facComisionP] => Array
        (
            [0] => 124
            [1] => 12.5
        )

    [facComision] => Array
        (
            [0] => 124
            [1] => 125
        )

    [totalFac] => 249.00
    [obser] => 
)


<strong></strong>

*/
?>

<?php
class guardarCupones extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");			
		/*
		for($i=0 ; $i<$this->request->cupones; $i++){
			echo $i;
			$cupon=new Cupon();
			$cupon->setFechaVencimiento($this->request->vencimiento[$i],"DATE");
			$cupon->setMonto($this->request->monto[$i]);
			$cupon->setNumeroCupon($this->request->numeroCupon[$i]);
			$cupon->setIdPoliza($this->request->idPoliza);		
			$cupon->storeIntoDB();
		}*/
		$test = (array) $this->request;
		//print_r($test);
		$only_foo = array();
		foreach ($test as $key => $value) {
		    if (strpos($key, 'numeroCupon-') === 0) {
		        $only_foo[$key] = $value;
		        $actual = substr($key,12);
		        //echo "numeroCupon ".$actual. " : ".$test["numeroCupon-".$actual]."<br/>";
		        //echo "monto ".$actual. " : ".$test["monto-".$actual]."<br/>";
		        //echo "vencimiento ".$actual. " : ".$test["vencimiento-".$actual]."<br/>";
		        $cupon=new Cupon();
				$cupon->setFechaVencimiento($test["vencimiento-".$actual],"DATE");
				$cupon->setMonto($test["monto-".$actual]);
				$cupon->setNumeroCupon($test["numeroCupon-".$actual]);
				$cupon->setIdPoliza($this->request->idPoliza);
				$cupon->storeIntoDB();
		    }
		}
		//print_r($only_foo);
		$response["respuesta"]="SUCCESS";
		echo json_encode($response);
		
	}
}
?>
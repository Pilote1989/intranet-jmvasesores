<?php
class guardarCupones extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");			
		$test = (array) $this->request;
		$only_foo = array();
		foreach ($test as $key => $value) {
		    if (strpos($key, 'numeroCupon-') === 0) {
		        $only_foo[$key] = $value;
		        $actual = substr($key,12);
		        $cupon=new Cupon();
				$cupon->setFechaVencimiento($test["vencimiento-".$actual],"DATE");
				$cupon->setMonto($test["monto-".$actual]);
				$cupon->setNumeroCupon($test["numeroCupon-".$actual]);
				$cupon->setIdPoliza($this->request->idPoliza);
				$dbLink=&FrontController::instance()->getLink();
				$dbLink->next_result();
				$cupon->storeIntoDB();
				$dbLink->next_result();
		    }
		}
		//print_r($only_foo);
		$response["respuesta"]="SUCCESS";
		echo json_encode($response);
		
	}
}
?>
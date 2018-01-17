<?php
class revisarNumeroPoliza extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = false;
		//if($this->request->numeroPoliza && $this->request->cia){
			$polizas=Fabrica::getAllFromDB("Poliza",array("numeroPoliza = '" . $this->request->numeroPoliza . "'","idCompania = '" . $this->request->cia . "'", "estado = '1'"));
			if(count($polizas)>0){
				$valid = false;
				if($this->request->poliza){
					foreach($polizas as $poliza){
						if($poliza->getId()==$this->request->poliza){
							$valid = true;
						}						
					}
				}
			}else{
				$valid = true;
			}		
		//}
		echo json_encode($valid);
	}
}
?>
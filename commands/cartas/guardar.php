<?php
class guardar extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.Carta");
		$usuario=$this->getUsuario();
		$response["respuesta"]="OLD";
		if($this->request->idCarta){
			$carta = Fabrica::getFromDB("Carta",$this->request->idCarta);
			$carta->setCarta(htmlspecialchars($this->request->texto, ENT_QUOTES, 'UTF-8'));	
			$carta->setDetalle($this->request->detalle);		
			$carta->storeIntoDB();			
			//guardando carta que esta creada
			$response["respuesta"]="OLD";
			echo json_encode($response);
		}else if($this->request->idPoliza){
			//guardando nueva carta
			$carta = new Carta();
			$carta->setCarta(htmlspecialchars($this->request->texto, ENT_QUOTES, 'UTF-8'));
			$carta->setIdPoliza($this->request->idPoliza);			
			$carta->setDetalle($this->request->detalle);				
			$carta->setFecha(date("Y-m-d"));		
			$carta->storeIntoDB();			
			$dbLink=&FrontController::instance()->getLink();			
			$id=$dbLink->insert_id;
			$response["respuesta"]="NEW";
			$response["ids"]=$id;
			echo json_encode($response);
		}
	}
}
?>
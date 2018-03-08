<?php
class busquedaEspecial extends sessionCommand{
	function execute(){	
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->aviso){
			$response["respuesta"]="FAIL";
			$response["id"]="0";
			$response["m"]="No se encontro ninguna poliza con ese Aviso de Cobranza.";
			$cobro=Fabrica::getAllFromDB("Cobro",array("avisoDeCobranza LIKE'" . $this->request->aviso . "'"));
			$response["cuenta"]=count($cobro);
			$y=0;
			$tipo=null;
			foreach($cobro as $tempcobro){
				$poliza=Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $tempcobro->getId() . "'", "estado = '1'"));
				if(count($poliza)==1){
					$pol=$poliza[0]->getId();
					$y++;	
					$tipo="pol";
				}else if(count($poliza)>1){
					$y=$y+2;
				}
				$endoso=Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $tempcobro->getId() . "'"));
				if(count($endoso)==1){
					$pol=$endoso[0]->getIdPoliza();
					$y++;	
					$tipo="end";
				}else if(count($endoso)>1){
					$y=$y+2;
				}
			}
			if($y==0){
				$response["m"]="No se encontro ningun documento con ese Aviso de Cobranza.";
			}else if($y==1){
				$response["respuesta"]="SUCCESS";
				$response["id"]=$pol;
				if($tipo=="pol"){
					$response["tipo"]="pol";
					$response["m"]="Se encontro una poliza, redireccionando...";
				}else{
					$response["tipo"]="end";
					$response["m"]="Se encontro un endoso, redireccionando a la poliza...";
				}
			}else if($y>1){
				$response["m"]="Se encontro mas de 1 documento con ese Aviso de Cobranza.";	
			}
			echo json_encode($response);
		}else{
			$this->addLayout("ace");
			$this->processTemplate("polizas/busquedaEspecial.html");
		}
		
	}
}
?>
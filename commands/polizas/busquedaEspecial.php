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
			if(count($cobro)=="1"){
				$poliza=Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $cobro[0]->getId() . "'"));
				if(count($poliza)=="1"){
					//Se encontro una poliza
					if($poliza[0]->getEstado()=="0"){
						//poliza eliminada
						$response["m"]="No se encontro ninguna poliza con ese Aviso de Cobranza.";	
					}else{
						$response["respuesta"]="SUCCESS";
						$response["id"]=$poliza[0]->getId();
						$response["m"]="Se encontro una poliza, redireccionando...";	
					}
				}else{
					//Debe ser un endoso
					$endoso=Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $cobro[0]->getId() . "'"));
					if(count($endoso)=="1"){
						//Se encontro un endoso
						$response["respuesta"]="SUCCESS";
						$response["id"]=$endoso[0]->getIdPoliza();
						$response["m"]="Se encontro un endoso, redireccionando a la poliza...";
					}else{
						//Error
						$response["m"]="Error desconocido.";
						
					}
				}
			}elseif(count($cobro)=="0"){
				$response["m"]="No se encontro ninguna poliza con ese Aviso de Cobranza.";
			}else{
				$response["m"]="Se encontro mas de 1 poliza con ese Aviso de Cobranza.";
			}
			echo json_encode($response);
		}else{
			$this->addLayout("ace");
			$this->processTemplate("polizas/busquedaEspecial.html");
		}
	}
}
?>
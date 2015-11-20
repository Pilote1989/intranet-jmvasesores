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
				$response["respuesta"]="SUCCESS";
				$response["id"]=$poliza[0]->getId();
				$response["m"]="Se encontro una poliza, redireccionando...";
			}elseif(count($cobro)=="0"){
				$response["m"]="No se encontro ninguna poliza con ese Aviso de Cobranza";
			}else{
				$response["m"]="Se encontro mas de 1 poliza con ese Aviso de Cobranza.";
			}
			echo json_encode($response);
		}else{
			$this->addLayout("admin");
			$this->processTemplate("polizas/busquedaEspecial.html");
		}
	}
}
?>
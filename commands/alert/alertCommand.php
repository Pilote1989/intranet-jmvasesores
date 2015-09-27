<?php
class alertCommand extends SessionCommand{
	function execute(){
		$alertas=Fabrica::getFromSession("Alerts");
		if(sizeof($alertas)){
			$i=0;
			$backupAlertas=array();
			foreach($alertas as $alerta){
				if($alerta->group==$this->request->group){
					$loopAlertas[$i]["tipo"]=$alerta->getAlertType();
					$loopAlertas[$i]["contenido"]=$alerta->getContent();
					$i++;
				}else{
					$backupAlertas[]=$alerta;
				}
			}
			new SessionObject("Alerts",$backupAlertas);
			if(sizeof($loopAlertas)){
				$this->addLoop("alertas",$loopAlertas);
				$this->processTemplate("alert/alertTemplate.html");
			}
		}
	}
}
?>
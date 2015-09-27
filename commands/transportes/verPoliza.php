<?
class verPoliza extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->id){
			$solicitud = Fabrica::getFromDB("Transporte", $this->request->id);
			if($this->checkAccess("verSolicitudes", true)){
				$this->addVar("idTransporte",$solicitud->getId());
				$this->addVar("direccion",$solicitud->getDireccion());
				$persona = Fabrica::getFromDB("Persona",$solicitud->getIdPersona());
				$this->addVar("contratante",$persona->getNombres() . " " . $persona->getApellidoPaterno() . " " . $persona->getApellidoMaterno());
				$this->addVar("ruc",$persona->getRuc());
				$this->addVar("tipoDeMercaderia",$solicitud->getTipoMercaderia());
				$this->addVar("descripcionMercaderia",$solicitud->getDescripcionMercaderia());
				$this->addVar("lugarOrigen",$solicitud->getLugarOrigen());
				$this->addVar("lugarDestino",$solicitud->getLugarDestino());
				$this->addVar("fechaSalida",$solicitud->getFechaSalida("DATE"));
				$this->addVar("paisOrigen",$solicitud->getPaisOrigen());
				$this->addVar("fechaLlegada",$solicitud->getFechaLlegada("DATE"));
				$this->addVar("medioTransporte",$solicitud->getMedioTransporte());
				$this->addVar("proovedor",$solicitud->getProovedor());
				$this->addVar("trasbordo",$solicitud->getTrasbordo());
				$this->addVar("nombreBuque",$solicitud->getNombreBuque());
				$this->addVar("tipoContenedor",$solicitud->getTipoContenedor());
				$this->addVar("tipoEmbalaje",$solicitud->getTipoEmbalaje());
				$this->addVar("valorFob",$solicitud->getValorFob());
				$this->addVar("valorFlete",$solicitud->getValorFlete());
				$this->addVar("sobreseguro",$solicitud->getSobreseguro());
				$this->addVar("valorCif",$solicitud->getValorCif());
				$this->addVar("observaciones",$solicitud->getObservaciones());
				$this->addVar("estado",$solicitud->getEstado());		
			}else{
				if($solicitud->getIdPersona() == $usuario->getId()){
					$this->addVar("idTransporte",$solicitud->getId());
					$this->addVar("direccion",$solicitud->getDireccion());
					$persona = Fabrica::getFromDB("Persona",$solicitud->getIdPersona());
					$this->addVar("contratante",$persona->getNombres() . " " . $persona->getApellidoPaterno() . " " . $persona->getApellidoMaterno());
					$this->addVar("ruc",$persona->getRuc());
					$this->addVar("tipoDeMercaderia",$solicitud->getTipoMercaderia());
					$this->addVar("descripcionMercaderia",$solicitud->getDescripcionMercaderia());
					$this->addVar("lugarOrigen",$solicitud->getLugarOrigen());
					$this->addVar("lugarDestino",$solicitud->getLugarDestino());
					$this->addVar("fechaSalida",$solicitud->getFechaSalida("DATE"));
					$this->addVar("paisOrigen",$solicitud->getPaisOrigen());
					$this->addVar("fechaLlegada",$solicitud->getFechaLlegada("DATE"));
					$this->addVar("medioTransporte",$solicitud->getMedioTransporte());
					$this->addVar("proovedor",$solicitud->getProovedor());
					$this->addVar("nombreBuque",$solicitud->getNombreBuque());
					$this->addVar("trasbordo",$solicitud->getTrasbordo());
					$this->addVar("tipoContenedor",$solicitud->getTipoContenedor());
					$this->addVar("tipoEmbalaje",$solicitud->getTipoEmbalaje());
					$this->addVar("valorFob",$solicitud->getValorFob());
					$this->addVar("valorFlete",$solicitud->getValorFlete());
					$this->addVar("sobreseguro",$solicitud->getSobreseguro());
					$this->addVar("valorCif",$solicitud->getValorCif());
					$this->addVar("observaciones",$solicitud->getObservaciones());
					$this->addVar("estado",$solicitud->getEstado());	
				}else{
					$fc->redirect("?do=home");
				}
			}
		}
		if(!$this->request->html){
			$this->addLayout("admin");
		}else{
			$this->addBlock("html");
		}
		$this->processTemplate("transportes/verPoliza.html");
	}
}
?>
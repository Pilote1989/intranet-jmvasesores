<?php
class editarDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		include("lib/Utilidades.php");
		$utilidades = new Utilidades();
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		$persona = Fabrica::getFromDB('Persona', $usuario->getId());
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		//para escribir datos basicos
		$this->addVar("readonly", "readonly");
		//variables de formulario
		if($this->request->confirmed == 1){
			$this->addVar("contratante", $this->request->contratante);
			$this->addVar("direccion", $this->request->direccion);
			$this->addVar("ruc", $this->request->ruc);
			$this->addVar("tipoDeMercaderia", $this->request->tipoDeMercaderia);
			$this->addVar("descripcionMercaderia", $this->request->descripcionMercaderia);
			$this->addVar("lugarOrigen", $this->request->lugarOrigen);
			$this->addVar("fechaSalida", $this->request->fechaSalida);
			$this->addVar("paises", $utilidades->generarPaises($this->request->paisOrigen));
			//$this->addVar("paisOrigen", $this->request->pais);
			$this->addVar("lugarDestino", $this->request->lugarDestino);
			$this->addVar("fechaLlegada", $this->request->fechaLlegada);
			//$this->addVar("medioTransporte", $this->request->medioTransporte);
			$this->addVar("proovedor", $this->request->proovedor);
			$this->addVar("nombreBuque", $this->request->nombreBuque);
			//$this->addVar("tipoContenedor", $this->request->tipoContenedor);
			$this->addVar("tipoEmbalaje", $this->request->tipoEmbalaje);
			if($this->request->trasbordo == "Si"){
				$this->addVar("checked", ' checked="checked" ');
			}else{
				$this->addEmptyVar("checked");
			}
			$this->addVar("valorFob", $this->request->fob);
			$this->addVar("valorFlete", $this->request->flete);
			$this->addVar("aduana", $this->request->aduana);
			$this->addVar("sobreseguro", $this->request->sobreseguro);
			$this->addVar("observaciones", $this->request->observaciones);
			$this->addEmptyVar("medioTransporteVacio");
			$this->addEmptyVar("medioTransporteTerrestre");
			$this->addEmptyVar("medioTransporteMaritimo");
			$this->addEmptyVar("medioTransporteAereo");	
			$this->addEmptyVar("contenedorVacio");
			$this->addEmptyVar("contenedorExclusivo");
			$this->addEmptyVar("contenedorConsolidado");
			$this->addVar("contenedor" . $this->request->tipoContenedor, "selected");	
			$this->addVar("medioTransporte" .$this->request->medioTransporte, "selected");		
		}else{		
			$this->addVar("contratante",$usuario->getNombres() . " " . $usuario->getApellidoPaterno() . " " . $usuario->getApellidoMaterno());
			$this->addVar("direccion", $usuario->getDireccion());
			$this->addVar("ruc", $usuario->getRuc());
			$this->addEmptyVar("tipoDeMercaderia");
			$this->addEmptyVar("descripcionMercaderia");
			$this->addEmptyVar("lugarOrigen");
			$this->addEmptyVar("fechaSalida");
			$this->addEmptyVar("lugarDestino");
			$this->addEmptyVar("fechaLlegada");
			$this->addEmptyVar("proovedor");
			$this->addEmptyVar("nombreBuque");
			$this->addEmptyVar("tipoEmbalaje");
			$this->addVar("valorFob", "0");
			$this->addVar("valorFlete", "0");
			$this->addVar("aduana", "0");
			$this->addVar("sumaAsegurada", "0");
			$this->addVar("sobreseguro", "10%");
			$this->addEmptyVar("observaciones");
			$this->addVar("medioTransporteVacio", "selected");
			$this->addEmptyVar("medioTransporteTerrestre");
			$this->addEmptyVar("medioTransporteMaritimo");
			$this->addEmptyVar("medioTransporteAereo");			
			$this->addVar("contenedorVacio", "selected");
			$this->addEmptyVar("contenedorExclusivo");
			$this->addEmptyVar("contenedorConsolidado");
			$this->addEmptyVar("checked");
			$this->addVar("paises", $utilidades->generarPaises());
		}
		$this->addVar("nombre", $usuario->getNombres()." ".$usuario->getApellidoPaterno());
		if(!$this->request->html){
			$this->addLayout("admin");
		}
		$this->processTemplate("transportes/editarDatosBasicos.html");
	}
}
?>
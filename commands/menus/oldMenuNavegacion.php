<?php
class oldMenuNavegacion extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$user = $this->getUsuario();
		$this->addEmptyVar("places");
		/*
		$this->addEmptyVar("inicio");
		$this->addEmptyVar("administracion");
		$this->addEmptyVar("personas.inscribirUsuario");
		$this->addEmptyVar("personas.verPersonas");
		$this->addEmptyVar("personas.cambiarClave");
		$this->addEmptyVar("polizas");
		$this->addEmptyVar("polizas.editarDatosBasicos");
		$this->addEmptyVar("polizas.verPolizas");
		$this->addEmptyVar("clientes");
		$this->addEmptyVar("clientes.editarDatosBasicos");
		$this->addEmptyVar("clientes.verClientes");
		$this->addEmptyVar("companias");
		$this->addEmptyVar("companias.editarDatosBasicos");
		$this->addEmptyVar("companias.verCompanias");
		$this->addEmptyVar("reportes");
		$this->addEmptyVar("reportes.generar");
		$this->addEmptyVar("reportes.historico");
		$this->addEmptyVar("solicitudes");
		$this->addEmptyVar("solicitudes.crearSolicitud");
		$this->addEmptyVar("solicitudes.verSolicitudes");*/
		if($this->request->doFalso == "personas.verPortada"){
			$places = array("Portada");
		}elseif($this->request->doFalso == "solicitudes.crearSolicitud"){
			$places = array("Solicitudes", "Crear Solicitud");
		}elseif($this->request->doFalso == "solicitudes.verSolicitudes"){
			$places = array("Solicitudes", "Ver Mis Solicitudes");
		}elseif($this->request->doFalso == "personas.inscribirUsuario"){
			$places = array("Administracion", "Inscribir Usuario");;
		}elseif($this->request->doFalso == "personas.verPersonas"){
			$places = array("Administracion", "Ver Personas");;
		}elseif($this->request->doFalso == "personas.verDatosBasicos"){
			$places = array("Administracion", "Ver Personas");;
		}elseif($this->request->doFalso == "personas.cambiarClave"){
			$places = array("Administracion", "Cambiar Clave");
		}elseif($this->request->doFalso == "polizas.ver"){
			$places = array("Polizas", "Ver Polizas");
		}elseif($this->request->doFalso == "polizas.editarDatosBasicos"){
			$places = array("Polizas", "Editar Polizas");
		}elseif($this->request->doFalso == "polizas.verPolizas"){
			$places = array("Polizas", "Ver Polizas");
		}elseif($this->request->doFalso == "clientes.verDatosBasicos"){
			$places = array("Clientes", "Ver Clientes");
		}elseif($this->request->doFalso == "clientes.editarDatosBasicos"){
			$places = array("Clientes", "Editar Clientes");
		}elseif($this->request->doFalso == "clientes.verClientes"){
			$places = array("Clientes", "Ver Clientes");
		}elseif($this->request->doFalso == "companias.verDatosBasicos"){
			$places = array("Companias", "Ver Companias");
		}elseif($this->request->doFalso == "companias.editarDatosBasicos"){
			$places = array("Companias", "Editar Companias");
		}elseif($this->request->doFalso == "companias.verCompanias"){
			$places = array("Companias", "Ver Companias");
		}elseif($this->request->doFalso == "reportes.generar"){
			$places = array("Reportes", "Generar");
		}elseif($this->request->doFalso == "reportes.historico"){
			$places = array("Reportes", "Ver Historico");
		}
		$menu = "";
		if(count($places)>0){
			$i=0;
			foreach ($places as $place) {
				$i++;
				if($i != count($places)){
					$menu .= "<li>" . $place . "&nbsp;&nbsp;</li>";
				}else{
					$menu .= "<li class='active'>" . $place . "</li>";
				}
			}
			$this->addVar("places", $menu);
		}
		$this->processTemplate("menus/oldMenuNavegacion.html");
	}
}
?>
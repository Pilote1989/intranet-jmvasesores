<?php
class inscribirUsuario extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);		
		$fc->import("lib.Persona");
		$fc->import("lib.PersonaEnPerfil");
		$usuario=$this->getUsuario();
		$this->checkAccess("crearUsuario");
		if($this->checkAccess("crearUsuario")){
			if($this->request->usuario){
				$personas = Fabrica::getAllFromDB("Persona", array("userName = '" . $this->request->usuario ."'"));
				if(sizeof($personas)==1){
					$this->addBlock("usuario");
					$this->addLayout("admin");
					$this->processTemplate("personas/inscribirUsuario.html");
				}else{
					$personas = Fabrica::getAllFromDB("Persona", array("mail = '" . $this->request->correo ."'"));
					if(sizeof($personas)==1){
						$this->addBlock("correo");
						$this->addLayout("admin");
						$this->processTemplate("personas/inscribirUsuario.html");
					}elseif($this->request->clave != $this->request->repetirClave){
						echo $this->request->clave . " - " . $this->request->repetirClave;
						$this->addBlock("clave");
						$this->addLayout("admin");
						$this->processTemplate("personas/inscribirUsuario.html");
					}else{
						//creo el usuario
						$persona=new Persona();
						$persona->setNombres($this->request->nombres);
						$persona->setApellidoPaterno($this->request->apellidoPaterno);
						$persona->setRuc($this->request->ruc);
						$persona->setMail($this->request->correo);
						$persona->setMail($this->request->correo);
						$persona->setComision($this->request->comision);
						$persona->setPassword(md5($this->request->clave));
						$persona->setUserName($this->request->usuario);
						$persona->storeIntoDB();
						$dbLink=&FrontController::instance()->getLink();
						$id=$dbLink->insert_id;					
						$persona->agregarPerfil("2");
						$fc->redirect("?do=personas.verDatosBasicos&idPersona=".$id);
					}				
				}
			}else{
					$this->addLayout("admin");
					$this->processTemplate("personas/inscribirUsuario.html");	
			}
		}else{
			$fc->redirect("?do=personas.verPortada");
		}		
	}
}
?>
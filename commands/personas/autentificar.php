<?php
class autentificar extends SessionCommand{
	function execute(){
		$fc=&FrontController::instance();
		$fc->import("lib.Persona");
			
		// Si es login de Administracion
		if(!isset($_GET['login'])){
			if(Persona::login($this->request->mail, $this->request->password)){
				$usuario=$this->getUsuario();
				//Si no tiene acceso
				if(isset($_SESSION["URL_ACTUAL"])){
					$fc->redirect($_SESSION["URL_ACTUAL"]);
				}else{
					//if(!Persona::permission(verConstrucciones,$usuario->getId())){
					if($this->request->loginModal){
						// Si se hace el login desde el thickbox solo necesitamos cerrarlo
						echo "
						<script>
							tb_remove();
							intLogin = setInterval(\"comprobarSesion()\",30000);
						</script>
						";
					}else{
						$fc->redirect("?do=personas.verPortada");
					}
				}
			}else{
					$fc->redirect("?do=login&error=2");
			}
		}else{
			$fc->redirect("?do=login&error=1");			
		}
	}
}
?>
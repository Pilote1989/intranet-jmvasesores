<?php
include_once("eocene/BaseCommand.php");
abstract class SessionCommand extends BaseCommand{

	public function __construct($getQuery){
		parent::__construct($getQuery); // Construir BaseCommand
		if(isset($this->request->idSession)){
			session_id(strip_tags($this->request->idSession));
		}
		//$sessionName=Nombre valido para sesion, unico para cada instancia del software
		$sessionName=md5(__FILE__);
		if( isset($this->fc->sessionName) ){
			$sessionName=$this->fc->sessionName;
		}
		session_name($sessionName);
		session_start();
		$this->checkLogin();
	}
	function setUsuario($usuario){
		new SessionObject("visitante",$usuario);
	}
	//metodo que retorna el usuario que esta operando el sistema
	public function getUsuario(){
		if($user=Fabrica::getFromSession("visitante")){
			return $user;
		}
                //header("Location: ./admin");
		return false;
	}
	public function checkAccess($permiso=null,$noKill=null){
		$fc=FrontController::instance();
		$fc->import("lib.Persona");
	
		//$_SESSION["URL_ACTUAL"] = $_SERVER["REQUEST_URI"];
		
		if((!$user=self::getUsuario()) && (!$noKill)){
			echo "No tienes permisos para acceder a esta página 1
			<div>
			<a href='./admin'>Volver a entrar</a>
			</div>";
			
			/*echo "
			<script>
				tb_show('Iniciar Sesión','?do=personas.loginModal','')
			</script>
			";*/
			
			exit;
		}elseif(!$user=self::getUsuario()){ // Sacar el error de no existe idPersona en user
			echo "No tienes permisos para acceder a esta página 2
			<div>
			<a href='./admin'>Volver a entrar</a>
			</div>";
			
			/*echo "
			<script>
				tb_show('Iniciar Sesión','?do=personas.loginModal','')
			</script>
			";*/
			
			
			exit;
		}else{
			if($permiso && !Persona::permission($permiso,$user->getIdPersona())){
				if($noKill){
					//echo '<div>false</div>';
					return false;
				}
				else{
					//echo '<div>false: '.$noKill.'</div>';
					echo "No tienes permisos para acceder a esta página 3
					<div>
					<a href='./admin'>Volver a entrar</a>
					</div>";
					exit;
				}
			}
		}
		return true;
	}
	public function checkPublicAccess(){
		if(!$user=self::getUsuario()){
			return false;
		}else{
			return true;
		}
	}
	public function checkUnidad($idActividad,$tipo="Actividad"){
		$fc=FrontController::instance();
		$fc->import("lib.Persona");
		
		if($actividad=Fabrica::getFromDB($tipo, $idActividad)){
			$user=self::getUsuario();
			$unidades=$user->getUnidadesOrganizacionales();
			;
			if(in_array($actividad->getIdUnidadOrganizacional(),$unidades)){
				if(count($user->getPerfiles()) == 1 && $tipo == "Actividad"){
					$perfiles = $user->getPerfiles();
					$perfil = $perfiles[0];
					
					if($perfil->getRestriccion() == "Actividad"){
						$peps = Fabrica::getAllFromDB("PersonaEnPerfil", array("idPersona=".$user->getId(), "idPerfil=".$perfil->getId()));
						$pep = $peps[0];
						
						if($idActividad == $pep->getIdRestriccion()){
							return true;
						}else{
							echo "No tienes permisos para acceder a esta página 4
							<div>
							<a href='./admin'>Volver a entrar</a>
							</div>";
							exit;					
						}
					}else{
						return true;
					}
				}else{
					return true;
				}
			}
			else{
				echo "No tienes permisos para acceder a esta página 5
				<div>
				<a href='./admin'>Volver a entrar</a>
				</div>";
				exit;
			}
		}
		else{
			echo "Error";
			exit;
		}
	}

    // Función para determinar si un idUnidadOrganizacional esta dentro de los permisos
    // del usuario
    public function checkUnidadOrganizacional($idUnidadOrganizacional) {
        $usuario = self::getUsuario();
        $unidades = $usuario->getUnidadesOrganizacionales();

        if (in_array($idUnidadOrganizacional, $unidades)) {
            return true; 
        }
        else {
            echo "No tienes permisos para acceder a esta página 6
            <div>
            <a href='./admin'>Volver a entrar</a>
            </div>";
            exit;
        }
    }


	// Funcion para determinar la Terminación de las Tablas Paralelas de Chile y Latinoamérica
	public function getTerminacionLT(){
		$user=self::getUsuario();
		// Si es cualquier Pais menos chile
		if(Fabrica::getFromDB("UnidadOrganizacional", $user->getIdUnidadOrganizacional())->getIdPais() != 7){
			return "LT";
		}else{
			return "";
		}
	}
	
	// Funcion que devuelve todas los id's de Actividades, Mesas de Trabajo, etc a las que el usuario tiene acceso
	public function checkRestriccion($tabla){
		$user=self::getUsuario();
		$perfiles = $user->getPerfiles();
		$arregloRestriccion = array();
		$arregloRestriccion[] = 0;
		
		foreach($perfiles as $perfil){
			// Solo el perfil de Observador puede ver todos los presupuesto asociados
			if($perfil->getNombre() == "Observador Presupuestos" && $tabla == "PresupuestoInstancia"){
				$arregloRestriccion = array();
				break;
			}elseif(substr_count($perfil->getRestriccion(), $tabla)){
				$personaPerfiles =  Fabrica::getAllFromDB("PersonaEnPerfil", array("idPersona = ".$user->getId(), "idPerfil=".$perfil->getId(), "idRestriccion NOT IN (".implode(",", $arregloRestriccion).")"));
				$personaPerfil = $personaPerfiles[0];
				
				if($personaPerfil->getIdRestriccion()){
					$arregloRestriccion[] = $personaPerfil->getIdRestriccion();
				}
			}
		}
		
		if(count($arregloRestriccion) == 1){
			$arregloRestriccion = array();
		}
		
		return $arregloRestriccion;
	}
	
	// Devuelve un filto SQL para la restriccion de busdqueda de inscripciones
	public function getFiltroRestriccion($id, $tabla){
		$user=self::getUsuario();
		$perfiles = $user->getPerfiles();
		$filtro = "";
		
		foreach($perfiles as $perfil){
			if(substr_count($perfil->getRestriccion(), $tabla)){
				if(Fabrica::getAllFromDB("PersonaEnPerfil", array("idPersona = ".$user->getId(), "idPerfil=".$perfil->getId(), "idRestriccion=".$id))){
					if($tabla == "Actividad" && $perfil->getRestringidoRol()){
						if($inscripciones = Fabrica::getAllFromDB("Inscripcion", array("idActividad=".$id, "idPersona=".$user->getId(), "rol <> ''"))){
							$inscripcion = $inscripciones[0];
							
							if($inscripcion->getIdEscuelaRol()){
								$filtro = "idEscuelaRol = ".$inscripcion->getIdEscuelaRol();
							}elseif($inscripcion->getIdLocalidadRol()){
								$localidad = Fabrica::getFromDB("Localidad", $inscripcion->getIdLocalidadRol());
								$filtro = "idLocalidadRol IN (".implode(",", $localidad->getArbol()).")";
							}
						}
					}
				}
			}
		}
		
		return $filtro;
	}
	
	
	//alias para BaseCommand::processTemplate, setea variables de sesion
	public function processTemplate($templateName){
		$usr=$this->getUsuario();
		$this->addVar("visitante",$usr);
		$this->addVar("sessionId",session_id());
		if(Fabrica::getFromSession("integer","modoEdicion")){
			$this->addBlock("modoEdicion");
		}
		parent::processTemplate($templateName);
	}
	protected function checkLogin(){
		/// TODO
		/// controlar que usuario visitante esté logueado
		return true;
	}
	///Reimplementación de addLoop y addVar para objetos DBObject
	function addVar($key,$value){
		if(is_array($value)){
			$objects=$value;
		}else{
			$objects=array($key=>$value);
		}
		$className=null;
		foreach($objects as $o){
			if(is_object($o)){
				if(get_parent_class($o)!="DBObject"){
					break;
				}elseif(!$className){
					$className=get_class($o);
					$extraAttrib=array();
					if(isset($o->extraAttrib)){
						$extraAttrib=$o->extraAttrib;
					}
				}elseif($className!=get_class($o)){
					//array de objetos de distinta clase...
					return false;
				}
			}else{
				break;
			}
		}
		if(!$className){
			return parent::addVar($key,$value);
		}else{
			$atributos=DescripcionTablas::getFields($className);
			$atributos[]="id";
			$atributos=array_merge($atributos,$extraAttrib);
			foreach($objects as $ko=>$o){
				foreach($atributos as $a){
					$metodo="get".ucfirst($a);
					$var[$a]=$o->$metodo();
				}
				$this->addVar($ko,$var);
			}
		}
	}
	function addLoop($key,$value){
		if(!is_array($value)){
			echo "SessionCommand: Error en parametros de médodo SessionCommand::addLoop (".$key.")";
			return false;
		}
		if(!sizeof($value)){
			return parent::addLoop($key,$value);
		}
		$isDBObject=true;
		$sameClass=true;
		$extraAttrib=array();
		unset($className);
		foreach($value as $v){
			if(!isset($className)){
				$className=get_class($v);
			}
			if(get_parent_class($v)=="DBObject"){
				if(isset($v->extraAttrib)){
					$extraAttrib=$v->extraAttrib;
				}
			}else{
				$isDBObject=false;
				break;
			}
			if($className!=get_class($v)){
				$sameClass=false;
				echo "SessionCommand: Error en parametros de médodo SessionCommand::addLoop (".$key.")";
				return false;
			}
		}
		if(!$isDBObject){
			return parent::addLoop($key,$value);
		}
		$i=0;
		$aux=new $className();
		foreach($value as $v){
			$atributos=array_merge(DescripcionTablas::getFields($aux->tableName),array("id"),$extraAttrib);
			foreach($atributos as $at){
				$method="get".ucfirst($at);
				$atValue=$v->$method();
				$loopVars[$i][$at]=$atValue;
			}
			$loopVars[$i]["loopCounter"]=$i+1;
			$i++;
		}
		return parent::addLoop($key,$loopVars);
	}	
	function queryResult($query){
		$fc=FrontController::instance();
		$dbLink=&$fc->getLink();
		
		if($result=$dbLink->query($query)){
			while($row=$result->fetch_array()){
				//echo '<div>'.($row).'</div>';
				if($row[0]!=NULL){
					return($row[0]);
				}
			}
			return null;
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}
	}

    //
    // Manejo de errores
    //
    function displayError($tipo, $informacion) {
        $error = new Error();
        $error->display($tipo, $informacion);
    }

    //
    // Validaciones
    //

    function isSelect($valor) {
        return $valor && $valor != "null";
    }

    function isNumero($valor) {
        return is_numeric($valor);
    }

    function isArreglo($valor) {
        return is_array($valor);
    }

    function isFecha($valor) {
        return $valor;
    }
}
?>

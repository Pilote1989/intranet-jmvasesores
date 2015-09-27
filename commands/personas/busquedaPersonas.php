<?php
class busquedaPersonas extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaPersonas"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaPersonas"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		$where=array();
		
		if($this->request->nombre){
			$_SESSION["busquedaPersonas"]["nombre"]=$this->request->nombre;
			$where[]="Persona.nombres LIKE '%".$this->request->nombre."%'";
		}
		
		if($this->request->apellidoPaterno){
			$_SESSION["busquedaPersonas"]["apellidoPaterno"]=$this->request->apellidoPaterno;
			$where[]="Persona.apellidoPaterno LIKE '%".$this->request->apellidoPaterno."%'";
		}
		
		if($this->request->apellidoMaterno){
			$_SESSION["busquedaPersonas"]["apellidoMaterno"]=$this->request->apellidoMaterno;
			$where[]="Persona.apellidoMaterno LIKE '%".$this->request->apellidoMaterno."%'";
		}
		
		if($this->request->usuario){
			$_SESSION["busquedaPersonas"]["userName"]=$this->request->usuario;
			$where[]="Persona.userName LIKE '%".$this->request->usuario."%'";
		}
		
		if($this->request->correo){
			$_SESSION["busquedaPersonas"]["correo"]=$this->request->correo;
			$where[]="Persona.mail LIKE '%".$this->request->correo."%'";
		}
		
		$pPaginador = "";
		

		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$personas=array();

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				DISTINCT *
			FROM Persona
			".$whereCondition."
			LIMIT ".$minimoDePagina.", ".$limite."
		";

		$query=utf8_decode($query);		
		$link=&$this->fc->getLink();
		
		if($result=$link->query($query)){
			$countQuery="SELECT FOUND_ROWS() as total";
		
			if($countResult=$link->query($countQuery)){
				$row=$countResult->fetch_assoc();
				$num_rows=$row['total'];
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
			
			$listaPolizas=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaPolizas[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaPolizas as $persona){
			//$actual = Fabrica::getFromDB("Poliza", $persona["idPersona"]);
			$personas[$i]["nombre"] = $persona["nombres"] . " " . $persona["apellidoPaterno"] . " " . $persona["apellidoMaterno"];
			$personas[$i]["correo"] = $persona["mail"];
			$personas[$i]["usuario"] = $persona["userName"];
			$personas[$i]["idPersona"] = $persona["idPersona"];
			if($persona["habilitado"] == "1"){
				$personas[$i]["estado"] = '<span class="label label-success">Habilitado</span>';
			}else{
				$personas[$i]["estado"] = '<span class="label label-warning">Deshabilitado</span>';
			}
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=personas.busquedaPersonas".$pPaginador,"divBusquedaPersonas", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("personas",$personas);

		$this->processTemplate("personas/busquedaPersonas.html");
		
	}
}
?>

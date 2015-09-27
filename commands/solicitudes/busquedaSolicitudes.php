<?php
class busquedaSolicitudes extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		$this->fc->import("lib.Persona");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaSolicitudes"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaSolicitudes"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
		
		$_SESSION["busquedaSolicitudes"]["observaciones"] = $this->request->observaciones;

		if($this->request->observaciones){
			$where[]="observaciones LIKE '%".$this->request->observaciones."%'";
		}
		
		$_SESSION["busquedaSolicitudes"]["fechaInicio"]=$this->request->fechaInicio;
		
		if($this->request->fechaInicio){
			$dates=explode("/",$this->request->fechaInicio);
			$fechaInicio=$dates[2]."-".$dates[1]."-".$dates[0];
			$where[]="fechaPedido >= '".$fechaInicio."'";
		}
		
		$_SESSION["busquedaSolicitudes"]["fechaFin"]=$this->request->fechaFin;
		
		if($this->request->fechaFin){
			$dates=explode("/",$this->request->fechaFin);
			$fechaFin=$dates[2]."-".$dates[1]."-".$dates[0];
			$where[]="fechaPedido <= '".$fechaFin."'";
		}
		
		$_SESSION["busquedaSolicitudes"]["usuario"]=$this->request->usuario;
		

		$pPaginador = "";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}
		$tabla = null;
		//if($this->request->tipo == "Transporte"){
			$tabla = "Transporte";
		//}
		
		$solicitudes=array();
		if(!is_null($tabla)){
			$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT * , id" . $tabla . " as clave
				FROM " . $tabla. "
				".$whereCondition."
				ORDER BY
					fechaPedido DESC
				LIMIT ".$minimoDePagina.", ".$limite."
			";
			//echo '<div>'.$query.'</div>';
			
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
				
				$listaSolicitudes=array();
				$this->fc->import("lib.Paginador");
				
				while($row=$result->fetch_assoc()){
					$listaSolicitudes[]=$row;
				}
				
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$i=0;
			foreach($listaSolicitudes as $solicitud){
				$actual = Fabrica::getFromDB("Persona",$solicitud["idPersona"]);
				if(!$this->request->noU){
					if($this->request->usuario){
						$nombreU = $actual->getNombres() . " " . $actual->getApellidoPaterno() . " " . $actual->getApellidoMaterno();
						$pos = strpos($nombreU, $this->request->usuario);
						//echo $nombreU . "<br />";
						//echo $this->request->usuario . "<br />";						
						//echo "1 " . $pos . " 1";
						if ($pos !== false) {
							$solicitudes[$i]["contratante"] = $actual->getNombres() . " " . $actual->getApellidoPaterno() . " " . $actual->getApellidoMaterno();
							$solicitudes[$i]["tipo"] = $this->request->tipo;
							if($solicitudes[$i]["tipo"]=="Transporte"){
								$solicitudes[$i]["link"] = "?do=transportes.verPoliza&id=" . $solicitud["idTransporte"];
							}else{
								$solicitudes[$i]["link"] = "#";
							}
							$solicitudes[$i]["fechaPedido"]=date("d/m/Y",strtotime($solicitud["fechaPedido"]));
							if($solicitud["estado"]=="Pendiente"){
								$solicitudes[$i]["estado"]='<span class="label label-sm label-warning">Pendiente</span>';
							}elseif($solicitud["estado"]=="Procesada"){
								$solicitudes[$i]["estado"]='<span class="label label-sm label-success">Procesada</span>';
							}else{
								$solicitudes[$i]["estado"]=$solicitud["estado"];
							}
							//$solicitudes[$i]["estado"]=$solicitud["estado"];
							$i++;	
						}
					}else{
						$solicitudes[$i]["contratante"] = $actual->getNombres() . " " . $actual->getApellidoPaterno() . " " . $actual->getApellidoMaterno();
						$solicitudes[$i]["tipo"] = $this->request->tipo;
						if($solicitudes[$i]["tipo"]=="Transporte"){
							$solicitudes[$i]["link"] = "?do=transportes.verPoliza&id=" . $solicitud["idTransporte"];
						}else{
							$solicitudes[$i]["link"] = "#";
						}
						$solicitudes[$i]["fechaPedido"]=date("d/m/Y",strtotime($solicitud["fechaPedido"]));
						
						
							if($solicitud["estado"]=="Pendiente"){
								$solicitudes[$i]["estado"]='<span class="label label-sm label-warning">Pendiente</span>';
							}elseif($solicitud["estado"]=="Procesada"){
								$solicitudes[$i]["estado"]='<span class="label label-sm label-success">Procesada</span>';
							}else{
								$solicitudes[$i]["estado"]=$solicitud["estado"];
							}
							//$solicitudes[$i]["estado"]=$solicitud["estado"];						
						
						
						$i++;					
					}
				}else{
					if($actual->getId()==$usuario->getId()){
						$solicitudes[$i]["contratante"] = $actual->getNombres() . " " . $actual->getApellidoPaterno() . " " . $actual->getApellidoMaterno();
						$solicitudes[$i]["tipo"] = $this->request->tipo;
						if($solicitudes[$i]["tipo"]=="Transporte"){
							$solicitudes[$i]["link"] = "?do=transportes.verPoliza&id=" . $solicitud["idTransporte"];
						}else{
							$solicitudes[$i]["link"] = "#";
						}
						$solicitudes[$i]["fechaPedido"]=date("d/m/Y",strtotime($solicitud["fechaPedido"]));

							if($solicitud["estado"]=="Pendiente"){
								$solicitudes[$i]["estado"]='<span class="label label-sm label-warning">Pendiente</span>';
							}elseif($solicitud["estado"]=="Procesada"){
								$solicitudes[$i]["estado"]='<span class="label label-sm label-success">Procesada</span>';
							}else{
								$solicitudes[$i]["estado"]=$solicitud["estado"];
							}
							//$solicitudes[$i]["estado"]=$solicitud["estado"];


						$i++;				
					}
				}
			}
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=solicitudes.busquedaSolicitudes".$pPaginador,"divBusquedaSolicitudes", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("solicitudes",$solicitudes);

		$this->processTemplate("solicitudes/busquedaSolicitudes.html");
		
	}
}
?>

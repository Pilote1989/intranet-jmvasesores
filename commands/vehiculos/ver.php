<?php
class ver extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idVehiculo){
			$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
			$this->addVar("idVehiculo",$vehiculo->getId());
			$this->addVar("placa",$vehiculo->getPlaca());
			$this->addVar("tipo",$vehiculo->textoTipo());
			$this->addVar("marca",$vehiculo->textoMarca());
			$this->addVar("modelo",$vehiculo->textoModelo());
			$this->addVar("anio",$vehiculo->getAnio());
			$this->addVar("sumaAsegurada",$vehiculo->getSumaAsegurada());
			$this->addVar("gps",($vehiculo->getGps() ? "Si" : "No"));
			$this->addVar("motor",$vehiculo->getMotor());
			$this->addVar("chasis",$vehiculo->getChasis());
				
	
			$query="
				SELECT 
					vp.idVehiculo AS idVehiculo, 
					vp.idPoliza AS idPoliza,
					numeroPoliza,
					cli.nombre AS nombreCliente,
					com.nombre AS nombreCompania,
					com.sigla AS siglaCompania,
					p.inicioVigencia AS inicioVigencia,
					p.finVigencia AS finVigencia
				FROM
					VehiculoEnPoliza vp,
					Poliza p, Cliente cli, Compania com
				WHERE
					vp.idPoliza = p.idPoliza
					AND 
					cli.idCliente = p.idCliente
					AND
					com.idCompania = p.idCompania
					AND
						vp.idVehiculo = '" . $this->request->idVehiculo . "'
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
				
				while($row=$result->fetch_assoc()){
					$listaPolizas[]=$row;
				}
				
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}			
			$i=0;
			
			$polizas=array();
			foreach($listaPolizas as $poliza){
				$actual = Fabrica::getFromDB("Poliza", $poliza["idPoliza"]);
				$polizas[$i]["id"] = ++$i;
				$polizas[$i]["idVehiculo"] = $poliza["idVehiculo"];
				$polizas[$i]["idPoliza"] = $poliza["idPoliza"];
				$polizas[$i]["numeroPoliza"] = $poliza["numeroPoliza"];
				$polizas[$i]["nombreCliente"] = $poliza["nombreCliente"];
				$polizas[$i]["nombreCompania"] = $poliza["nombreCompania"];
				$polizas[$i]["siglaCompania"] = $poliza["siglaCompania"];
				$polizas[$i]["inicioVigencia"] = $actual->getInicioVigencia("DATE"); 
				$polizas[$i]["finVigencia"] = $actual->getFinVigencia("DATE"); 			
				$polizas[$i]["estado"] = $actual->estadoLabel();
			}
			
			$this->addVar("numPolizas",$i);
			$this->addLoop("polizas",$polizas);			

			
			$this->addLayout("ace");
			$this->processTemplate("vehiculos/ver.html");
		}
	}
}
?>
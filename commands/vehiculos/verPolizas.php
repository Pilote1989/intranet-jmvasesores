<?php
class verPolizas extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idVehiculo){
			$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
			$query="
				SELECT 
					vp.idVehiculo AS idVehiculo, 
					vp.idPoliza AS idPoliza,
					numeroPoliza,
					cli.nombre AS nombreCliente,
					com.nombre AS nombreCompania,
					com.sigla AS siglaCompania,
					p.inicioVigencia AS inicioVigencia,
					p.finVigencia AS finVigencia,
                    r.sigla as ramo
				FROM
					VehiculoEnPoliza vp,
					Poliza p, Cliente cli, Compania com, Ramo r
				WHERE
					vp.idPoliza = p.idPoliza
					AND 
					cli.idCliente = p.idCliente
					AND
					com.idCompania = p.idCompania
					AND
					r.idRamo = p.idRamo
					AND
						vp.idVehiculo = '" . $this->request->idVehiculo . "'
                ORDER BY 
                    p.inicioVigencia ASC 
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
				$polizas[$i]["ramo"] = $poliza["ramo"];
				$polizas[$i]["numeroPoliza"] = $poliza["numeroPoliza"];
				$polizas[$i]["nombreCliente"] = $poliza["nombreCliente"];
				$polizas[$i]["nombreCompania"] = $poliza["nombreCompania"];
				$polizas[$i]["siglaCompania"] = $poliza["siglaCompania"];
				$polizas[$i]["inicioVigencia"] = $actual->getInicioVigencia("DATE"); 
				$polizas[$i]["finVigencia"] = $actual->getFinVigencia("DATE"); 			
				$polizas[$i]["estado"] = $actual->estadoLabel();
			}
			$this->addLoop("polizas",$polizas);			
			$this->processTemplate("vehiculos/verPolizas.html");
		}
	}
}
?>
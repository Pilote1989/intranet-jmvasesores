<?php
class busqueda extends BaseCommand{
	function execute(){
		// -> Banner
		//getAllFromDB($className,$arrayFiltros=array(),$order=NULL,$limit=NULL,$queryPrint=NULL)
		$q = $this->request->q;
		$query="
			SELECT SQL_CALC_FOUND_ROWS
				DISTINCT r.nombre as ramo, r.sigla as sramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza, cia.nombre as cia, cia.sigla as scia, min(inicioVigencia) as minimoVig, max(finVigencia) as maxVig, count(*)
			FROM Poliza, Cliente c, Ramo r, Compania cia
			WHERE (Poliza.idCliente = c.idCliente) AND (r.idRamo = Poliza.idRamo) AND (cia.idCompania = Poliza.idCompania) AND (Poliza.estado = '1')
			AND numeroPoliza LIKE '%".$q."%'
			GROUP BY numeroPoliza, cia.sigla
			ORDER BY
				maxVig DESC
			LIMIT 0, 3
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
		if (count($listaPolizas)) {
			$this->addBlock("bloquePolizas");
			foreach($listaPolizas as $poliza){
				$polizas[$i]["id"] = $poliza["idPoliza"];	
				$polizas[$i]["numeroPoliza"] = $poliza["numeroPoliza"];
				$polizas[$i]["scia"] = $poliza["scia"];
				$polizas[$i]["sramo"] = $poliza["sramo"];
				$polizas[$i]["cliente"] = $poliza["contratante"];
				$i++;				
			}
			$this->addLoop("polizas", $polizas);
		}else{
			$this->addBlock("bloqueNoPolizas");
		}
		
		$listaVehiculos = Fabrica::getAllFromDB("Vehiculo",array("placa LIKE '%".$q."%'"),NULL,3);
		$i=0;
		if (count($listaVehiculos)) {
			$this->addBlock("bloqueVehiculos");
			foreach($listaVehiculos as $vehiculo){
				$vehiculos[$i]["id"] = $vehiculo->getId();
				$vehiculos[$i]["placa"] = $vehiculo->getPlaca();
				$vehiculos[$i]["modelo"] = $vehiculo->textoModelo();
				$vehiculos[$i]["marca"] = $vehiculo->textoMarca();
				$i++;				
			}
			$this->addLoop("vehiculos", $vehiculos);
		}else{
			$this->addBlock("bloqueNoVehiculos");
		}

		$search = explode(' ', $q);
		$searchResults = 'LIKE ';
		foreach ($search as $id => $word) {
			$searchResults .= "'%" . $word . "%'";
			$searchResults .= " OR nombre LIKE ";
		}
		$searchResults = rtrim($searchResults," OR nombre LIKE ");
		//echo $searchResults;
		$listaClientes = Fabrica::getAllFromDB("Cliente",array("nombre ".$searchResults),NULL,3);
		$i=0;
		if (count($listaClientes)) {
			$this->addBlock("bloqueClientes");
			foreach($listaClientes as $cliente){
				$clientes[$i]["id"] = $cliente->getId();
				$clientes[$i]["nombre"] = $cliente->getNombre();
				$i++;				
			}
			$this->addLoop("clientes", $clientes);
		}else{
			$this->addBlock("bloqueNoClientes");
		}
		
		
		$listaCobros=Fabrica::getAllFromDB("Cobro",array("avisoDeCobranza LIKE '%".$q."%'"),NULL,3);
		$i=0;
		if(count($listaCobros)){
			foreach($listaCobros as $cobro){
				$poliza=Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $cobro->getId() . "'"));
				if(count($poliza)=="1"){
					//Se encontro una poliza
					$avisos[$i]["aviso"] = $cobro->getAvisoDeCobranza();	
					$avisos[$i]["id"] = $poliza[0]->getId();	
					$avisos[$i]["numeroPoliza"] = $poliza[0]->getNumeroPoliza();
					$compania=Fabrica::getFromDB("Compania",$poliza[0]->getIdCompania());
					$ramo=Fabrica::getFromDB("Ramo",$poliza[0]->getIdRamo());
					$cliente=Fabrica::getFromDB("Cliente",$poliza[0]->getIdCliente());
					$avisos[$i]["scia"] = $compania->getSigla();
					$avisos[$i]["sramo"] = $ramo->getNombre();
					$avisos[$i]["cliente"] = $cliente->getNombre();
					$i++;				
				}else{
					//Debe ser un endoso
					$endoso=Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $cobro->getId() . "'"));
					if(count($endoso)>"1"){
						$poliza=Fabrica::getFromDB("Poliza",$endoso[0]->getIdPoliza());
						$avisos[$i]["aviso"] = $cobro->getAvisoDeCobranza();	
						$avisos[$i]["id"] = $poliza[0]->getId();	
						$avisos[$i]["numeroPoliza"] = $poliza[0]->getNumeroPoliza();
						$compania=Fabrica::getFromDB("Compania",$poliza[0]->getIdCompania());
						$ramo=Fabrica::getFromDB("Ramo",$poliza[0]->getIdRamo());
						$cliente=Fabrica::getFromDB("Cliente",$poliza[0]->getIdCliente());
						$avisos[$i]["scia"] = $compania->getSigla();
						$avisos[$i]["sramo"] = $ramo->getNombre();
						$avisos[$i]["cliente"] = $cliente->getNombre();
					$i++;				
					}
				}
			}
			if($i>0){
				$this->addBlock("bloqueAvisos");
				$this->addLoop("avisos", $avisos);	
			}else{
				$this->addBlock("bloqueNoAvisos");
			}
		}else{
			$this->addBlock("bloqueNoAvisos");
		}
		$this->processTemplate("busqueda.html");
	}
}
?>
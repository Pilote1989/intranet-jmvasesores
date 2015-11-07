<?php
class generaPantalla extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);	
		if($this->request->ase || $this->request->com || $this->request->ram){
			if($this->request->ase!=""){
				$where[]="rtf.idCliente = '".$this->request->ase."'";
				$this->addVar("ase",$this->request->ase);
				$asegurado = Fabrica::getFromDB("Cliente", $this->request->ase);
				$this->addVar("aseTexto",$asegurado->getNombre());	
				$this->addBlock("asegurado");
			}else{
				$this->addEmptyVar("ase");
			}
			if($this->request->ram!=""){
				$where[]="rtf.idRamo = '".$this->request->ram."'";
				$this->addVar("ram",$this->request->ram);
				$ramo = Fabrica::getFromDB("Ramo", $this->request->ram);
				$this->addVar("ramTexto",$ramo->getNombre());
				$this->addBlock("ramo");
			}else{
				$this->addEmptyVar("ram");
			}
			if($this->request->com!=""){
				$where[]="rtf.idCompania = '".$this->request->com."'";
				$this->addVar("com",$this->request->com);
				$compania = Fabrica::getFromDB("Compania", $this->request->com);
				$this->addVar("comTexto",$compania->getNombre());
				$this->addBlock("com");	
			}else{
				$this->addEmptyVar("com");
			}
			
			$where[]="rtf.idCliente = cli.idCliente";
			$where[]="rtf.idRamo = r.idRamo";
			$where[]="rtf.idCompania = c.idCompania";
			$where[]="rtf.idCobro = cob.idCobro";
			
			if(sizeof($where)){
				$whereCondition = " WHERE (" . implode(") AND (",$where) . ")";
			}
			$busquedaCliente = array();
			$query="
				SELECT SQL_CALC_FOUND_ROWS
					rtf.moneda AS moneda,
					rtf.idPoliza AS idPoliza,
					rtf.doc AS tipo,
					rtf.numeroPoliza AS numeroPoliza,
					rtf.idCliente AS idCliente,
					cli.nombre AS nombreCliente,
					rtf.idCompania  AS idCompania,
					c.sigla AS siglaCompania,
					c.nombre AS nombreCompania,
					rtf.idRamo AS idRamo,
					r.sigla AS siglaRamo,
					r.nombre AS nombreRamo,
					cob.avisoDeCobranza AS avisoDeCobranza,
					cob.primaNeta AS primaNeta,
					cob.totalFactura AS totalFactura,
					cob.comisionP AS comisionP,
					cob.comision AS comision,
					cob.idLiquidacion AS liq,
					rtf.inicioVigencia as inicioVigencia,
					rtf.finVigencia as finVigencia
				FROM reporteTodoF rtf, Compania c, Ramo r, Cliente cli, Cobro cob
				".$whereCondition."
				ORDER BY numeroPoliza, inicioVigencia
			";
			//echo "<br / >" . $query;
			
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
			$polizas = array();
			
			//falta moneda!
			foreach($listaPolizas as $poliza){
				//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);	
				if($poliza["moneda"]="Dolares"){
					$polizas[$i]["moneda"] = "DOL";
				}else if($poliza["moneda"]="Soles"){
					$polizas[$i]["moneda"] = "SOL";
				}else if($poliza["moneda"]="Euros"){
					$polizas[$i]["moneda"] = "EUR";
				}
				$polizas[$i]["idPoliza"] = $poliza["idPoliza"];		
				$polizas[$i]["tipo"] = $poliza["tipo"];		
				$polizas[$i]["numeroPoliza"] = $poliza["numeroPoliza"];	
				$polizas[$i]["nombreCliente"] = $poliza["nombreCliente"];
				$polizas[$i]["siglaRamo"] = $poliza["siglaRamo"];
				$polizas[$i]["nombreRamo"] = $poliza["nombreRamo"];
				$polizas[$i]["siglaCompania"] = $poliza["siglaCompania"];
				$polizas[$i]["avisoDeCobranza"] = $poliza["avisoDeCobranza"];
				$polizas[$i]["primaNeta"] = round($poliza["primaNeta"], 2);
				$polizas[$i]["comisionP"] = round($poliza["comisionP"], 2);
				$polizas[$i]["comision"] = round($poliza["comision"], 2);
				if($poliza["liq"]=""){
					$polizas[$i]["liq"] = "Pendiente";
				}else{
					$polizas[$i]["liq"] = "Pagada";
				}
				$polizas[$i]["totalFactura"] = round($poliza["totalFactura"], 2);
				$polizas[$i]["inicioVigencia"] = date("d/m/Y",strtotime($poliza["inicioVigencia"]));
				$polizas[$i]["finVigencia"] = date("d/m/Y",strtotime($poliza["finVigencia"]));
				$i++;				
			}			
			$this->addLoop("polizas", $polizas);

			if($this->request->vista!=1){
				$this->addLayout("adminAlone");
				$this->processTemplate("reportes/generaPantalla2.html");
			}else{
				//vista normal
				$this->processTemplate("reportes/generaPantalla.html");
			}
			
		}
	}
}
?>
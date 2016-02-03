<?php
class cedidasPendientes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		
		$query="
			SELECT c.idPersona AS idPersona, per.nombres AS nombres, 
			per.apellidoPaterno AS paterno, per.apellidoMaterno AS materno
			FROM reporteTodoF rtf, Cobro c, Persona per
			WHERE rtf.idCobro = c.idCobro
			AND c.idPersona = per.idPersona
			AND c.idLiquidacion IS NOT NULL 
			AND c.idCedida IS NULL 
			AND per.idPersona !=  '1'
			GROUP BY c.idPersona
			ORDER BY nombres
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
				$listaPersonas=array();
				while($row=$result->fetch_assoc()){
					$listaPersonas[]=$row;
				}
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$i = 0;
			$lista = array();
			$granTotalSoles = 0;
			$granTotalDolares = 0;
			$granTotalEuros = 0;
			$granComSoles = 0;
			$granComDolares = 0;
			$granComEuros = 0;
			$granComVenSoles = 0;
			$granComVenDolares = 0;
			$granComVenEuros = 0;
			foreach($listaPersonas as $persona){
				$totalSoles = 0;
				$totalDolares = 0;
				$totalEuros = 0;
				$comSoles = 0;
				$comDolares = 0;
				$comEuros = 0;
				$comVenSoles = 0;
				$comVenDolares = 0;
				$comVenEuros = 0;
				$lista[$i]["idLista"] = ++$i;
				$lista[$i]["persona"] = $persona["nombres"] . " " . $persona["paterno"] . " " . $persona["materno"];
				
				//Asegurado, Aviso de Cobranza, Póliza, Cia, Ramo, Prima, Comisión, Porc%, Comisión Cedida, cedida%
				$query="
				SELECT 
					cli.nombre AS cliente, 
					c.avisoDeCobranza AS aviso, 
					pol.idPoliza AS idPoliza, 
					pol.numeroPoliza AS poliza, 
					com.sigla AS comp, 
					r.sigla AS ramo, 
					rtf.moneda as moneda,
					c.primaNeta as primaNeta,
					c.comision AS comision, 
					c.comisionP AS comisionP, 
					c.comisionCedida AS comisionCedida,
					c.comisionCedidaP AS comisionCedidaP
				FROM reporteTodoF rtf, Cobro c, Persona per, Cliente cli, Ramo r, Compania com, Poliza pol
				WHERE rtf.idCobro = c.idCobro
				AND rtf.idPoliza = pol.idPoliza
				AND rtf.idCliente = cli.idCliente
				AND rtf.idRamo = r.idRamo
				AND rtf.idCompania = com.idCompania
				AND c.idPersona = per.idPersona
				AND c.idLiquidacion IS NOT NULL 
				AND c.idCedida IS NULL 
				AND per.idPersona =  '" . $persona["idPersona"] . "'
				ORDER BY cliente
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
					$listaCobros=array();
					while($row=$result->fetch_assoc()){
						$listaCobros[]=$row;
					}
				}else{
					printf("Error: %s\n", $link->error);
					return null;
				}
				
				$j = 0;
				$checkDolares = false;
				$checkSoles = false;
				$checkEuros = false;
				foreach($listaCobros as $cobro){
					//Asegurado, Aviso de Cobranza, Póliza, Cia, Ramo, Prima, Comisión, Porc%, Comisión Cedida, cedida%
					$lista[$i]["cobro"][$j]["idLista"]=++$j;
					$lista[$i]["cobro"][$j]["aviso"]=$cobro["aviso"];
					$lista[$i]["cobro"][$j]["poliza"]=$cobro["poliza"];
					$lista[$i]["cobro"][$j]["idPoliza"]=$cobro["idPoliza"];
					$lista[$i]["cobro"][$j]["ramo"]=$cobro["ramo"];
					$lista[$i]["cobro"][$j]["com"]=$cobro["comp"];
					$lista[$i]["cobro"][$j]["primaNeta"]=number_format($cobro["primaNeta"],2);
					$lista[$i]["cobro"][$j]["comisionP"]=$cobro["comisionP"];
					$lista[$i]["cobro"][$j]["comision"]=number_format($cobro["comision"],2);
					$lista[$i]["cobro"][$j]["comisionCedidaP"]=$cobro["comisionCedidaP"]."%";
					$lista[$i]["cobro"][$j]["comisionCedida"]=number_format($cobro["comisionCedida"],2);	
					if($cobro["moneda"]=="Dolares"){
						$lista[$i]["cobro"][$j]["moneda"]="USD";
						$totalDolares += $cobro["primaNeta"];
						$comDolares += $cobro["comision"];
						if($cobro["vendedor"]!="1"){
							$comVenDolares += $cobro["comisionCedida"];
						}
						$checkDolares = true;
					}else if($cobro["moneda"]=="Soles"){
						$lista[$i]["cobro"][$j]["moneda"]="SOL";
						$totalSoles += $cobro["primaNeta"];
						$comSoles += $cobro["comision"];
						if($cobro["vendedor"]!="1"){
							$comVenSoles += $cobro["comisionCedida"];
						}
						$checkSoles = true;
					}else if($cobro["moneda"]=="Euros"){
						$lista[$i]["cobro"][$j]["moneda"]="EUR";
						$totalEuros += $cobro["primaNeta"];
						$comEuros += $cobro["comision"];
						if($cobro["vendedor"]!="1"){
							$comVenEuros += $cobro["comisionCedida"];
						}
						$checkEuros = true;
					}
				}
				$lista[$i]["usd"]="";
				$lista[$i]["sol"]="";
				$lista[$i]["eur"]="";
				if($checkDolares){
					$temp='
					<tr>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    <td colspan="2" align="center">Total Dolares</td>
				    <td align="center">' . $totalDolares . '</td>
				    <td align="center">' . $comDolares . '</td>
				    <td align="center">' . $comVenDolares . '</td>
					</tr>';
					$lista[$i]["usd"] = $temp;
				}
				if($checkSoles){
					$temp='
					<tr>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    <td colspan="2" align="center">Total Soles</td>
				    <td align="center">' . $totalSoles . '</td>
				    <td align="center">' . $comSoles . '</td>
				    <td align="center">' . $comVenSoles . '</td>
					</tr>';
					$lista[$i]["sol"] = $temp;
				}
				if($checkEuros){
					$temp='
					<tr>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    <td colspan="2" align="center">Total Euros</td>
				    <td align="center">' . $totalEuros . '</td>
				    <td align="center">' . $comEuros . '</td>
				    <td align="center">' . $comVenEuros . '</td>
					</tr>';
					$lista[$i]["eur"] = $temp;
				}
				$granTotalSoles += $totalSoles;
				$granTotalDolares += $totalDolares;
				$granTotalEuros += $totalEuros;
				$granComSoles += $comSoles;
				$granComDolares += $comDolares;
				$granComEuros += $comEuros;
				$granComVenSoles += $comVenSoles;
				$granComVenDolares += $comVenDolares;
				$granComVenEuros += $comVenEuros;
			}
			
			$this->addVar("granTotalSoles",number_format($granTotalSoles,2));
			$this->addVar("granTotalDolares",number_format($granTotalDolares,2));
			$this->addVar("granTotalEuros",number_format($granTotalEuros,2));
			$this->addVar("granComSoles",number_format($granComSoles,2));
			$this->addVar("granComDolares",number_format($granComDolares,2));
			$this->addVar("granComEuros",number_format($granComEuros,2));
			$this->addVar("granComVenSoles",number_format($granComVenSoles,2));
			$this->addVar("granComVenDolares",number_format($granComVenDolares,2));
			$this->addVar("granComVenEuros",number_format($granComVenEuros,2));
			//print_r($lista);
			$this->addLoop("personas", $lista);
		if($this->request->vista=="1"){
			$this->addLayout("adminAlone");
			$this->processTemplate("reportes/cedidasPendientes2.html");
		
		}else{
			$this->addLayout("admin");
			$this->processTemplate("reportes/cedidasPendientes.html");
		}
	}
}
?>
<?php
class comisionesPendientes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		
		$query="
		SELECT rtf.idCliente as idCli, cli.nombre as nombre
		FROM reporteTodoF rtf, Cobro c, Cliente cli
		WHERE rtf.idCobro = c.idCobro
		AND  rtf.idCliente = cli.idCliente
		AND c.idLiquidacion IS NULL 
		AND rtf.anulada = 0 
		GROUP BY rtf.idCliente
		ORDER By nombre
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
				$listaClientes=array();
				while($row=$result->fetch_assoc()){
					$listaClientes[]=$row;
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
			foreach($listaClientes as $cliente){
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
				$lista[$i]["cliente"] = $cliente["nombre"];
				
				//P. Neta	Total Fac.	% COM	COM	Vendedor	% CED	CED
				$query="
				SELECT 	c.avisoDeCobranza as aviso, 
						rtf.numeroPoliza as poliza, 
						rtf.idPoliza as idPoliza, 
						rtf.doc as doc, 
						r.sigla as ramo, 
						com.sigla as comp,
						DATE_FORMAT(rtf.inicioVigencia,'%d/%m/%Y') as inicioVigencia,
						DATE_FORMAT(rtf.finVigencia,'%d/%m/%Y') as finVigencia,
						c.primaNeta as primaNeta,
						c.totalFactura as totalFactura,
						c.comisionP as comisionP,
						c.comision as comision,
						c.comisionCedidaP as comisionCedidaP,
						c.comisionCedida as comisionCedida,
						rtf.moneda as moneda,
						c.idPersona as vendedor
				FROM reporteTodoF rtf, Cobro c, Cliente cli, Ramo r, Compania com
				WHERE rtf.idCobro = c.idCobro
				AND  rtf.idCliente = cli.idCliente
				AND  rtf.idRamo = r.idRamo
				AND rtf.anulada = 0
				AND  rtf.idCompania = com.idCompania
				AND c.idLiquidacion IS NULL 
				AND cli.idCliente = '" . $cliente["idCli"] . "'
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
					$lista[$i]["cobro"][$j]["idLista"]=++$j;
					$lista[$i]["cobro"][$j]["aviso"]=$cobro["aviso"];
					$lista[$i]["cobro"][$j]["poliza"]=$cobro["poliza"];
					$lista[$i]["cobro"][$j]["idPoliza"]=$cobro["idPoliza"];
					$lista[$i]["cobro"][$j]["doc"]=$cobro["doc"];
					$lista[$i]["cobro"][$j]["ramo"]=$cobro["ramo"];
					$lista[$i]["cobro"][$j]["com"]=$cobro["comp"];
					$lista[$i]["cobro"][$j]["inicioVigencia"]=$cobro["inicioVigencia"];
					$lista[$i]["cobro"][$j]["finVigencia"]=$cobro["finVigencia"];
					$lista[$i]["cobro"][$j]["totalFactura"]=number_format($cobro["totalFactura"],2);
					$lista[$i]["cobro"][$j]["primaNeta"]=number_format($cobro["primaNeta"],2);
					$lista[$i]["cobro"][$j]["comisionP"]=$cobro["comisionP"];
					$lista[$i]["cobro"][$j]["comision"]=number_format($cobro["comision"],2);
					$vendedor = Fabrica::getFromDB("Persona",$cobro["vendedor"]);
					$lista[$i]["cobro"][$j]["vendedor"]=$vendedor->getNombres().' '.$vendedor->getApellidoPaterno().' '.$vendedor->getApellidoMaterno();
					if($cobro["vendedor"]=="1"){
						$lista[$i]["cobro"][$j]["comisionCedidaP"]="";
						$lista[$i]["cobro"][$j]["comisionCedida"]="";
					}else{
						$lista[$i]["cobro"][$j]["comisionCedidaP"]=$cobro["comisionCedidaP"]."%";
						$lista[$i]["cobro"][$j]["comisionCedida"]=number_format($cobro["comisionCedida"],2);						
					}
					if($cobro["moneda"]=="Dolares"){
						$lista[$i]["cobro"][$j]["moneda"]="USD";
						$totalDolares += $cobro["totalFactura"];
						$comDolares += $cobro["comision"];
						if($cobro["vendedor"]!="1"){
							$comVenDolares += $cobro["comisionCedida"];
						}
						$checkDolares = true;
					}else if($cobro["moneda"]=="Soles"){
						$lista[$i]["cobro"][$j]["moneda"]="SOL";
						$totalSoles += $cobro["totalFactura"];
						$comSoles += $cobro["comision"];
						if($cobro["vendedor"]!="1"){
							$comVenSoles += $cobro["comisionCedida"];
						}
						$checkSoles = true;
					}else if($cobro["moneda"]=="Euros"){
						$lista[$i]["cobro"][$j]["moneda"]="EUR";
						$totalEuros += $cobro["totalFactura"];
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
				    <td colspan="5">&nbsp;</td>
				    <td colspan="2" align="center">Total Dolares</td>
				    <td>&nbsp;</td>
				    <td align="center">' . number_format($totalDolares,2) . '</td>
				    <td align="center">' . number_format($comDolares,2) . '</td>
				    <td>&nbsp;</td>
				    <td align="center">' . number_format($comVenDolares,2) . '</td>
					</tr>';
					$lista[$i]["usd"] = $temp;
				}
				if($checkSoles){
					$temp='
					<tr>
				    <td colspan="5">&nbsp;</td>
				    <td colspan="2" align="center">Total Soles</td>
				    <td>&nbsp;</td>
				    <td align="center">' . number_format($totalSoles,2) . '</td>
				    <td align="center">' . number_format($comSoles,2) . '</td>
				    <td>&nbsp;</td>
				    <td align="center">' . number_format($comVenSoles,2) . '</td>
					</tr>';
					$lista[$i]["sol"] = $temp;
				}
				if($checkEuros){
					$temp='
					<tr>
				    <td colspan="5">&nbsp;</td>
				    <td colspan="2" align="center">Total Euros</td>
				    <td>&nbsp;</td>
				    <td align="center">' . number_format($totalEuros,2) . '</td>
				    <td align="center">' . number_format($comEuros,2) . '</td>
				    <td>&nbsp;</td>
				    <td align="center">' . number_format($comVenEuros,2) . '</td>
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
				
			$this->addLoop("clientes", $lista);
		if($this->request->vista=="1"){
			$this->addLayout("adminAlone");
			$this->processTemplate("reportes/comisionesPendientes2.html");
		
		}else{
			$this->addLayout("admin");
			$this->processTemplate("reportes/comisionesPendientes.html");
		}
	}
}
?>
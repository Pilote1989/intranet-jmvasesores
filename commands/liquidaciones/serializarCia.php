<?php
class serializarCia extends SessionCommand{
	function execute(){
		$moneda = "Dolares";
		if($this->request->moneda=="1"){
			$moneda = "Dolares";
		}elseif($this->request->moneda=="2"){
			$moneda = "Soles";
		}elseif($this->request->moneda=="3"){
			$moneda = "Euros";
		}
		
		$query='
			SELECT 
				"END" as tipo, e.documento as doc, numeroPoliza as num, c.primaNeta as neta, c.comision as com, c.comisionP as comP, cli.nombre as nom, c.idCobro as cobroId, c.avisoDeCobranza as avi
			FROM
				jmvclientes.Poliza p,
				jmvclientes.Cobro c,
				jmvclientes.Cliente cli,
				jmvclientes.Endoso e
			WHERE
				e.idCobro = c.idCobro
			AND
				p.idCliente = cli.idCliente
			AND
				e.idPoliza = p.idPoliza
			AND
				c.idLiquidacion IS NULL
			AND 
				p.moneda = "' . $moneda . '"
			AND 
				p.estado = "1"
			AND
				p.idCompania = "' . $this->request->idCompania . '"
		';
		
		$j = 0;
		//echo $query;
		$query=utf8_decode($query);		
		$link=&$this->fc->getLink();	
		$listaComisiones = array();			
		$comisiones = array();			
		if($result=$link->query($query)){
			while($row=$result->fetch_assoc()){
				$listaComisiones[]=$row;
				$j++;
			}			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		//echo $query;
		$i=0;
		
		foreach($listaComisiones as $comision){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$comisiones[$i]["idLista"] = $i;
			$comisiones[$i]["cobroId"] = $comision["cobroId"];
			$comisiones[$i]["tipo"] = $comision["tipo"];
			$comisiones[$i]["doc"] = $comision["doc"];
			$comisiones[$i]["num"] = $comision["num"];
			$comisiones[$i]["neta"] = number_format($comision["neta"],2);
			$comisiones[$i]["com"] = number_format($comision["com"],2);
			$comisiones[$i]["comP"] = $comision["comP"];
			$comisiones[$i]["nom"] = $comision["nom"];
			$comisiones[$i]["avi"] = $comision["avi"];
			$i++;
		};
		$query='
			SELECT 
				"POL" as tipo, p.documento as doc, numeroPoliza as num, c.primaNeta as neta, c.comision as com, c.comisionP as comP, cli.nombre as nom, c.idCobro as cobroId, c.avisoDeCobranza as avi
			FROM
				jmvclientes.Poliza p,
				jmvclientes.Cobro c,
				jmvclientes.Cliente cli
			WHERE
				p.idCobro = c.idCobro
			AND
				p.idCliente = cli.idCliente
			AND
				c.idLiquidacion IS NULL
			AND 
				p.moneda = "' . $moneda . '"
			AND
				p.estado = "1"
			AND
				p.idCompania = "' . $this->request->idCompania . '"
		';		

		//echo $query;
		$query=utf8_decode($query);		
		$link=&$this->fc->getLink();	
		$listaComisiones = array();			
		if($result=$link->query($query)){
			while($row=$result->fetch_assoc()){
				$listaComisiones[]=$row;
				$j++;
			}			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		//echo $query;

		foreach($listaComisiones as $comision){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$comisiones[$i]["idLista"] = $i;
			$comisiones[$i]["cobroId"] = $comision["cobroId"];
			$comisiones[$i]["tipo"] = $comision["tipo"];
			$comisiones[$i]["doc"] = $comision["doc"];
			$comisiones[$i]["num"] = $comision["num"];
			$comisiones[$i]["neta"] = $comision["neta"];
			$comisiones[$i]["com"] = $comision["com"];
			$comisiones[$i]["comP"] = $comision["comP"];
			$comisiones[$i]["nom"] = $comision["nom"];
			$comisiones[$i]["avi"] = $comision["avi"];
			$i++;
		}
		if($j > 0){
			echo json_encode($comisiones);		
		}else{
			echo json_encode("");		
		}
	}
}
?>

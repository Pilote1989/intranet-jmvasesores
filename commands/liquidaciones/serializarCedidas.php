<?php
class serializarCedidas extends SessionCommand{
	function execute(){
		$moneda = "Dolares";
		if($this->request->moneda=="1"){
			$moneda = "Dolares";
		}elseif($this->request->moneda=="2"){
			$moneda = "Soles";
		}elseif($this->request->moneda=="3"){
			$moneda = "Euros";
		}
		
		$query="
			SELECT 
				c.idCobro as cobroId,
				rt.doc as tipo,
				cli.nombre as nom,
				c.avisoDeCobranza as avi,
				rt.numeroPoliza as num,
				c.primaNeta as neta,
				c.comision as comi,
				c.comisionP as comiP,
				c.comisionCedida as comiC,
				c.comisionCedidaP as comiCP
			FROM
				jmvclientes.reporteTodo rt,
				jmvclientes.Cobro c,
				jmvclientes.Cliente cli
			WHERE
				rt.idCobro = c.idCobro
					AND rt.idCliente = cli.idCliente
					AND	c.idLiquidacion IS NOT NULL
					AND	c.idCedida IS NULL
					AND	rt.estado = '1'
					AND rt.moneda = '" . $this->request->moneda . "'
					AND c.idPersona = '" . $this->request->idPersona . "'
		";
		
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
			$comisiones[$i]["nom"] = $comision["nom"];
			$comisiones[$i]["avi"] = $comision["avi"];
			$comisiones[$i]["num"] = $comision["num"];
			$comisiones[$i]["neta"] = number_format($comision["neta"],2);
			$comisiones[$i]["comi"] = number_format($comision["comi"],2);
			$comisiones[$i]["comiP"] = $comision["comiP"];
			$comisiones[$i]["comiC"] = number_format($comision["comiC"],2);
			$comisiones[$i]["comiCP"] = $comision["comiCP"];
			$i++;
		};
		
		if($j > 0){
			echo json_encode($comisiones);		
		}else{
			echo json_encode("");		
		}
	}
}
?>

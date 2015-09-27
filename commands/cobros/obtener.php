<?php
class obtener extends SessionCommand{
	function execute(){
		if($this->request->idCobro){
			$cobro = Fabrica::getFromDB("Cobro", $this->request->idCobro);
			if($cobro->tipo()=="POL"){
				$query='
					SELECT 
						"POL" as tipo, p.documento as doc, numeroPoliza as num, c.primaNeta as neta, c.comision as com, c.comisionP as comP, cli.nombre as nom, c.idCobro as cobroId
					FROM
						jmvclientes.Poliza p,
						jmvclientes.Cobro c,
						jmvclientes.Cliente cli
					WHERE
						p.idCobro = c.idCobro
					AND
						p.idCliente = cli.idCliente
					AND
						c.idCobro = "' . $this->request->idCobro . '"
				';
				
			}elseif($cobro->tipo()=="END"){
				$query='
					SELECT 
						"END" as tipo, e.documento as doc, numeroPoliza as num, c.primaNeta as neta, c.comision as com, c.comisionP as comP, cli.nombre as nom, c.idCobro as cobroId
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
						c.idCobro = "' . $this->request->idCobro . '"
				';
	
			}
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
			if($j > 0){
				echo json_encode($listaComisiones[0]);		
			}else{
				echo json_encode("");		
			}
		}
	}
}
?>

<?php
class obtenerCobros extends SessionCommand{
	function execute(){
		if($this->request->ids){
			$ids = explode(",",$this->request->ids);
			$tipos = explode(",",$this->request->tipos);
			$i=0;
			$listaComisiones = array();	
			foreach($ids as $id){
				if($tipos[$i]=="POL"){
					$query='
						SELECT 
							"POL" as tipo, p.documento as doc, numeroPoliza as num, c.primaNeta as neta, c.comision as com, c.comisionP as comP, c.comisionCedida as comC, c.comisionCedidaP as comCP, cli.nombre as nom, c.idCobro as cobroId, c.avisoDeCobranza as avi
						FROM
							jmvclientes.Poliza p,
							jmvclientes.Cobro c,
							jmvclientes.Cliente cli
						WHERE
							p.idCobro = c.idCobro
						AND
							p.idCliente = cli.idCliente
						AND
							c.idCobro = "' . $id . '"
					';
					
				}elseif($tipos[$i]=="END"){
					$query='
						SELECT 
							"END" as tipo, e.documento as doc, numeroPoliza as num, c.primaNeta as neta, c.comision as com, c.comisionP as comP, c.comisionCedida as comC, c.comisionCedidaP as comCP, cli.nombre as nom, c.idCobro as cobroId, c.avisoDeCobranza as avi
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
							c.idCobro = "' . $id . '"
					';
		
				}
				$query=utf8_decode($query);		
				$link=&$this->fc->getLink();			
				if($result=$link->query($query)){
					while($row=$result->fetch_assoc()){
						$listaComisiones[]=$row;
						$j++;
					}			
				}else{
					printf("Error: %s\n", $link->error);
					return null;
				}
				$i++;	
			}
			if($i > 0){
				echo json_encode($listaComisiones);		
			}else{
				echo json_encode("");		
			}
		}
	}
}
?>

<?php
class busquedaCupones extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaCupones"]["pagina"]=$this->request->pagina;
		}
		$limite=10;
		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		$where=array();
		//echo $this->request->rango;
		if($this->request->rango){
			$fechas = explode("-", $this->request->rango);
			$_SESSION["busquedaCupones"]["rango"] = $this->request->rango;
			$_SESSION["busquedaCupones"]["inicio"] = $fechas[0];
			$_SESSION["busquedaCupones"]["fin"] = $fechas[1];
			$d = str_replace('/', '-', $fechas[0]);
			$inicio = date('Y-m-d', strtotime($d));
			$d = str_replace('/', '-', $fechas[1]);
			$fin = date('Y-m-d', strtotime($d));
			$inQuery = "
			AND c.fechaVencimiento > DATE('" . $inicio . "')
			AND c.fechaVencimiento < DATE('" . $fin . "')
			";
		}
		$test = false;
		if($this->request->porEnviar!="on"){
			$_SESSION["busquedaCupones"]["porEnviar"] = "";
		}else{
			$_SESSION["busquedaCupones"]["porEnviar"] = 'checked="checked"';
			$where[] = "DATEDIFF(DATE(c.fechaVencimiento),DATE(NOW())) > 4";
			$test = true;
		}
		if($this->request->enviado!="on"){
			$_SESSION["busquedaCupones"]["enviado"] = "";
		}else{
			$_SESSION["busquedaCupones"]["enviado"] = 'checked="checked"';
			$where[] = "DATEDIFF(DATE(c.fechaVencimiento),DATE(NOW())) < 4";
			$test = true;
		}
		if($this->request->enviadoHoy!="on"){
			$_SESSION["busquedaCupones"]["enviadoHoy"] = "";
		}else{
			$_SESSION["busquedaCupones"]["enviadoHoy"] = 'checked="checked"';
			$where[] = "DATEDIFF(DATE(c.fechaVencimiento),DATE(NOW())) = 4";
			$test = true;
		}
		//var_dump($listaArray);
	
		if(sizeof($where)){
			$whereCondition=" AND ((".implode(") OR (",$where)."))";
		}
		$pPaginador = "";
		$cupones = array();
		$query = "
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT r.nombre as ramo, cl.nombre as contratante, p.idPoliza as idPoliza, c.fechaVencimiento as fechaVenc, p.numeroPoliza as numeroPoliza, c.numeroCupon as numeroCupon, cm.nombre as cia, cm.sigla as scia, c.idCupon as idCupon, DATEDIFF(DATE(c.fechaVencimiento),DATE(NOW())) as fecha_diferencia
			FROM Poliza p, Cupon c, Ramo r, Cliente cl, Compania cm
			WHERE p.idCliente = cl.idCliente
			AND c.idPoliza = p.idPoliza
			AND p.estado = '1'
			AND p.anulada = '0'
			" . $whereCondition . "
			AND p.idRamo = r.idRamo
			AND cl.idCliente = p.idCliente
			AND cm.idCompania = p.idCompania
			" . $inQuery. "
			AND DATEDIFF(DATE(c.fechaVencimiento),DATE(NOW())) > -14
			ORDER BY fechaVenc ASC
			LIMIT ".$minimoDePagina.", ".$limite."
		";
		//echo $query;
		
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
			$listaCupones=array();
			$this->fc->import("lib.Paginador");
			while($row=$result->fetch_assoc()){
				if($test){
					$listaCupones[]=$row;	
				}else{
					$num_rows = 0;	
				}
			}
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		$i=0;
		foreach($listaCupones as $cupon){
			$cuponActual = Fabrica::getFromDB("Cupon",$cupon["idCupon"]);
			$cupones[$i]["contratante"] = $cupon["contratante"];
			$cupones[$i]["ramo"] = $cupon["ramo"];
			$cupones[$i]["numeroPoliza"] = $cupon["numeroPoliza"];			
			$cupones[$i]["idPoliza"] = $cupon["idPoliza"];		
			$cupones[$i]["cia"] = $cupon["cia"];			
			$cupones[$i]["scia"] = $cupon["scia"];				
			$cupones[$i]["monto"] = $cuponActual->monto();	
			$cupones[$i]["vencimiento"] = date( "d/m/Y", strtotime($cupon["fechaVenc"]));
			$cupones[$i]["fecha_diferencia"] = $cupon["fecha_diferencia"];				
			$cupones[$i]["estado"] = $cuponActual->estadoColor();			
			$cupones[$i]["idPoliza"] = $cupon["idPoliza"];	
			$i++;
		}
		$tablaPaginas=Paginador::crearNewHtmlAjax($paginaActual,$num_rows,"?do=admin.busquedaCupones".$pPaginador,"divBusquedaRecordatorios", $limite);
		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		$this->addLoop("cupones",$cupones);
		$this->processTemplate("admin/busquedaCupones.html");
	}
}
?>

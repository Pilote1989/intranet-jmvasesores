<?php
class oldMenuNotificaciones extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		//cuponesPorVencer
		$query = "
			SELECT
				COUNT(*) AS a
			FROM
				jmvclientes.Poliza p,
				jmvclientes.Cupon c
			WHERE c.idPoliza = p.idPoliza
					AND p.estado = '1'
					AND p.anulada = '0'
					AND DATE(c.fechaVencimiento) > DATE(NOW())
					AND DATE(c.fechaVencimiento) < ADDDATE(DATE(NOW()), 10)
		";
		$query=utf8_decode($query);		
		$link =&$this->fc->getLink();	
		if($countResult=$link->query($query)){
			$row=$countResult->fetch_assoc();
			$resultado=$row['a'];
			$cuponesPorVencer = $resultado;
		}else{
			printf("Error: %s\n", $dbLink->error);
			$cuponesPorVencer = "0";
		}
		$this->addVar("cuponesPorVencer",$cuponesPorVencer);
		//polizasPorVencer
		$query = "
			SELECT 
				COUNT(*) AS a
			FROM
				(SELECT 
					p.numeroPoliza, max(p.finVigencia) AS fin
				FROM
					jmvclientes.Poliza p
				WHERE
					p.estado = '1' AND p.anulada = '0'
				GROUP BY p.numeroPoliza) AS temp
			WHERE
				DATE(temp.fin) > DATE(now())
					AND DATE(temp.fin) < ADDDATE(DATE(NOW()), 10)
		";
		$query=utf8_decode($query);		
		$link =&$this->fc->getLink();	
		if($countResult=$link->query($query)){
			$row=$countResult->fetch_assoc();
			$resultado=$row['a'];
			$polizasPorVencer = $resultado;
		}else{
			printf("Error: %s\n", $dbLink->error);
			$polizasPorVencer = "0";
		}
		$this->addVar("polizasPorVencer",$polizasPorVencer);
		//numeroNotificaciones
		$this->addVar("numeroNotificaciones","2");
		//cantidadNotificaciones
		$this->addVar("cantidadNotificaciones","2 Notificaciones");
		//$user = $this->getUsuario();
		$this->processTemplate("menus/oldMenuNotificaciones.html");
	}
}
?>

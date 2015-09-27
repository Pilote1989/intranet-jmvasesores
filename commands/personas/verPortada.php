<?php
class verPortada extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		$this->addVar("doFalso", $this->request->do);
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		if($this->checkAccess("crearUsuario", true)){
			$this->addBlock("admin");
			$porLiquidar = "X";
			$porVencer = "X";
			$recordatoriosPorEnviar = "X";
			//comisiones por liquidar
			$query='
			SELECT 
				COUNT(*) as c
			FROM
				jmvclientes.Cobro
			WHERE
				idLiquidacion IS NULL
			';
			$query=utf8_decode($query);		
			$link=&$this->fc->getLink();			
			if($result=$link->query($query)){
				$row=$result->fetch_assoc();
				//echo $row["c"];
				/*while($row=$result->fetch_assoc()){
					$listaComisiones[]=$row;
					$j++;
				}*/
				$porLiquidar = $row["c"];
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			//polizas por vencer
			$query='
			SELECT COUNT( DATEDIFF(  `finVigencia` , CURDATE( ) ) ) AS c
			FROM  `Poliza` 
			WHERE DATEDIFF(  `finVigencia` , CURDATE( ) ) >0
			AND DATEDIFF(  `finVigencia` , CURDATE( ) ) <7
			';
			$query=utf8_decode($query);		
			$link=&$this->fc->getLink();			
			if($result=$link->query($query)){
				$row=$result->fetch_assoc();
				$porVencer = $row["c"];
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$this->addVar("porLiquidar", $porLiquidar);
			$this->addVar("porVencer", $porVencer);
			$this->addVar("recordatoriosPorEnviar", $recordatoriosPorEnviar);
			
		}
		// Nombre
		$this->addBlock("bloqueNombre");
		//$this->addVar("nombreUsuario", $usuario->getNombres());
		$this->addLayout("admin");
		$this->processTemplate("personas/verPortada.html");
	}
}
?>
<?php
class Mes extends DBObject{
	var $tableName="Mes";
	
	function estadoColor(){
		if($this->getEstado()==1)
			return "red";
		return "green";
	}
	function codBoot(){
		if($this->getEstado()==1)
			return "danger";
		return "success";
	}
	
	function calPrimaNeta($moneda = "Dolares"){
		$suma = 0;
		$listaLiquidaciones = Fabrica::getAllFromDB("Liquidacion",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes()));
		foreach($listaLiquidaciones as $liquidacion){
			$suma += Fabrica::getSumFromDB("Cobro","primaNeta",array("idLiquidacion = " . $liquidacion->getId(),"moneda = '".$moneda."'"));
		}
		return $suma;
	}
	
	function calComision($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Liquidacion","subTotal",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
	
	function calIgvFacturas($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Liquidacion","igv",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}	
	
	function calTotalFacturas($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Liquidacion","totalFactura",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
	
	function calComisionCedida($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Cedida","subTotal",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
	
	function calIgvCedidas($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Cedida","igv",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
		
	function calTotalCedidas($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Cedida","totalFactura",array("YEAR(fechaFactura) = " . $this->getAnio(),"MONTH(fechaFactura) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}	
	
	function calSubtotalCompras($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Compra","subtotal",array("YEAR(fechaPresentacion) = " . $this->getAnio(),"MONTH(fechaPresentacion) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
	
	function calIgvCompras($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Compra","igv",array("YEAR(fechaPresentacion) = " . $this->getAnio(),"MONTH(fechaPresentacion) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
	
	function calOtrosCompras($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Compra","otros",array("YEAR(fechaPresentacion) = " . $this->getAnio(),"MONTH(fechaPresentacion) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
	
	function calTotalCompras($moneda = "Dolares"){
		return Fabrica::getSumFromDB("Compra","total",array("YEAR(fechaPresentacion) = " . $this->getAnio(),"MONTH(fechaPresentacion) = " .  $this->getMes(),"moneda = '".$moneda."'"));
	}
}
?>
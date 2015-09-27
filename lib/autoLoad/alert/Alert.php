<?php
//  Descripcion:
///  Clase para presentar mensajes en pantalla
//  Uso:
///  new Alert("mensaje de ejemplo",Alert::SUCCESS,grupo);

class Alert{
	const ERROR="ERROR";
	const WARNING="WARNING";
	const SUCCESS="SUCCESS";
	private	$content;
	private $type;
	public $group; //tag para despliegue en distintas partes de la pantalla

	public function __construct($content,$type,$group="main"){
		if($type!=self::ERROR && $type!=self::WARNING&& $type!=self::SUCCESS){
			$content="ERROR EN TIPO DE MENSAJE(".$msg.")";
			$type=self::WARNING;
		}
		$this->content=$content;
		$this->type=$type;
		$this->group=$group;
		$alerts=Fabrica::getFromSession("Alerts");
		$alerts[]=$this;
		new SessionObject("Alerts",$alerts);
	}
	public function getContent(){
		return htmlentities($this->content);
	}
	public function getAlertType(){
		return htmlentities($this->type);
	}
	
}
?>
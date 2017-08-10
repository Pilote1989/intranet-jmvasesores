<?php
abstract class DescripcionTablas{
	static $descripciones;
	static $foreignKeys;
	
	/********************************************************/
	/** Entrega la lista de campos de una tabla/clase      **/
	/********************************************************/
	public static function getFields($tableName){
		if(!(isset(self::$descripciones[$tableName]))){
			self::describeTable($tableName);
		}
		return self::$descripciones[$tableName]['Field'];
	}
	/********************************************************/
	/** Lista de claves primarias de una tabla             **/
	/********************************************************/
	public static function getPrimaryKeys($tableName){
		if(!(isset(self::$descripciones[$tableName]))){
			self::describeTable($tableName);
		}
		$primaryKeys=array();
		foreach(self::$descripciones[$tableName]['Key'] as $key=>$value){
			if($value=='PRI'){
				$primaryKeys[]=$key;
			}
		}
		return $primaryKeys;
	}
	/********************************************************/
	/** Obtener una unica clave primaria de una tabla      **/
	/********************************************************/
	public static function getPrimaryKey($tableName){
		$keys=self::getPrimaryKeys($tableName);
		if(sizeof($keys)==1){
			return $keys[0];
		}elseif(sizeof($keys)>1){
			return $keys;
		}
		return NULL;
	}
	
	public static function isAutoIncrement($tableName,$column){
		if(!(isset(self::$descripciones[$tableName]))){
			self::describeTable($tableName);
		}
		if(self::$descripciones[$tableName]['Extra'][$column] == "auto_increment"){
			return true;
		}
		return false;
	}
	
	/********************************************************/
	/** Entrega array con datos de claves foraneas         **/
	/********************************************************/
	public static function getForeignKeys($tableName){
		if(!isset(self::$foreignKeys[$tableName])){
			self::showCreateTable($tableName);
		}
		if(is_array(self::$foreignKeys[$tableName])){
			return self::$foreignKeys[$tableName];
		}
		return array();

	}
	private static function describeTable($tableName){
		$dbLink=&FrontController::instance()->getLink();
		$query="DESCRIBE ".$tableName;
		if($result= $dbLink->query($query)){
			$descripcion=array();
			while($row=$result->fetch_assoc()){
				$fieldKey=$row['Field'];
				foreach($row as $key=>$value){
					$descripcion[$key][$fieldKey]=$value;
				}
			}
			self::$descripciones[$tableName]=&$descripcion;
		}
	}
	
	private static function showCreateTable($tableName){
		$dbLink=&FrontController::instance()->getLink();
		$query="SHOW CREATE TABLE ".$tableName;
		if($result=$dbLink->query($query)){
			$row=$result->fetch_assoc();
			$createQuery=$row["Create Table"];
			$createExploded=explode(",",$createQuery);
			foreach($createExploded as $lineaSQL){
				if(!($foreignKeyString=strstr($lineaSQL,"FOREIGN KEY"))){
					continue;
				}
				$foreignKeyString=str_replace("`","",$foreignKeyString);
				$foreignKeyString=str_replace("(","",$foreignKeyString);
				$foreignKeyString=str_replace(")","",$foreignKeyString);
				$pattern='FOREIGN KEY %s REFERENCES %s %s';
				sscanf($foreignKeyString,$pattern,$column,$tableRef,$columnRef);
				self::$foreignKeys[$tableName][$column]=$tableRef;
			}
		}
	}
}

abstract class DBObject{
	private $dataArray;// Ejemplo: $dataArray["idPersona"]=1;
	private $originalId;
	public $fc;
	public $tableName=NULL;

	public function __construct(){
		$this->fc= &FrontController::instance();
		if(empty($this->tableName)){
			$this->tableName=strtolower(get_class($this))."s";
		}
		$campos=DescripcionTablas::getFields($this->tableName);
		foreach($campos as $campo){
			$this->dataArray[$campo]=null;
		}
	}

	/********************************************************/
	/** Funcion para almacenar objetos en base de datos    **/
	/********************************************************/
	public function storeIntoDB(){
	
		if(Fabrica::getFromDB(get_class($this),$this->getOriginalId())){
			return $this->update();
		}
		return $this->insert();
	}
	public function save(){
		return $this->storeIntoDB();
	}
	
	/********************************************************/
	/** Funcion para borrar objetos de base de datos       **/
	/********************************************************/
	public function deleteFromDB(){
		return $this->delete();
	}
	
	/********************************************************/
	/** Metafuncion para captura y seteo de atributos      **/
	/********************************************************/
	function __call($val, $x){
		if(substr($val, 0, 3) == 'get'){
			$varname = strtolower(substr($val, 3, 1)).substr($val, 4);
			if(array_key_exists($varname, $this->dataArray)){
				if(sizeof($x)){
					$tipo=$x[0];
				}elseif(isset($this->defaultType[$varname])){
					$tipo=$this->defaultType[$varname];
				}else{
					//usando codificacion UTF-8, basta con tipo=DB
					$tipo="DB";
				}
				switch($tipo){
					case "HTML":
						$output=str_replace("  ","&nbsp;&nbsp;",nl2br($this->dataArray[$varname]));
						$output=str_replace("\"","&quot;",$output);
						$output=str_replace("<","&lt;",$output);
						$output=str_replace(">","&gt;",$output);
						return $output;
					case "DB":
						return ($this->dataArray[$varname]);
					case "BASE64DECODE":
						return base64_decode($this->dataArray[$varname]);
					case "JS":
						return $this->dataArray[$varname];
					case "NUMBER":
						$decimals=0;
						$pieces=explode(".",$this->dataArray[$varname]);
						if(sizeof($pieces)==2){
							$decimals=strlen($pieces[1]);
						}
						return number_format($this->dataArray[$varname],$decimals,',','.');
					case "DATE":
						if(trim($this->dataArray[$varname])=="")
							return "";
						return date("d/m/Y",strtotime($this->dataArray[$varname]));
					case "DATETIME":
						if(trim($this->dataArray[$varname])=="")
							return "";
						return date("d/m/Y H:i:s",strtotime($this->dataArray[$varname]));
					default:
						return NULL;
				}
			}elseif(array_key_exists("id".substr($val, 3),DescripcionTablas::getForeignKeys(get_class($this)))){
				$fKeys=DescripcionTablas::getForeignKeys(get_class($this));
				return Fabrica::getFromDB($fKeys["id".substr($val, 3)],$this->dataArray["id".ucfirst($varname)]);
			}
		}elseif(substr($val, 0, 3) == 'set'){
			//echo "<div>_".substr($val, 0, 3)."_</div>";
			$varname = strtolower(substr($val, 3, 1)).substr($val, 4);
			//echo "<div>_".$varname."_</div>";
			if(array_key_exists($varname, $this->dataArray)){
				if(!(sizeof($x))){
					return false;
				}elseif(sizeof($x)==2){
					$tipo=$x[1];
				}elseif(isset($this->defaultType[$varname])){
					$tipo=$this->defaultType[$varname];
				}else{
					$tipo="DB";
				}
				//echo '<div>'.$x[0].'-'.$tipo.'</div>';
				switch($tipo){
					case "DB":
						$this->dataArray[$varname]=self::unhtmlentities ($x[0]);
						return true;
					case "MD5":
						$this->dataArray[$varname]=md5($x[0]);
						return true;
					case "BASE64ENCODE":
						$this->dataArray[$varname]=base64_encode($x[0]);
						return true;
					case "DATE":
						$dates=explode("/",$x[0]);
						$this->dataArray[$varname]=$dates[2]."-".$dates[1]."-".$dates[0];
						return true;
					case "DATETIME":
						//asume entrada tipo 11/12/2005 14:50
						$dates=explode("/",$x[0]);
						$yearTime=explode(" ",$dates[0]);
						$hms=explode(":",$yearTime[1]);
						$this->dataArray[$varname]=$dates[2]."-".$dates[1]."-".$yearTime[0]." ".$hms[0].":".$hms[1].":00";
						return true;
					case "NUMBER":
						$intDec=explode(",",$x[0]);
						$integ=explode(".",$intDec[0]);
						$num=implode("",$integ);
						if(isset($intDec[1])){
							$num.=".".$intDec[1];
						}
						$this->dataArray[$varname]=$num;
					default:
						return false;
				}
			}
		}else{
			die("Method $val does not exist\n");
		}
		return false;
	}
	
	public function getId(){
		return implode(".",$this->getIdArray() );
	}
	public function getOriginalId(){
		return $this->originalId;
	}
	public function setOriginalId($id){
		$this->originalId=$id;;
	}
	public function getIdArray(){
		$keys=DescripcionTablas::getPrimaryKeys($this->tableName);
		$ids=array();
		foreach($keys as $k){
			$ids[]=$this->dataArray[$k];
		}
		return $ids;
	}

	
	/********************************************************/
	/** Impresion amistosa del objeto                      **/
	/********************************************************/
	public function __toString() {
		$strOut="<table>\n";
		$strOut.="\t<tr>\n";
		$strOut.="\t\t<td colspan=\"2\"><b>".get_class($this)."</b></td>\n";
		foreach($this->dataArray as $key=>$val){
			$strOut.="\t<tr>\n";
			$strOut.="\t\t<td>".$key."</td><td> = '".$val."'</td>\n";
			$strOut.="\t</tr>";
		}
		$strOut.="</table>\n";
		return $strOut;
	}
	
	/********************************************************/
	/** Metodos privados                                   **/
	/********************************************************/
	private function insert(){
		$dbLink=&FrontController::instance()->getLink();
		$col=array();
		$val=array();
		foreach($this->dataArray as $campo=>$valor){
			if(trim($valor)!=""){
				$col[]=$campo;
				$val[]=$dbLink->real_escape_string(utf8_decode($valor));
			}
		}
		if(!(sizeof($col))){
			return false;
		}
		$query="SET @user_id = '".$_SESSION["ID"]."';INSERT INTO ".$this->tableName." (".implode(", ",$col).") VALUES ('".implode("', '",$val)."');SET @user_id = null;";
		//echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		if(!($result=$dbLink->multi_query($query))){
			printf("Error : %s\n", $dbLink->error);
			return $result;
		}
		$primaryKey=DescripcionTablas::getPrimaryKey($this->tableName);
		if(DescripcionTablas::isAutoIncrement($this->tableName,$primaryKey)){
			$this->dataArray[$primaryKey]=$dbLink->insert_id;
		}
		$this->setOriginalId($this->dataArray[$primaryKey]);
		return true;
	}
	private function update(){
		$dbLink=&FrontController::instance()->getLink();
		$primaryKey=DescripcionTablas::getPrimaryKey($this->tableName);
		$query="SET @user_id = '".$_SESSION["ID"]."';UPDATE ".$this->tableName." SET ";
		foreach($this->dataArray as $campo=>$valor){
			if(trim($valor)==""){
				$query.="`".$campo."` = NULL, ";
			}else{
				$query.="`".$campo."`= '".$dbLink->real_escape_string(utf8_decode($valor))."', ";
			}
		}
		$query=substr($query,0,-2)." WHERE `".$primaryKey."`='".$this->getOriginalId()."' LIMIT 1;SET @user_id = null;";
		//echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		//echo "<div>".$query."</div>";
		$result=$dbLink->multi_query($query);
		if(!$result){
			printf("Error : %s\n", $dbLink->error);
			return $result;
		}
		return true;
	}
	public function delete(){
		$dbLink=&FrontController::instance()->getLink();
		///TODO revisar reescritura
		$keys=DescripcionTablas::getPrimaryKeys($this->tableName);
		$primaryKeys=array();
	//print_r($primaryKeys);
		foreach($keys as $k){
			$method="get".ucfirst($k);
			$primaryKeys[]=$k."='".$this->$method("DB")."'";
		}
	//print_r($primaryKeys);
		$query="DELETE FROM ".$this->tableName." WHERE (".implode(") AND( ",$primaryKeys).")";
		//echo $query;
		$result=$dbLink->query($query);
		if(!$result){
			printf("Error : %s\n", $dbLink->error);
			return $result;
		}
	//	FrontController::instance()->import("lib.Fabrica");
		Fabrica::deleteInstancia(get_class($this),$this->getId());
		return true;
	}
	
	private function unhtmlentities ($string) {
		$trans_tbl =get_html_translation_table (HTML_ENTITIES );
		$trans_tbl =array_flip ($trans_tbl );
		return strtr ($string ,$trans_tbl );
	}

}
class SessionObject{
	private $identifier; //key, DBObject::getId()
	private $object; //string, boolean, DBObject...
	private $serializedObject;
	private $className;
	
	public function __construct($id,$obj){
		$this->identifier=$id;
		$this->object=$obj;
		$this->serializedObject=serialize($this->object);
		$this->className=false;
		if(is_object($this->object)){
			$this->className=get_class($this->object);
		}
		$this->storeIntoSession();
	}
	public function initialUnserialize(){
		if($this->className){
			if(!class_exists($this->className)){
				FrontController::instance()->import("lib.".$this->className);
			}
		}
		$this->object=unserialize($this->serializedObject);
	}
	public function storeIntoSession(){
		$_SESSION[$this->identifier]=serialize($this);
	}
	public function getId(){
		return $this->identifier;
	}
	public function getObject(){
		return $this->object;
	}
}
/******************************************************************************/
/******************************************************************************/
/******************************************************************************/
/******************************************************************************/
/******************************************************************************/
/******************************************************************************/
/******************************************************************************/

abstract class Fabrica{
	private static $instancias;
	private static $num_rows;

	public static function getFromDB($className,$id){
		if(isset(self::$instancias[$className][$id])){
			return self::$instancias[$className][$id];
		}
		if (!class_exists($className)) {
			$fc=&FrontController::instance();
			$fc->import("lib.".$className);
			if(!class_exists($className)){
				echo("Clase <i>".$className."</i> no declarada en carpeta lib");
				exit();
			}
		}
		$aux=new $className();
		if(!sizeof( $keys= DescripcionTablas::getPrimaryKeys($aux->tableName))){
			echo("No se encontraron claves primarias para la clase <i>".$className."</i>");
			exit();
		}
		$idArray=explode(".",$id);
		if(sizeof($keys)!=sizeof($idArray)){
			echo("El número de claves primarias especificadas para obtener objetos de la clase <i>".$className."</i> no corresponde");
			exit();
		}
		$filtros=array();
		for($i=0;$i<sizeof($keys);$i++){
			$filtros[]=$keys[$i]."='".$idArray[$i]."'";
		}
		$arrayClassName=self::getAllFromDB($className,$filtros);
		if(is_array($arrayClassName)){
			if(sizeof($arrayClassName)==1){	
				return $arrayClassName[0];
			}
		}
		return null;
	}

	public static function getAllFromDB($className,$arrayFiltros=array(),$order=NULL,$limit=NULL,$queryPrint=NULL){
		$fc=&FrontController::instance();
		if (!class_exists($className)) {
			$fc->import("lib.".$className);
		}
		$stringWhere="";
		$stringOrder="";
		$stringLimit="";
		$stringFoundRows="";
		//Arreglo de filtros, string de WHERE
		if(sizeof($arrayFiltros)){
			$stringWhere=" WHERE (".implode(") AND (",$arrayFiltros).")";
		}
		//String de orden
		if($order){
			$stringOrder=" ORDER BY	".$order;
		}
		//String de Limit
		if($limit){
			$stringFoundRows="SQL_CALC_FOUND_ROWS ";
			$stringLimit=" LIMIT ".$limit;
		}
		$dbLink=&$fc->getLink();
		$aux=new $className();
		$query="SELECT ".$stringFoundRows."* FROM ".$aux->tableName.$stringWhere.$stringOrder.$stringLimit;
		if($queryPrint){
			echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		}
		$query=utf8_decode($query);
		$objetos=array();
		if($result=$dbLink->query($query)){
			//numero de registros encontrados
			$countQuery="SELECT FOUND_ROWS() as total";
			if($countResult=$dbLink->query($countQuery)){
				$row=$countResult->fetch_assoc();
				self::$num_rows=$row['total'];
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
			$countResult->free();
			//instanciar objetos
			while($row=$result->fetch_assoc()){
				$aux = new $className;
				foreach($row as $key => $data){
					$setAtributo="set".ucfirst($key);
					$aux->$setAtributo(stripslashes(utf8_encode($data)),"DB");
					$aux->setOriginalId($aux->getId());
				}
				
				self::$instancias[$className][$aux->getId()]=$aux;
				$objetos[]=&self::$instancias[$className][$aux->getId()];
			}
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}
		$result->free();
		return $objetos;
	}
	
	public static function getSumFromDB($className,$field,$arrayFiltros=array(),$order=NULL,$limit=NULL,$queryPrint=NULL){
		$suma = 0;
		$fc=&FrontController::instance();
		if (!class_exists($className)) {
			$fc->import("lib.".$className);
		}
		$stringWhere="";
		$stringOrder="";
		$stringLimit="";
		$stringFoundRows="";
		//Arreglo de filtros, string de WHERE
		if(sizeof($arrayFiltros)){
			$stringWhere=" WHERE (".implode(") AND (",$arrayFiltros).")";
		}
		//String de orden
		if($order){
			$stringOrder=" ORDER BY	".$order;
		}
		//String de Limit
		if($limit){
			$stringFoundRows="SQL_CALC_FOUND_ROWS ";
			$stringLimit=" LIMIT ".$limit;
		}
		$dbLink=&$fc->getLink();
		$aux=new $className();
		$query="SELECT ".$stringFoundRows."SUM(".$field.") AS suma FROM ".$aux->tableName.$stringWhere.$stringOrder.$stringLimit;
		if($queryPrint){
			return '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		}
		//echo $query;
		$query=utf8_decode($query);
		$objetos=array();
		if($result=$dbLink->query($query)){
			//numero de registros encontrados
			$countQuery="SELECT FOUND_ROWS() as total";
			if($countResult=$dbLink->query($countQuery)){
				$row=$countResult->fetch_assoc();
				self::$num_rows=$row['total'];
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
			$countResult->free();
			$row=$result->fetch_assoc();
			$suma = $row["suma"];
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}
		$result->free();
		return $suma;
	}
	
	public static function getHistoryFromDB($className,$id,$queryPrint=NULL){
		if (!class_exists($className)) {
			$fc=&FrontController::instance();
			$fc->import("lib.".$className);
			if(!class_exists($className)){
				echo("Clase <i>".$className."</i> no declarada en carpeta lib");
				exit();
			}
		}
		$aux=new $className();
		if(!sizeof( $keys= DescripcionTablas::getPrimaryKeys($aux->tableName))){
			echo("No se encontraron claves primarias para la clase <i>".$className."</i>");
			exit();
		}
		$idArray=explode(".",$id);
		if(sizeof($keys)!=sizeof($idArray)){
			echo("El número de claves primarias especificadas para obtener objetos de la clase <i>".$className."</i> no corresponde");
			exit();
		}
		$filtros=array();
		for($i=0;$i<sizeof($keys);$i++){
			$filtros[]=$keys[$i]."='".$idArray[$i]."'";
		}
		if(sizeof($filtros)){
			$stringWhere=" WHERE (".implode(") AND (",$filtros).")";
		}
		
		$dbLink=&$fc->getLink();
		$aux=new $className();
		$query="SELECT * FROM x_".$aux->tableName." ".$stringWhere. " ORDER BY 'revision' ASC";
		if($queryPrint){
			echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		}
		$query=utf8_decode($query);
		$objetos=array();
		if($result=$dbLink->query($query)){
			//numero de registros encontrados
			$countQuery="SELECT FOUND_ROWS() as total";
			if($countResult=$dbLink->query($countQuery)){
				$row=$countResult->fetch_assoc();
				self::$num_rows=$row['total'];
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
			$countResult->free();
			//instanciar objetos
			$lista=array();
			while($row=$result->fetch_assoc()){
				$lista[]=$row;
				/*
				$aux = new $className;
				foreach($row as $key => $data){
					$setAtributo="set".ucfirst($key);
					$aux->$setAtributo(stripslashes(utf8_encode($data)),"DB");
					$aux->setOriginalId($aux->getId());
				}
				
				self::$instancias[$className][$aux->getId()]=$aux;
				$objetos[]=&self::$instancias[$className][$aux->getId()];*/
			}
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}
		$result->free();
		return $lista;
		/*
		$arrayClassName=self::getAllFromDB($className,$filtros);
		
		if(is_array($arrayClassName)){
			if(sizeof($arrayClassName)==1){	
				return $arrayClassName[0];
			}
		}
		return null;		
		*/
		
		
		
		/*
		$fc=&FrontController::instance();
		if (!class_exists($className)) {
			$fc->import("lib.".$className);
		}
		$stringWhere="";
		$stringOrder="";
		$stringLimit="";
		$stringFoundRows="";
		//Arreglo de filtros, string de WHERE
		if(sizeof($arrayFiltros)){
			$stringWhere=" WHERE (".implode(") AND (",$arrayFiltros).")";
		}
		//String de orden
		if($order){
			$stringOrder=" ORDER BY	".$order;
		}
		//String de Limit
		if($limit){
			$stringFoundRows="SQL_CALC_FOUND_ROWS ";
			$stringLimit=" LIMIT ".$limit;
		}
		$dbLink=&$fc->getLink();
		$aux=new $className();
		$query="SELECT ".$stringFoundRows."* FROM ".$aux->tableName.$stringWhere.$stringOrder.$stringLimit;
		if($queryPrint){
			echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		}
		$query=utf8_decode($query);
		$objetos=array();
		if($result=$dbLink->query($query)){
			//numero de registros encontrados
			$countQuery="SELECT FOUND_ROWS() as total";
			if($countResult=$dbLink->query($countQuery)){
				$row=$countResult->fetch_assoc();
				self::$num_rows=$row['total'];
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
			$countResult->free();
			//instanciar objetos
			while($row=$result->fetch_assoc()){
				$aux = new $className;
				foreach($row as $key => $data){
					$setAtributo="set".ucfirst($key);
					$aux->$setAtributo(stripslashes(utf8_encode($data)),"DB");
					$aux->setOriginalId($aux->getId());
				}
				
				self::$instancias[$className][$aux->getId()]=$aux;
				$objetos[]=&self::$instancias[$className][$aux->getId()];
			}
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}
		$result->free();
		return $objetos;*/
	}	
	
	public static function getCountFromDB($className,$arrayFiltros=array(),$order=NULL,$limit=NULL,$queryPrint=NULL,$distinct=NULL){
		$fc=&FrontController::instance();
		if (!class_exists($className)) {
			$fc->import("lib.".$className);
		}
		$stringWhere="";
		$stringOrder="";
		$stringLimit="";
		$stringFoundRows="";
		//Arreglo de filtros, string de WHERE
		if(sizeof($arrayFiltros)){
			$stringWhere=" WHERE (".implode(") AND (",$arrayFiltros).")";
		}
		//String de orden
		if($order){
			$stringOrder=" ORDER BY	".$order;
		}
		//String de Limit
		if($limit){
			$stringLimit=" LIMIT ".$limit;
		}
		//Distinct de un campo
		if($distinct){
			$d="DISTINCT(".$distinct.")";
		}else{
			$d="1";
		}
		
		$dbLink=&$fc->getLink();
		$aux=new $className();
		$query="SELECT COUNT(".$d.") as total FROM ".$aux->tableName.$stringWhere.$stringOrder.$stringLimit;
		if($queryPrint){
			echo '<div style="width:100%;background:#AAFFAA; color:#449944; font-size:10px; font-weight:bold;">'.$query.'</div>';
		}
		$query=utf8_decode($query);
		$total=0;
		if($result=$dbLink->query($query)){
			self::$num_rows=1;
			
			//instanciar objetos
			$row = $result->fetch_assoc();
			$total = $row["total"];
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}
		$result->free();
		return $total;
	}
	
	public static function getNumRows(){
		return self::$num_rows;
	}
	public function deleteInstancia($className,$id){
		unset(self::$instancias[$className][$id]);
	}
	
	/*metodos para objetos en sesion*/
	public function getFromSession($id){
		if(!isset($_SESSION[$id])){
			return NULL;
		}
		$sessionObj=unserialize($_SESSION[$id]);
		$sessionObj->initialUnserialize();
		return $sessionObj->getObject();
	}
	public function deleteFromSession($identifier){
		unset($_SESSION[$identifier]);
	}
}
?>

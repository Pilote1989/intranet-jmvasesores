<?php
class Language{
	var $code; 
	var $messages;
	var $dictionary;
	const MAX_LENGTH=100;
	
	function __construct($dictionary,$code="en"){
		$this->dictionary=$dictionary;
		$this->code=$code;
		$this->readDictionary();
	}

	function getCode() { 
		return $this->code; 
	}

	function getMessage( $msgId ) { 
		$args = func_get_args() ; 
		unset($args[0]);
		return vsprintf( $this->messages[$msgId][$this->code], $args);
	}

	private function readDictionary() { 
		$fullPath=realpath($this->dictionary);
		if (!$fp=fopen( $fullPath, 'r' )){
			$msg="Error: Cannot open user language file in <i>".$fullPath."</i>";
			new Error($msg);
		}
		$i=0;
		while($line=fgets($fp,self::MAX_LENGTH)){
			$i++;
			$line=trim($line);
			if($line[0]=='#'){
				continue;
			}
			$pieces=array();
			$pos=0;
			while( ( $start=strpos($line,"[",$pos) )!==false){
				$start=$start+1;
				$end=strpos($line,"]",$start);
				$pieces[]=trim(substr($line,$start,$end-$start));
				$pos=$end+1;
			}
			if(sizeof($pieces)!=3){
				$msg="Error: Syntax error in <i>".$fullPath."</i>, line <i>".$i."</i>";
				new Error($msg);
			}
			$this->messages[$pieces[0]][$pieces[1]]=$pieces[2];
		}
	}
}
?>

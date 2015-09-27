<?php
class Paginador{
	const LIMITE_POR_PAGINA=10;

	static function crearHtml($paginaActual,$numeroFilas,$uriPagina){
        $paginas=(int)$numeroFilas/self::LIMITE_POR_PAGINA;
        if(($paginas-floor($paginas))!=0){$paginas=floor($paginas)+1;}

		// -> Variables
		$inferior=1;
        $margen=3;
		//echo'URI: '.$uriPagina;
		$contenidoHtml='<table><tr><td>P&aacute;ginas</td>';

		// -> Imprimir limite inferior
		if($paginaActual > $margen+1){
			$contenidoHtml=$contenidoHtml.'<td><a href="'.$uriPagina.'&pagina=1" class="link2">&lt;&lt;</a></td>';
		}
		// -> Declarar el limite superior e inferior
		if($paginaActual <= $margen){
			if($paginas < $margen){
				$inferior=1;
				$superior=$paginas;
			}
			elseif($paginas>= $margen && ($paginaActual+$margen )>$paginas){
				$inferior=1;
				$superior=$paginas;
			}
			else{
				$inferior=1;
				$superior=$paginaActual + $margen;
			}
        }
        elseif($paginaActual > ($paginas-$margen)){
			$inferior=$paginaActual-$margen;
			$superior=$paginas;
		}
        else{
			$inferior=$paginaActual-$margen;
			$superior=$paginaActual+$margen;
		}
		// -> Imprimir las paginas
        for($i=$inferior;$i<=$superior;$i++){
			if($paginaActual==$i){
				$contenidoHtml=$contenidoHtml.'<td><div class="link2Muerto">'.$i.'</div></td>';
			}
			else{
				$contenidoHtml=$contenidoHtml.'<td><a href="'.$uriPagina.'&pagina='.$i.'" class="link2">'.$i.'</a></td>';
			}
        }
		// -> Imprimir limite superior
		if($paginaActual < $paginas-$margen){
			$contenidoHtml=$contenidoHtml.'<td><a href="'.$uriPagina.'&pagina='.$paginas.'" class="link2">&gt;&gt;</a></td>';
		}

		$contenidoHtml=$contenidoHtml.'</tr></table>';

		return($contenidoHtml);
	}
	
	
	static function crearHtmlAjax2($paginaActual,$numeroFilas,$uriPagina,$divPagina, $limite){
		// Se comentó la siguiente línea ya que se agregó el parámetro $limite a 
		// la función para controlar el limite de resultados
        //$paginas=(int)$numeroFilas/self::LIMITE_POR_PAGINA;
		$paginas=(int)$numeroFilas/$limite;
		
        if(($paginas-floor($paginas))!=0){$paginas=floor($paginas)+1;}

		// -> Variables
		$inferior=1;
        $margen=3;
		//echo'URI: '.$uriPagina;
		$contenidoHtml='<table width="100%"><tr><td width=50>P&aacute;ginas</td>';

		// -> Imprimir limite inferior
		if($paginaActual > $margen+1){
			$contenidoHtml=$contenidoHtml.'<td width=10><a style="color: rgb(170, 170, 170); text-decoration: underline; cursor: pointer;" id\u003d\="" prf_d\="" onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina=1\',\''.$divPagina.'\')">&lt;&lt;</a></td>';
		}
		// -> Declarar el limite superior e inferior
		if($paginaActual <= $margen){
			if($paginas < $margen){
				$inferior=1;
				$superior=$paginas;
			}
			elseif($paginas>= $margen && ($paginaActual+$margen )>$paginas){
				$inferior=1;
				$superior=$paginas;
			}
			else{
				$inferior=1;
				$superior=$paginaActual + $margen;
			}
        }
        elseif($paginaActual > ($paginas-$margen)){
			$inferior=$paginaActual-$margen;
			$superior=$paginas;
		}
        else{
			$inferior=$paginaActual-$margen;
			$superior=$paginaActual+$margen;
		}
		// -> Imprimir las paginas
        for($i=$inferior;$i<=$superior;$i++){
			if($paginaActual==$i){
				$contenidoHtml=$contenidoHtml.'<td width=10><div class="link2Muerto">'.$i.'</div></td>';
			}
			else{
				$contenidoHtml=$contenidoHtml.'<td width=10><a style="color: rgb(170, 170, 170); text-decoration: underline; cursor: pointer;" id\u003d\="" prf_d\=""  onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$i.'\',\''.$divPagina.'\')">'.$i.'</a></td>';
			}
        }
		// -> Imprimir limite superior
		if($paginaActual < $paginas-$margen){
			$contenidoHtml=$contenidoHtml.'<td width=10><a style="color: rgb(170, 170, 170); text-decoration: underline; cursor: pointer;" id\u003d\="" prf_d\=""  onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$paginas.'\',\''.$divPagina.'\')">&gt;&gt;</a></td>';
		}

		$contenidoHtml=$contenidoHtml.'<td></td><td align=right width=150>Total de Registros: <strong>'.$numeroFilas.'</strong></td></tr></table>';

		return($contenidoHtml);
	}
	
	
static function crearHtmlAjax($paginaActual,$numeroFilas,$uriPagina,$divPagina, $limite){
	/*
		// Se comentó la siguiente línea ya que se agregó el parámetro $limite a 
		// la función para controlar el limite de resultados
        //$paginas=(int)$numeroFilas/self::LIMITE_POR_PAGINA;
		$paginas=(int)$numeroFilas/$limite;
		
        if(($paginas-floor($paginas))!=0){$paginas=floor($paginas)+1;}

		// -> Variables
		$inferior=1;
        $margen=3;
		//echo'URI: '.$uriPagina;
		//$contenidoHtml='<table width="100%"><tr><td width=50>P&aacute;ginas</td>';
		$contenidoHtml='<div class="btn-toolbar"><div class="btn-group">';

		// -> Imprimir limite inferior
		if($paginaActual > $margen+1){
			//$contenidoHtml=$contenidoHtml.'<td width=10><a style="color: rgb(170, 170, 170); text-decoration: underline; cursor: pointer;" id\u003d\="" prf_d\="" onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina=1\',\''.$divPagina.'\')">&lt;&lt;</a></td>';
			$contenidoHtml=$contenidoHtml.'<button class="btn btn-light" onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina=1\',\''.$divPagina.'\')">&lt;&lt;</button>';

		}
		// -> Declarar el limite superior e inferior
		if($paginaActual <= $margen){
			if($paginas < $margen){
				$inferior=1;
				$superior=$paginas;
			}
			elseif($paginas>= $margen && ($paginaActual+$margen )>$paginas){
				$inferior=1;
				$superior=$paginas;
			}
			else{
				$inferior=1;
				$superior=$paginaActual + $margen;
			}
        }
        elseif($paginaActual > ($paginas-$margen)){
			$inferior=$paginaActual-$margen;
			$superior=$paginas;
		}
        else{
			$inferior=$paginaActual-$margen;
			$superior=$paginaActual+$margen;
		}
		// -> Imprimir las paginas
        for($i=$inferior;$i<=$superior;$i++){
			if($paginaActual==$i){
				$contenidoHtml=$contenidoHtml.'<button class="btn btn-light">' . $i . '</button>';
			}
			else{
				$contenidoHtml=$contenidoHtml.'
				<button class="btn btn-light" onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$i.'\',\''.$divPagina.'\')">' . $i . '</button>';
			}
        }
		// -> Imprimir limite superior
		if($paginaActual < $paginas-$margen){
			$contenidoHtml=$contenidoHtml.'<button class="btn btn-light" onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$paginas.'\',\''.$divPagina.'\')">&gt;&gt;</button>';
		}

		$contenidoHtml=$contenidoHtml.'</div></div>';
		$contenidoHtml=$contenidoHtml.'Total de Registros: <strong>'.$numeroFilas.'</strong>';

		return($contenidoHtml);
		*/
		
		$paginas=(int)$numeroFilas/$limite;		
        if(($paginas-floor($paginas))!=0){$paginas=floor($paginas)+1;}
		$inferior=1;
        $margen=3;
		$contenidoHtml='<ul class="pagination">';
		// -> Imprimir limite inferior
		if($paginaActual > $margen+1){
			$contenidoHtml=$contenidoHtml.'
			<li> <a onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina=1\',\''.$divPagina.'\')"> <i class="icon-double-angle-left"></i> </a> </li>
			';

		}else {
			$contenidoHtml=$contenidoHtml.'
			<li class="disabled"> <a> <i class="icon-double-angle-left"></i> </a> </li>
			';
			
		}
		// -> Declarar el limite superior e inferior
		if($paginaActual <= $margen){
			if($paginas < $margen){
				$inferior=1;
				$superior=$paginas;
			}
			elseif($paginas>= $margen && ($paginaActual+$margen )>$paginas){
				$inferior=1;
				$superior=$paginas;
			}
			else{
				$inferior=1;
				$superior=$paginaActual + $margen;
			}
        }
        elseif($paginaActual > ($paginas-$margen)){
			$inferior=$paginaActual-$margen;
			$superior=$paginas;
		}
        else{
			$inferior=$paginaActual-$margen;
			$superior=$paginaActual+$margen;
		}
		// -> Imprimir las paginas
        for($i=$inferior;$i<=$superior;$i++){
			if($paginaActual==$i){
				$contenidoHtml=$contenidoHtml.'<li class="active"> <a>' . $i . '</a> </li>';
			}
			else{
				$contenidoHtml=$contenidoHtml.'
				<li> <a onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$i.'\',\''.$divPagina.'\')">' . $i . '</a> </li>';
			}
        }
		// -> Imprimir limite superior
		if($paginaActual < $paginas-$margen){
			$contenidoHtml=$contenidoHtml.'
			<li> <a onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$paginas.'\',\''.$divPagina.'\')"> <i class="icon-double-angle-right"></i> </a> </li>';
		}else{
			$contenidoHtml=$contenidoHtml.'
			<li class="disabled"> <a> <i class="icon-double-angle-right"></i> </a> </li>';
			
		}
		$contenidoHtml=$contenidoHtml.'</ul>';
		//$contenidoHtml=$contenidoHtml.'Total de Registros: <strong>'.$numeroFilas.'</strong>';
		$respuesta = '
		<div class="row">
		  <div class="col-sm-6">
			<div>Total de <strong>'.$numeroFilas.'</strong> registros</div>
		  </div>
		  <div class="col-sm-6">
			<div class="dataTables_paginate paging_bootstrap">
			  '. $contenidoHtml . '
			</div>
		  </div>
		</div>
		';
		return($respuesta);
	}					
	static function crearNewHtmlAjax($paginaActual,$numeroFilas,$uriPagina,$divPagina, $limite){
		$paginas=(int)$numeroFilas/$limite;		
        if(($paginas-floor($paginas))!=0){$paginas=floor($paginas)+1;}
		$inferior=1;
        $margen=3;
		$contenidoHtml='<ul class="pagination">';
		// -> Imprimir limite inferior
		if($paginaActual > $margen+1){
			$contenidoHtml=$contenidoHtml.'
			<li> <a onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina=1\',\''.$divPagina.'\')"> <i class="icon-double-angle-left"></i> </a> </li>
			';

		}else {
			$contenidoHtml=$contenidoHtml.'
			<li class="disabled"> <a> <i class="icon-double-angle-left"></i> </a> </li>
			';
			
		}
		// -> Declarar el limite superior e inferior
		if($paginaActual <= $margen){
			if($paginas < $margen){
				$inferior=1;
				$superior=$paginas;
			}
			elseif($paginas>= $margen && ($paginaActual+$margen )>$paginas){
				$inferior=1;
				$superior=$paginas;
			}
			else{
				$inferior=1;
				$superior=$paginaActual + $margen;
			}
        }
        elseif($paginaActual > ($paginas-$margen)){
			$inferior=$paginaActual-$margen;
			$superior=$paginas;
		}
        else{
			$inferior=$paginaActual-$margen;
			$superior=$paginaActual+$margen;
		}
		// -> Imprimir las paginas
        for($i=$inferior;$i<=$superior;$i++){
			if($paginaActual==$i){
				$contenidoHtml=$contenidoHtml.'<li class="active"> <a>' . $i . '</a> </li>';
			}
			else{
				$contenidoHtml=$contenidoHtml.'
				<li> <a onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$i.'\',\''.$divPagina.'\')">' . $i . '</a> </li>';
			}
        }
		// -> Imprimir limite superior
		if($paginaActual < $paginas-$margen){
			$contenidoHtml=$contenidoHtml.'
			<li> <a onclick="cargarFormulario(\'formulario\',\''.$uriPagina.'&pagina='.$paginas.'\',\''.$divPagina.'\')"> <i class="icon-double-angle-right"></i> </a> </li>';
		}else{
			$contenidoHtml=$contenidoHtml.'
			<li class="disabled"> <a> <i class="icon-double-angle-right"></i> </a> </li>';
			
		}
		$contenidoHtml=$contenidoHtml.'</ul>';
		//$contenidoHtml=$contenidoHtml.'Total de Registros: <strong>'.$numeroFilas.'</strong>';
		$respuesta = '
		<div class="row">
		  <div class="col-sm-6">
			<div>Total de <strong>'.$numeroFilas.'</strong> registros</div>
		  </div>
		  <div class="col-sm-6">
			<div class="dataTables_paginate paging_bootstrap">
			  '. $contenidoHtml . '
			</div>
		  </div>
		</div>
		';
		return($respuesta);
	}		
	
	
	static function getMinimo($paginaActual, $limite){
		// Se comentó la siguiente línea ya que se agregó el parámetro $limite a 
		// la función para controlar el limite de resultados
		//return(($paginaActual-1)*self::LIMITE_POR_PAGINA);
		return(($paginaActual-1)*$limite);
	}

}
?>
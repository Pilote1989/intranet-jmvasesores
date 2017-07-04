<?php
/**
 * Class Error
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 * Error class writes any error and abort the request.
 * Modify it according to your needs.
 *
 * PUBLIC METHODS
 *	Error(&$errorMessage)
*/

define("E_VALIDACION_INCORRECTO", 1);
define("E_VALIDACION_OBLIGATORIO", 2);

class Error{
	function __construct(&$errorMessage = null){
        if (!$errorMessage) {
            return;
        }

		$fc=&FrontController::instance();
		echo '
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
        <title>eBase - JMV Asesores</title>
        <base href="http://intranetjmvasesores-pilote1989.c9.io:80/">

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<meta http-equiv="Cache-control" content="public">
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="ace-1.3.5/css/bootstrap.css" />
		<link rel="stylesheet" href="ace-1.3.5/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="ace-1.3.5/css/jquery-ui.css" />
        <link rel="stylesheet" href="ace-1.3.5/css/bootstrap-datetimepicker.css" />
        <link rel="stylesheet" href="ace-1.3.5/css/bootstrap-datepicker3.css" />
        <link rel="stylesheet" href="ace-1.3.5/css/daterangepicker.css" />
        <link rel="stylesheet" href="ace-1.3.5/css/ui.jqgrid.css" />
		<link rel="stylesheet" href="ace-1.3.5/css/jquery.gritter.css" />


		

		<!-- text fonts -->
		<link rel="stylesheet" href="ace-1.3.5/css/ace-fonts.css" />
		<link rel="stylesheet" href="ace-1.3.5/css/chosen.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="ace-1.3.5/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
        <link rel="stylesheet" href="ace-1.3.5/css/ace-rtl.css" />
		<link rel="stylesheet" href="ace-1.3.5/css/ace-skins.css" />
        <link rel="stylesheet" href="ace-1.3.5/css/animate.css" />
        <link rel="stylesheet" href="ace-1.3.5/css/bootstrap-responsive-tabs.css" />
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="ace-1.3.5/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="ace-1.3.5/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="ace-1.3.5/js/ace-extra.js"></script>
		<style>
		html {
			overflow: -moz-scrollbars-vertical;
			overflow: scroll;
		}
		</style>		

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="ace-1.3.5/js/html5shiv.js"></script>
		<script src="ace-1.3.5/js/respond.js"></script>
		<![endif]-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <!-- Ajax -->
        <script type="text/javascript" src="jScripts/ajax_core.js"></script>
        <script type="text/javascript" src="jScripts/ajform.js"></script>
        
        <!--Fin Ajax-->
	</head>
<body>
<!-- basic scripts --> 
<!--[if !IE]> -->
						<script type="text/javascript">
							window.jQuery || document.write("<script src=\'assets/js/jquery-2.0.3.min.js\'>"+"<"+"/script>");
						</script>
						<!-- <![endif]--> 
<!--[if IE]>
						<script type="text/javascript">
						 window.jQuery || document.write("<script src=\'assets/js/jquery-1.10.2.min.js\'>"+"<"+"/script>");
						</script>
						<![endif]--> 
<script type="text/javascript">
							if("ontouchend" in document) document.write("<script src=\'assets/js/jquery.mobile.custom.min.js\'>"+"<"+"/script>");
						</script> 
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/typeahead-bs2.min.js"></script> 

<!-- page specific plugin scripts --> 
<script src="assets/js/jquery-ui-1.10.3.full.min.js"></script> 
<script src="assets/js/jquery.ui.touch-punch.min.js"></script> 
<script src="assets/js/jquery.dataTables.min.js"></script> 
<script src="assets/js/jquery.dataTables.bootstrap.js"></script> 
<script src="assets/js/fuelux/fuelux.wizard.min.js"></script> 
<script src="assets/js/additional-methods.min.js"></script> 
<script src="assets/js/bootbox.min.js"></script> 
<script src="assets/js/jquery.maskedinput.min.js"></script> 
<script src="assets/js/select2.min.js"></script> 
<script src="assets/js/date-time/bootstrap-datepicker.min.js"></script> 
<script src="assets/js/chosen.jquery.min.js"></script> 
<script src="assets/js/jquery.validate.min.js"></script> 
<script src="assets/js/date-time/moment.min.js"></script> 
<script src="assets/js/date-time/daterangepicker.min.js"></script> 

<!-- ace scripts --> 

<script src="assets/js/ace-elements.min.js"></script> 
<script src="assets/js/ace.min.js"></script>
<div class="navbar navbar-default" id="navbar">
  <script type="text/javascript">
						try{ace.settings.check("navbar" , "fixed")}catch(e){}
					</script> 
  <!-- /.container -->
</div>
<div class="main-container" id="main-container">
  <script type="text/javascript">
						try{ace.settings.check("main-container" , "fixed")}catch(e){}
					</script>
  <div class="main-container-inner">
    <a class="menu-toggler" id="menu-toggler" href="#"> <span class="menu-text"></span> </a>
    <div class="page-content">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          
          <div class="error-container">
            <div class="well">
              <h1 class="grey lighter smaller"> <span class="blue bigger-125"> <i class="ace-icon fa fa-sitemap"></i> 404 </span> Pagina No Encontrada </h1>
              <hr>
              <h3 class="lighter smaller">¡Buscamos por todo el sitio pero no la encontramos!</h3>
              <div>
                <div class="space">
                </div>
                <h4 class="smaller">Intente lo siguiente:</h4>
                <ul class="list-unstyled spaced inline bigger-110 margin-15">
                  <li> <i class="ace-icon fa fa-hand-o-right blue"></i> Revise la direccion. </li>
                  <li> <i class="ace-icon fa fa-hand-o-right blue"></i> Regrese a la pagina anterior. </li>
                  <li> <i class="ace-icon fa fa-hand-o-right blue"></i> Avisele al programador. </li>
                </ul>
              </div>
              <hr>
              <div class="space">
              </div>
              <div class="center">
                <a href="javascript:history.back()" class="btn btn-grey"> <i class="ace-icon fa fa-arrow-left"></i> Regresar </a>
              </div>
            </div>
          </div>
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
    </div>
  </div>
  <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse"> <i class="ace-icon fa fa-double-angle-up ace-icon fa fa-only bigger-110"></i> </a>
</div>
</body>
</html>		
		';
		exit;
	}

    function display($tipo, $informacion) {
        switch ($tipo) {
        
            case E_VALIDACION_INCORRECTO:
                $mensaje = "La información del campo \"$informacion\" es incorrecta.";
                break; 

            case E_VALIDACION_OBLIGATORIO:
                $mensaje = "El campo \"$informacion\" es obligatorio.";
                break;

        }

        header("HTTP/1.1 500 Internal Server Error");
        if ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
            echo json_encode(array("error" => $mensaje, "contenido" => array()));
        }
        else {
            $smarty = new Smarty();
            $smarty->compile_dir  = "templates_c";
            $smarty->template_dir = "templates";
            $smarty->assign("mensaje", $mensaje);
            $smarty->display("layouts/error.tpl");
        }

        exit();
    }
}
?>
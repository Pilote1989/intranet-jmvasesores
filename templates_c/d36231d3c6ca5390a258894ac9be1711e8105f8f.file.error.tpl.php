<?php /* Smarty version Smarty3rc4, created on 2015-08-15 21:50:11
         compiled from "templates/layouts/error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14763477155d016838335e3-98824216%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd36231d3c6ca5390a258894ac9be1711e8105f8f' => 
    array (
      0 => 'templates/layouts/error.tpl',
      1 => 1312862540,
    ),
  ),
  'nocache_hash' => '14763477155d016838335e3-98824216',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<html lang="es">
	<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>Un Techo para mi País - Pilote</title>
		<link rel="stylesheet" type="text/css" href="styles/main.css">
        <link rel="shortcut icon" href="favicon.ico">
	</head>
	<body class="uix-error">
        <h1>Error</h1>
        <div>
            <?php echo $_smarty_tpl->getVariable('mensaje')->value;?>

        </div>
        <a href="javascript:history.back(1);">Regresa a la página anterior</a>
	</body>
</html>

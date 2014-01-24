<? include_once("head.php");?>
<body onload="mueveReloj()">
<?
//selecciono todas las empresas 
$query="SELECT * FROM `estacionamiento` WHERE id_estado=1";   
$estacionamiento=mysql_query($query) or die(mysql_error());
$row_estacionamiento = mysql_fetch_assoc($estacionamiento);
mysql_query("SET NAMES 'utf8'");
$numero_filas = mysql_num_rows($estacionamiento);
if($numero_filas>1){
	echo "Hay mas de un estacionamiento activo, seleccione el deseado.";
}
?>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
							Cabecera
----------------------------------------------------------------------
--------------------------------------------------------------------->
<!-- Delete everything in this .container and get started on your own site! -->
	<header id="cabecera">
		<div class="container">
			<a id="logo" href="">Park<span class="gris">ing</span> <p><span class="gris">TMS</span> Group</p></a>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
							Menu principal
----------------------------------------------------------------------
--------------------------------------------------------------------->	
			<nav id="navigation">
                <a href="index.php">Inicio</a>
                <a href="caja.php">Cerrar Caja</a>
				<a href="cajas.php">Cajas</a>
                <a href="tarifas.php">Tarifas</a>
				<a href="codigos.php">Tarjetas</a>
				<a href="codigo.php">Crear</a>
                <!--<a href="http://dariobf.com/contacto/">Contacto</a>-->
            </nav>
        </div>
    </header>	
	<div id="content" class="container">	


		

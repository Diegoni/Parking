<?php 
session_start();
	if($_SESSION['id_tipousuario']!=1){
	header("Location: index.php");
	}
include_once("menu.php");
$query		= "SELECT * FROM `tipo`";   
$tipo		= mysql_query($query) or die(mysql_error());
$row_tipo	= mysql_fetch_assoc($tipo);
?>
<table> 
<form action="codigo.php">
<tr>
<td>Tipo</td>
<td><select name="tipo">
	<?php do{?>
	<option value="<?php echo $row_tipo['id_tipo'];?>"><?php echo $row_tipo['tipo'];?></option>
	<?php }while ($row_tipo = mysql_fetch_array($tipo));?>
	</select>
</td>
</tr>
<tr>
<td>Codigo</td>
<td><input name="codigo" value="<?php echo rand(0,99999999999);?>"></input></td>
</tr>
<tr>
<td></td>
<td><button type="submit" name="generar" value="1">Generar codigo</button></td>
</tr>
</form>
</table>





<?php 
if(isset($_GET['generar'])){

$query="SELECT * FROM `tarjeta` WHERE codigo='$_GET[codigo]'";   
$tarjeta=mysql_query($query) or die(mysql_error());
$row_tarjeta = mysql_fetch_assoc($tarjeta);
$numero_tarjetas = mysql_num_rows($tarjeta);


if($numero_tarjetas>0){
	echo "Ese codigo ya fue ingresado por favor ingrese uno nuevamente";
}else{
?>
<form action="codigo.php" id="formulario" border="1">
<div class="tarjeta">

<table>
<tr>
	<td colspan="2">Código de Barra </td>
</tr>

  <?php
  if(strlen($_GET['codigo'])<13){ 
		$cantidad=strlen($_GET['codigo']);
		$Cadena=$_GET['codigo'];
		for ($cantidad; $cantidad < 13; $cantidad++) {
			$Cadena='0'.$Cadena;
		}	
	}else{
		$Cadena=$_GET['codigo'];
	}
  ?>
<tr>
	<td colspan="2"><img src="barcode.php?code=<?php echo $Cadena;?>&scale=2"></td>
</tr>
<tr>
<td>Tipo :</td>
<?php
$tipo=mysql_query("SELECT * FROM `tipo` WHERE id_tipo='$_GET[tipo]'") or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
?>

<td><?php echo $row_tipo['tipo'];?></td>
</tr>
<tr>
<!--<td><button type="submit" name="guardar" value="1">Guardar</button></td>-->
<td></td>
</tr>
</table>
</div>
<br>
<br>
<td><a href="#" class="button"  title="guardar" onClick="abrirVentana('imprimir.php?codigo=<?php echo $Cadena;?>&tipo=<?php echo $_GET['tipo'];?>')">Imprimir</a></td>
</form>
<?php 
}
}

?>

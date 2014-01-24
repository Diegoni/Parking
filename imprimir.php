
<?include_once("config/database.php");?>
<style>
.tarjeta{
	border:1px solid;
	padding:30px;
	border-radius:25px;

}
</style>
<?
$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo) WHERE codigo='$_GET[codigo]'";   
$tarjeta=mysql_query($query) or die(mysql_error());
$row_tarjeta = mysql_fetch_assoc($tarjeta);
$numero_tarjetas = mysql_num_rows($tarjeta);
if($numero_tarjetas>0){
}else{
mysql_query("INSERT INTO tarjeta(
								codigo,
								id_tipo,
								id_estacionamiento,
								id_estado) 
							VALUES(
								'$_GET[codigo]',
								'$_GET[tipo]',
								'$row_estacionamiento[id_estacionamiento]',
								1
								)") or die(mysql_error());
}

?>
<body onload="window.print(); window.close();">
<center>
<div class="tarjeta">
<table>
<tr>
<td colspan="2"><center><img src="barcode.php?code=<?echo $_GET['codigo'];?>&scale=2"></center></td>
</tr>
<tr>
<td>Tipo :</td>
<?
$tipo=mysql_query("SELECT * FROM `tipo` WHERE id_tipo='$_GET[tipo]'") or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
?>

<td><?echo $row_tipo['tipo'];?></td>
</tr>
</table>
</div>
</center>

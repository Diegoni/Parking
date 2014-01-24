<?php include_once("menu.php"); 


	
	if(isset($_GET['guardar'])){
	$query="SELECT * FROM `tarifa` WHERE tarifa.id_estado=1";   
	$tarifa2=mysql_query($query) or die(mysql_error());
	$row_tarifa2 = mysql_fetch_assoc($tarifa2);
	$numero_tarifas2 = mysql_num_rows($tarifa2);
	
	do {
	$inicial_min=$_GET['inicial_min'.$row_tarifa2['id_tarifa']];
	$valor_inicial=$_GET['valor_inicial'.$row_tarifa2['id_tarifa']];
	$extra_min=$_GET['extra_min'.$row_tarifa2['id_tarifa']];
	$tolerancia_min=$_GET['tolerancia_min'.$row_tarifa2['id_tarifa']];
	$valor_extra=$_GET['valor_extra'.$row_tarifa2['id_tarifa']];
	mysql_query("UPDATE `tarifa` SET 
						inicial_min='$inicial_min',
						valor_inicial='$valor_inicial',
						extra_min='$extra_min',
						tolerancia_min='$tolerancia_min',
						valor_extra='$valor_extra'
						WHERE id_tarifa='$row_tarifa2[id_tarifa]'
						") or die(mysql_error());
	
	
	}while ($row_tarifa2 = mysql_fetch_array($tarifa2));
	?>

	<?
	}
	
	$query="SELECT * FROM `tarifa` INNER JOIN `tipo` on(tarifa.id_tipo=tipo.id_tipo) WHERE tarifa.id_estado=1 ORDER BY tipo.tipo";   
	$tarifa=mysql_query($query) or die(mysql_error());
	$row_tarifa = mysql_fetch_assoc($tarifa);
	$numero_tarifas = mysql_num_rows($tarifa);
?>

<table border="1">
<tr>
	<td></td>
	<td align="center" colspan="2">Inicia</td>
	<td align="center" colspan="3">Extra</td>
</tr>
<tr>
	<td>Tipo</td>
	<td>Minutos</td>
	<td>$ Valor</td>
	<td>Minutos</td>
	<td>Tolerancia</td>
	<td>$ Valor</td>
</tr>
<form action="tarifas.php" name="tarifas">
<?do {?>
<tr>
	<td><?echo $row_tarifa['tipo']?></td>
	<td><input size="5" type="text" name="inicial_min<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['inicial_min']?>"></td>
	<td><input size="5" type="text" name="valor_inicial<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['valor_inicial']?>"></td>
	<td><input size="5" type="text" name="extra_min<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['extra_min']?>"></td>
	<td><input size="5" type="text" name="tolerancia_min<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['tolerancia_min']?>"></td>
	<td><input size="5" type="text" name="valor_extra<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['valor_extra']?>"></td>
		<input type="hidden" name="id_tarifa" value="<?echo $row_tarifa['id_tarifa']?>">
</tr>
<? }while ($row_tarifa = mysql_fetch_array($tarifa)) ?>
<tr>
	<td align="center" colspan="6"><button type="submit" value="1" name="guardar">Guardar cambios</button></td>
</tr>

</table>




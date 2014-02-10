<?php 
session_start();

	if($_SESSION['id_tipousuario']!=1){
	header("Location: index.php");
	}

include_once("menu.php"); 


	
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
	}
	
	if(isset($_GET['cambiar_estacionamiento'])){
	mysql_query("UPDATE estacionamiento SET
					estacionamiento='$_GET[estacionamiento]',
					cant_lugares='$_GET[cant_lugares]'") or die(mysql_error());
	}
	
	$query="SELECT * FROM `tarifa` INNER JOIN `tipo` on(tarifa.id_tipo=tipo.id_tipo) WHERE tarifa.id_estado=1 ORDER BY tipo.tipo";   
	$tarifa=mysql_query($query) or die(mysql_error());
	$row_tarifa = mysql_fetch_assoc($tarifa);
	$numero_tarifas = mysql_num_rows($tarifa);
?>

<table id="tfhover" class="tftablechica">
<tr>
	<th></td>
	<th align="center" colspan="2">Inicia</th>
	<th align="center" colspan="3">Extra</th>
</tr>
<tr>
	<th>Tipo</th>
	<th>Minutos</th>
	<th>$ Valor</th>
	<th>Minutos</th>
	<th>Tolerancia</th>
	<th>$ Valor</th>
</tr>
<form action="config.php" name="tarifas">
<?do {?>
<tr>
	<th><?echo $row_tarifa['tipo']?></th>
	<td><input size="5" type="text" name="inicial_min<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['inicial_min']?>"></td>
	<td><input size="5" type="text" name="valor_inicial<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['valor_inicial']?>"></td>
	<td><input size="5" type="text" name="extra_min<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['extra_min']?>"></td>
	<td><input size="5" type="text" name="tolerancia_min<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['tolerancia_min']?>"></td>
	<td><input size="5" type="text" name="valor_extra<?echo $row_tarifa['id_tarifa']?>" value="<?echo $row_tarifa['valor_extra']?>"></td>
		<input type="hidden" name="id_tarifa" value="<?echo $row_tarifa['id_tarifa']?>">
</tr>
<? }while ($row_tarifa = mysql_fetch_array($tarifa)) ?>
<tr>
	<td align="center" colspan="6">
		<!--<button type="submit" value="1" name="agregar">Agregar tipo</button>-->
		<button type="submit" value="1" name="guardar">Guardar cambios</button>
	</td>
</tr>

</table>

<br>
<table id="tfhover" class="tftablechica">
<tr>
	<th>Nombre</th>
	<th>Cantidad de lugares</th>
</tr>
<form action="config.php" name="tarifas">
<tr>
	<td><input size="25" type="text" name="estacionamiento" value="<?echo $row_estacionamiento['estacionamiento']?>"></td>
	<td><input size="5" type="text" name="cant_lugares" value="<?echo $row_estacionamiento['cant_lugares']?>"></td>
</tr>
<tr>
	<td align="center" colspan="6">
	<button type="submit" value="1" name="cambiar_estacionamiento">Guardar cambios</button></td>
</tr>
</table>



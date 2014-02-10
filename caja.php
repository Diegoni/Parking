<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: index.php");
	}
include_once("menu.php"); 
if(isset($_GET['fecha'])){
	$fecha_americana=date( "Y-d-m", strtotime( $_GET['fecha'] ) );
	$fecha=$_GET['fecha'];
	$query="SELECT *
			FROM estadia
			INNER JOIN tipo ON(estadia.id_tipo=tipo.id_tipo)
			INNER JOIN usuarios ON(estadia.id_usuario_salida=usuarios.usuario_id)
			INNER JOIN estado ON(estadia.id_estado=estado.id_estado)
			INNER JOIN tarjeta ON(estadia.id_tarjeta=tarjeta.id_tarjeta)
			WHERE DATE_FORMAT(salida, '%Y-%m-%d') = '$fecha_americana' 
			AND estadia.id_caja=0;";   
	$estadia=mysql_query($query) or die(mysql_error());
	$row_estadia = mysql_fetch_assoc($estadia);
	$numero_estadias = mysql_num_rows($estadia);
	
	$total=0;
}?>

<? if(isset($_GET['cerrar'])){
	$fecha_americana=date( "Y-m-d",strtotime( $_GET['fecha'] ));
	if($_GET['comentario']==""){

	mysql_query("INSERT INTO caja(
				fecha,
				valor_real,
				valor_medido,
				cant_estadia,
				id_usuario,
				id_estacionamiento)
			VALUES (
				'$fecha_americana',
				'$_GET[valor_real]',
				'$_GET[valor_medido]',
				'$_GET[cant_estadia]',
				'$row_estadia[usuario_id]',
				'$row_estacionamiento[id_estacionamiento]'
				)") or die(mysql_error());
	$id_caja = mysql_insert_id();
	mysql_query("UPDATE estadia SET estadia.id_caja='$id_caja' WHERE DATE_FORMAT(entrada, '%Y-%m-%d') = '$fecha_americana' AND estadia.id_caja=0 AND estadia.id_estado=0;") or die(mysql_error());   
	
	}else{
	mysql_query("INSERT INTO nota(nota)VALUES (	'$_GET[comentario]')") or die(mysql_error());
	$id_nota = mysql_insert_id(); 
				
	mysql_query("INSERT INTO caja(
				fecha,
				valor_real,
				valor_medido,
				cant_estadia,
				id_nota,
				id_usuario,
				id_estacionamiento)
			VALUES (
				'$fecha_americana',
				'$_GET[valor_real]',
				'$_GET[valor_medido]',
				'$_GET[cant_estadia]',
				'$id_nota',
				'$row_estadia[usuario_id]',
				'$row_estacionamiento[id_estacionamiento]'
				)") or die(mysql_error());
		$id_caja = mysql_insert_id();
	mysql_query("UPDATE estadia SET estadia.id_caja='$id_caja' WHERE DATE_FORMAT(entrada, '%Y-%m-%d') = '$fecha_americana' AND estadia.id_caja=0 AND estadia.id_estado=0;") or die(mysql_error());   
	

	}
}
?>

<form action="caja.php" name="caja">
<table>
<tr>
<td>Fecha caja</td>
<td><input type="text" id="datepicker" name="fecha" placeholder="Seleccione una fecha" required></td>
<td><button type="submit" name="caja" value="1" class="button">ok</button></td>
</tr>
</table>
</form>

<?if(isset($_GET['caja'])){
if($numero_estadias==0){
	echo "No se registran movimientos en la fecha ";
	echo $fecha;
}else{	
?>
<table id="tfhover" class="tftable">
<tr>
	<th>Usuario-Salida</th>
	<th>Tarjeta</th>
	<th>Entrada</th>
	<th>Salida</th>
	<th>Estado</th>
	<th>Tipo</th>
	<th>Minutos</th>
	<th>Valor</th>
</tr>
<?do{?>
<tr>
	<td><?echo $row_estadia['usuario_nombre']?></td>
	<td><?echo $row_estadia['codigo']?></td>
	<td><?echo date("H:i:s", strtotime( $row_estadia['entrada']))?></td>
	<td><?echo date("H:i:s", strtotime( $row_estadia['salida']))?></td>
	<td><?echo $row_estadia['estado']?></td>
	<td><?echo $row_estadia['tipo']?></td>
	<td><?echo $row_estadia['min']?></td>
	<td>$ <?echo $row_estadia['monto']?></td>
	<?$total=$total+$row_estadia['monto']?>
	
</tr>
<? }while ($row_estadia = mysql_fetch_array($estadia));?>


<tr>
	<th colspan="2"><strong>Fecha</strong></th>
	<td colspan="2"><? echo $fecha?></td>
	<th colspan="2"><strong>Total</strong></th>
	<td colspan="2">$ <?echo $total;?></td>
</tr>
<tr>
	<form action="caja.php" name="caja">
	<th colspan="4" align="center"><center><a href="ver.php?fecha=<? echo $fecha;?>" target="main" class="button">Exportar</a></button></center></th>
	<th colspan="4" align="center"><center><button type="submit" name="guardar" value="1">Cerrar Caja</button></center></th>
	<input type="hidden" name="fecha" value="<? echo $fecha;?>">
	<input type="hidden" name="valor_medido" value="<? echo $total;?>">
	<input type="hidden" name="cantidad" value="<? echo $numero_estadias;?>">
	</form>
</tr>

</table>

<?}
}?>
<? if(isset($_GET['guardar'])){?>
<form action="caja.php" name="caja2">
<table id="tfhover" class="tftable">
<tr>
<td><label>Fecha</label></td>
<td><input name="fecha" value="<?echo $_GET['fecha']?>" readonly disabled></td>
</tr>
<tr>
<td><label>Valor Medido</label></td>
<td><input name="valor_medido" value="<?echo $_GET['valor_medido']?>" readonly disabled></td>
</tr>
<tr>
<td><label>Valor Real</label></td>
<td><input name="valor_real" value="" required></td>
</tr>
<tr>
<td><label>Cantidad de autos</label></td>
<td><input name="cant_estadia" value="<?echo $_GET['cantidad']?>" readonly disabled></td>
<tr>
<tr>
<td><label>Comentario</label></td>
<td><textarea name="comentario" value=""></textarea></td>
<tr>
<tr>
<script language="JavaScript">
document.caja2.valor_real.focus();
</script>
<td><center><button name="cancelar" value="1">Cancelar</button></center></td>
<td><center><button name="cerrar" onClick="abrirVentana('imprimircaja.php?fecha=<?echo $_GET['fecha']?>" value="1">Cerrar Caja</button></center></td>
<tr>
</table>
</form>
<? } ?>


<? if(isset($_GET['cerrar'])){?>
La caja se ha guardado correctamente
<? } ?>

	
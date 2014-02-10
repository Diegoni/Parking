<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: index.php");
	}
include_once("head.php"); 

	$query="SELECT *
			FROM estadia
			INNER JOIN tipo ON(estadia.id_tipo=tipo.id_tipo)
			INNER JOIN usuarios ON(estadia.id_usuario_salida=usuarios.usuario_id)
			INNER JOIN estado ON(estadia.id_estado=estado.id_estado)
			INNER JOIN tarjeta ON(estadia.id_tarjeta=tarjeta.id_tarjeta)
			WHERE estadia.id_caja='$_GET[caja]';";   
	$estadia=mysql_query($query) or die(mysql_error());
	$row_estadia = mysql_fetch_assoc($estadia);
	$numero_estadias = mysql_num_rows($estadia);
	
	$query="SELECT * FROM caja
			INNER JOIN nota ON(caja.id_nota=nota.id_nota)
			WHERE caja.id_caja='$_GET[caja]';";   
	$caja=mysql_query($query) or die(mysql_error());
	$row_caja = mysql_fetch_assoc($caja);
	$numero_cajas = mysql_num_rows($caja);
	
	if($numero_cajas==0){
	$query="SELECT * FROM caja WHERE caja.id_caja='$_GET[caja]';";   
	$caja=mysql_query($query) or die(mysql_error());
	$row_caja = mysql_fetch_assoc($caja);
	$numero_cajas = mysql_num_rows($caja);
	
	}
	
	$total=0;

?>
<body onload="window.print()">
<?
if($numero_estadias==0){
	echo "No se registran movimientos en la fecha ";
	echo $fecha;
}else{	
?>
<? $fecha=date( "d-m-Y", strtotime( $row_caja['fecha'] ) );?>
Fecha: <? echo $fecha?>
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
	<th colspan="2"><strong>Real</strong></th>
	<td colspan="2">$ <?echo $row_caja['valor_real'];?></td>
	<th colspan="2"><strong>Medido</strong></th>
	<td colspan="2">$ <?echo $total;?></td>.
</tr>

</table>

<?}
if($numero_cajas==1){
echo "comentario: ";
echo $row_caja['nota'];
}?>

	
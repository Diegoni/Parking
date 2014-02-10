<? 
session_start();
	if($_SESSION['id_tipousuario']!=1){
	header("Location: index.php");
	}
	
include_once("menu.php");

$total_medido=0;
$total_real=0;
$total_estadia=0;


$fecha_inicio=date( "Y-m-d", strtotime( $_GET['inicio'] ) );
$fecha_final=date( "Y-m-d", strtotime( $_GET['final'] ) );

$query="SELECT * FROM `caja` 
		INNER JOIN usuarios ON(caja.id_usuario=usuarios.usuario_id) 
		WHERE 
		DATE_FORMAT(fecha, '%Y-%m-%d')>='$fecha_inicio' 
		AND 
		DATE_FORMAT(fecha, '%Y-%m-%d')<='$fecha_final';";  
$caja=mysql_query($query) or die(mysql_error());
$row_caja = mysql_fetch_assoc($caja);
$numero_cajas = mysql_num_rows($caja);

?>
<body onload="window.print()">
<table id="tfhover" class="tftable">
<tr>
<th>Fecha</th>
<th>Usuario</th>
<th>Valor Medido</th>
<th>Valor Real</th>
<th>Cantidad de estadias</th>
</tr>
<?do{?>
<tr>
<td><a href="#" class="button" style="padding: 5px 5px 5px 5px;"  title="guardar" onClick="abrirVentana('vercaja.php?caja=<?echo $row_caja['id_caja']?>')"><? echo date( "d-m-Y", strtotime( $row_caja['fecha'] ) );?></a></td>
<td><? echo $row_caja['usuario_nombre'];?></td>
<td>$ <? echo $row_caja['valor_medido'];?></td>
<td>$ <? echo $row_caja['valor_real'];?></td>
<td><? echo $row_caja['cant_estadia'];?></td>
<?
$total_medido=$total_medido+$row_caja['valor_medido'];
$total_real=$total_real+$row_caja['valor_real'];
$total_estadia=$total_estadia+$row_caja['cant_estadia'];
?>
</tr>
<? }while ($row_caja = mysql_fetch_array($caja)) ?>

<tr>
<th colspan="2">Totales</th>
<td>$ <? echo $total_medido;?></td>
<td>$  <? echo $total_real;?></td>
<td><? echo $total_estadia;?></td>
</tr>
</table>
</body>
<?php
session_start();
	if($_SESSION['id_tipousuario']!=1){
	header("Location: index.php");
	}
	
include_once("menu.php");

$total_medido=0;
$total_real=0;
$total_estadia=0;

if(isset($_GET['buscar'])){
$fecha_inicio=date( "Y-d-m", strtotime( $_GET['inicio'] ) );
$fecha_final=date( "Y-d-m", strtotime( $_GET['final'] ) );

$query="SELECT * FROM `caja` 
		INNER JOIN usuarios ON(caja.id_usuario=usuarios.usuario_id) 
		WHERE 
		DATE_FORMAT(fecha, '%Y-%m-%d')>='$fecha_inicio' 
		AND 
		DATE_FORMAT(fecha, '%Y-%m-%d')<='$fecha_final';";  
$caja=mysql_query($query) or die(mysql_error());
$row_caja = mysql_fetch_assoc($caja);
$numero_cajas = mysql_num_rows($caja);
}else{

$query="SELECT * FROM `caja` INNER JOIN usuarios ON(caja.id_usuario=usuarios.usuario_id) LIMIT 0,20";   
$caja=mysql_query($query) or die(mysql_error());
$row_caja = mysql_fetch_assoc($caja);
$numero_cajas = mysql_num_rows($caja);
}
?>
<table id="tfhover" class="tftable">
<form action="cajas.php">
<th>Inicio</th>
<th><input name="inicio" id="datepicker" type="text" placeholder="Seleccione una fecha" required></th>
<th>Final</th>
<th><input name="final" id="datepicker2" type="text" placeholder="Seleccione una fecha" value="<?php echo date("m/d/Y")?>"></th>
<th><button type="submit" name="buscar" value="1">buscar</button></th>
</form>

<tr>
<th>Fecha</th>
<th>Usuario</th>
<th>Valor Medido</th>
<th>Valor Real</th>
<th>Cantidad de estadias</th>
</tr>
<?php do{?>
<tr>
<td><a href="#" class="button" style="padding: 5px 5px 5px 5px;"  title="guardar" onClick="abrirVentana('vercaja.php?caja=<?php echo $row_caja['id_caja']?>')"><?php echo date( "d-m-Y", strtotime( $row_caja['fecha'] ) );?></a></td>
<td><?php echo $row_caja['usuario_nombre'];?></td>
<td>$ <?php echo $row_caja['valor_medido'];?></td>
<td>$ <?php echo $row_caja['valor_real'];?></td>
<td><?php echo $row_caja['cant_estadia'];?></td>
<?php
$total_medido=$total_medido+$row_caja['valor_medido'];
$total_real=$total_real+$row_caja['valor_real'];
$total_estadia=$total_estadia+$row_caja['cant_estadia'];
?>
</tr>
<?php }while ($row_caja = mysql_fetch_array($caja)) ?>

<tr>
<th colspan="2">Totales</th>
<td>$ <?php echo $total_medido;?></td>
<td>$  <?php echo $total_real;?></td>
<td><?php echo $total_estadia;?></td>
</tr>
</table>
<?php 
session_start();
include_once("head.php");
?>
<script language="JavaScript">
function actualizaPadre(miColor){
    window.opener.document.estadia.tarjeta.value = miColor
	window.opener.document.estadia.tarjeta.focus()
	window.close()
}
</script><?
				
//selecciono todas las empresas 
$query="SELECT * FROM `estadia` 
				INNER JOIN tipo ON (estadia.id_tipo=tipo.id_tipo) 
				INNER JOIN tarjeta ON(estadia.id_tarjeta=tarjeta.id_tarjeta)
				INNER JOIN usuarios ON(estadia.id_usuario_entrada=usuarios.usuario_id)
		WHERE estadia.id_estado=1 AND estadia.id_tipo='$_GET[tipo]'";   
$estadia=mysql_query($query) or die(mysql_error());
$row_estadia = mysql_fetch_assoc($estadia);
mysql_query("SET NAMES 'utf8'");
$numero_estadia=0;

function intervalo_tiempo($init,$finish)
{
    //formateamos las fechas a segundos tipo 1374998435
    $diferencia = strtotime($finish) - strtotime($init);
	$diferencia=round($diferencia/60);
	if($diferencia<0){
		$diferencia="ERROR";
	}
    return $diferencia;
}
function periodos($minutos,$inicial,$extra_min,$tolerancia_min){

	$resta=$minutos-$inicial;
	$periodos=round($resta/$extra_min);
	$resto=$resta-$periodos*$extra_min;
				
	if($resto>$tolerancia_min){
		$periodos=$periodos+1;
		}
	return $periodos;
}
$total=0;
	
?>

<table id="tfhover" class="tftable">
<tr>
	<th>Usuario-Entrada</th>
	<th>Tarjeta</th>
	<th>Entrada</th>
	<th>Tipo</th>
	<th>Minutos</th>
	<th>Valor</th>
</tr>
<?do{?>
<tr>
	<td><?echo $row_estadia['usuario_nombre']?></td>
	<td><script language="javascript">
			var nuevoc = <?php echo $row_estadia['codigo']; ?>
        
			document.write("<a href=\"javascript:actualizaPadre('" + nuevoc + "')\">");
			document.write(nuevoc);
 
		</script>
	</td>
	<td><?echo date("H:i:s", strtotime( $row_estadia['entrada']))?></td>
	<td><?echo $row_estadia['tipo']?></td>
			<?$salida=date("Y-m-d H:i:s");
			$entrada=$row_estadia['entrada'];
			$minutos=intervalo_tiempo($entrada,$salida);?>
	<td><?echo $minutos?></td>
			<?
			$query="SELECT * FROM `tarifa` WHERE id_tipo='$row_estadia[id_tipo]' AND id_estado=1";   
			$tarifa=mysql_query($query) or die(mysql_error());
			$row_tarifa = mysql_fetch_assoc($tarifa);
			
			if(($row_tarifa['inicial_min']+$row_tarifa['tolerancia_min'])>$minutos){		
				$monto=$row_tarifa['valor_inicial'];
			}else{
				$periodos=periodos($minutos,$row_tarifa['inicial_min'],$row_tarifa['extra_min'],$row_tarifa['tolerancia_min']);		
				$extra=$periodos*$row_tarifa['valor_extra'];
				$monto=$row_tarifa['valor_inicial']+$extra;
			}?>
	<td>$ <?echo $monto?></td>
	<?$total=$total+$monto?>
	
</tr>
<? }while ($row_estadia = mysql_fetch_array($estadia));?>


<tr>
	<th colspan="5"><strong>Total</strong></th>
	<td>$ <?echo $total;?></td>
</tr>
<tr>
	<form action="caja.php" name="caja">
	<th colspan="4" align="center"><center></center></th>
	<th colspan="4" align="center"><center><button class="button_rojo" onclick="window.close()" name="guardar" value="1">Cerrar</button></center></th>
	</form>
</tr>

</table>



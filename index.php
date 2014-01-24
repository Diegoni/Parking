<?php include_once("menu.php"); ?>
<?php

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
	echo "entro";
	return $periodos;
}



if(isset($_GET['tarjeta'])){
	$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo) WHERE codigo='$_GET[tarjeta]'";   
	$tarjeta=mysql_query($query) or die(mysql_error());
	$row_tarjeta = mysql_fetch_assoc($tarjeta);
	$numero_tarjetas = mysql_num_rows($tarjeta);
	
	if($numero_tarjetas==0){
	echo "No existe esa tarjeta en la base de datos, por favor cargarla";
	}
		$query="SELECT * FROM `estadia` WHERE id_tarjeta='$row_tarjeta[id_tarjeta]' AND id_estado=1";   
		$estadia=mysql_query($query) or die(mysql_error());
		$row_estadia = mysql_fetch_assoc($estadia);
		$numero_estadias = mysql_num_rows($estadia);
		
		if($numero_estadias==0){
		$entrada=date("Y-m-d H:i:s");
		mysql_query("INSERT INTO `estadia` (
					id_tarjeta,
					entrada,
					id_estado,
					id_tipo,
					id_estacionamiento)
			VALUES (
					'$row_tarjeta[id_tarjeta]',
					'$entrada',
					1,
					'$row_tarjeta[id_tipo]',
					'$row_estacionamiento[estacionamiento]')
			") or die(mysql_error());	
		}else if($numero_estadias==1){
			$salida=date("Y-m-d H:i:s");
			$entrada=$row_estadia['entrada'];
			$minutos=intervalo_tiempo($entrada,$salida);
			
			$query="SELECT * FROM `tarifa` WHERE id_tipo='$row_estadia[id_tipo]' AND id_estado=1";   
			$tarifa=mysql_query($query) or die(mysql_error());
			$row_tarifa = mysql_fetch_assoc($tarifa);
			
			if(($row_tarifa['inicial_min']+$row_tarifa['tolerancia_min'])>$minutos){		
				$monto=$row_tarifa['valor_inicial'];
			}else{
				$periodos=periodos($minutos,$row_tarifa['inicial_min'],$row_tarifa['extra_min'],$row_tarifa['tolerancia_min']);		
				$extra=$periodos*$row_tarifa['valor_extra'];
				$monto=$row_tarifa['valor_inicial']+$extra;
			}
			
			
			mysql_query("UPDATE `estadia` SET 
						salida='$salida',
						id_estado=0,
						min='$minutos',
						monto='$monto'
						WHERE id_estadia='$row_estadia[id_estadia]'
						") or die(mysql_error());
						
			$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo) WHERE codigo='$_GET[tarjeta]'";   
			$tarjeta=mysql_query($query) or die(mysql_error());
			$row_tarjeta = mysql_fetch_assoc($tarjeta);
		}
}


//selecciono todas las empresas 
$query="SELECT count(*) as count, tipo.tipo FROM `estadia` INNER JOIN tipo ON (estadia.id_tipo=tipo.id_tipo) WHERE id_estado=1 GROUP BY tipo.id_tipo";   
$estadia=mysql_query($query) or die(mysql_error());
$row_estadia = mysql_fetch_assoc($estadia);
mysql_query("SET NAMES 'utf8'");
$numero_estadia=0;


	
?>

<form action="index.php" name="estadia">
<table>
<tr>
<td><label>Codigo de tarjeta</label></td>
<td><input type="text" name="tarjeta" placeholder="ingrese número de tarjeta" size="30" ></td>
<td><button type="submit">ok</button></td>
</tr>
</table>
<script language="JavaScript">
document.estadia.tarjeta.focus();
</script>
</form>


<? if($numero_estadias==0){ ?>
<table id="tfhover" class="tftable">
<tr>
	<th>Hora ingreso</th>
	<td><? echo date("H:i:s", strtotime( $entrada ));?></td>
	<th>Hora salida</th>
	<td> - </td>
</tr>
<tr>
	<th>Tarjeta</th>
	<td><? echo $row_tarjeta['codigo'];?></td>
	<th>Estado</th>
	<td>Abierta</td>
</tr>
<tr>
	<th>Tipo</th>
	<td><? echo $row_tarjeta['tipo'];?></td>
	<th>Valor</th>
	<td> - </td>
</tr>
</table>
<? }else{ ?>
<table id="tfhover" class="tftable">
<tr>
	<th>Hora ingreso</th>
	<td><? echo date("H:i:s", strtotime( $entrada)) ;?></td>
	<th>Hora salida</th>
	<td><? echo date("H:i:s", strtotime( $salida)) ;?></td>
</tr>
<tr>
	<th>Tarjeta</th>
	<td><? echo $row_tarjeta['codigo'];?></td>
	<th>Estado</th>
	<td>Cerrada</td>
</tr>
<tr>
	<th>Tipo</th>
	<td><? echo $row_tarjeta['tipo'];?></td>
	<th>Valor</th>
	<td><? echo $monto;?></td>
</tr>
</table>
<? } ?>

<br>

<div class="cantidad">
Cantidad de entradas abiertas 
<table id="tfhover" class="tftablechica">
<? do{ ?>
<tr>
	<td><? echo $row_estadia['tipo'];?></td>
	<td><? echo $row_estadia['count'];?></td>
	<?$numero_estadia=$numero_estadia+$row_estadia['count'];?>
</tr>
<? }while ($row_estadia = mysql_fetch_array($estadia));?>
<tr>
	<th>Total</th>
	<td><? echo $numero_estadia;?></td>
</tr>
<tr>
	<td>Hora actual</td>
	<td><form name="form_reloj">
		<input type="text" name="reloj" size="7">
		</form></td>
</tr>
</table>	
</div>




	

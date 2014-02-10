<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php");

//Funcion que calcula los minutos dos datetime
function intervalo_tiempo($init,$finish)
{
    $diferencia = strtotime($finish) - strtotime($init);
	$diferencia=round($diferencia/60);
	if($diferencia<0){
		$diferencia="ERROR";
	}
    return $diferencia;
}

//Cantidad de periodos, sirve para calcular costo final
function periodos($minutos,$inicial,$extra_min,$tolerancia_min){

	$resta=$minutos-$inicial;
	$periodos=round($resta/$extra_min);
	$resto=$resta-$periodos*$extra_min;
				
	if($resto>$tolerancia_min){
		$periodos=$periodos+1;
		}
	return $periodos;
}


//Si recibe parametro tarjeta
if(isset($_GET['tarjeta'])){
	
	//Selecciona la tarjeta que se ingreso
	$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo) WHERE codigo='$_GET[tarjeta]'";   
	$tarjeta=mysql_query($query) or die(mysql_error());
	$row_tarjeta = mysql_fetch_assoc($tarjeta);
	$numero_tarjetas = mysql_num_rows($tarjeta);
	
	
	//Si no encontro resultados, completa los 13 digitos de la tarjeta con 0 al comienzo y vuelve a buscar.
	if($numero_tarjetas==0){
		if(strlen($_GET['tarjeta'])<13){ 
		$cantidad=strlen($_GET['tarjeta']);
		$Cadena=$_GET['tarjeta'];
		for ($cantidad; $cantidad < 13; $cantidad++) {
			$Cadena='0'.$Cadena;
		}	
	}else{
		$Cadena=$_GET['tarjeta'];
	}
	$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo) WHERE codigo='$Cadena'";   
	$tarjeta=mysql_query($query) or die(mysql_error());
	$row_tarjeta = mysql_fetch_assoc($tarjeta);
	$numero_tarjetas = mysql_num_rows($tarjeta);
	}
	
	
	//Si no encontro nada en las dos busquedas muestra cartel de error
	if($numero_tarjetas==0){
	echo "No existe esa tarjeta en la base de datos, por favor cargarla";
	}
	
	
	//Buscamos estadias relacionados con la id de tarjeta	
		$query="SELECT * FROM `estadia` WHERE id_tarjeta='$row_tarjeta[id_tarjeta]' AND id_estado=1";   
		$estadia=mysql_query($query) or die(mysql_error());
		$row_estadia = mysql_fetch_assoc($estadia);
		$numero_estadias = mysql_num_rows($estadia);
	
	
	//Si en la busqueda no encontro nada es porque no hay estadias abiertas, damos ingreso a la estadia
		if($numero_estadias==0){
		$entrada=date("Y-m-d H:i:s");
		mysql_query("INSERT INTO `estadia` (
					id_tarjeta,
					entrada,
					id_estado,
					id_tipo,
					id_usuario_entrada,
					id_estacionamiento)
			VALUES (
					'$row_tarjeta[id_tarjeta]',
					'$entrada',
					1,
					'$row_tarjeta[id_tipo]',
					'$_SESSION[usuario_id]',
					'$row_estacionamiento[estacionamiento]')
			") or die(mysql_error());	
		}
		
		
	//Si encontro una estadia le damos de baja, cambiando el estado, ingresando los valores de salida y calculos necesarios
		else if($numero_estadias==1){
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
						id_usuario_salida='$_SESSION[usuario_id]',
						monto='$monto'
						WHERE id_estadia='$row_estadia[id_estadia]'
						") or die(mysql_error());
						
			$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo) WHERE codigo='$_GET[tarjeta]'";   
			$tarjeta=mysql_query($query) or die(mysql_error());
			$row_tarjeta = mysql_fetch_assoc($tarjeta);
		}
}


//selecciono todas las empresas 
$query="SELECT count(*) as count, tipo.tipo, tipo.id_tipo FROM `estadia` INNER JOIN tipo ON (estadia.id_tipo=tipo.id_tipo) WHERE id_estado=1 GROUP BY tipo.id_tipo";   
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


<? 
if(isset($_GET['tarjeta'])){
if($numero_estadias==0){ ?>
<table id="tfhover" class="tftable">
<tr>
	<th><div class="ptablatexto">Hora ingreso</div></th>
	<td><div class="ptabladato"><? echo date("H:i:s", strtotime( $entrada ));?></div></td>
	<th><div class="ptablatexto">Hora salida</div></th>
	<td><div class="ptabladato"> - </div></td>
</tr>
<tr>
	<th><div class="ptablatexto">Tarjeta</div></th>
	<td><div class="ptabladato"><? echo $row_tarjeta['codigo'];?></div></td>
	<th><div class="ptablatexto">Estado</div></th>
	<td><div class="ptabladato">Abierta</div></td>
</tr>
<tr>
	<th><div class="ptablatexto">Tipo</div></th>
	<td><div class="ptabladato"><? echo $row_tarjeta['tipo'];?></div></td>
	<th><div class="ptablatexto">Valor</div></th>
	<td><div class="ptablatotal"> - </div></td>
</tr>
</table>
<? }else{ ?>
<table>
<table id="tfhover" class="tftable">
<tr>
	<th><div class="ptablatexto">Hora ingreso</div></th>
	<td><div class="ptabladato"><? echo date("H:i:s", strtotime( $entrada)) ;?></div></td>
	<th><div class="ptablatexto">Hora salida</div></th>
	<td><div class="ptabladato"><? echo date("H:i:s", strtotime( $salida)) ;?></div></td>
</tr>
<tr>
	<th><div class="ptablatexto">Tarjeta</div></th>
	<td><div class="ptabladato"><? echo $row_tarjeta['codigo'];?></div></td>
	<th><div class="ptablatexto">Estado</div></th>
	<td><div class="ptabladato">Cerrada</div></td>
</tr>
<tr>
	<th><div class="ptablatexto">Tipo</div></th>
	<td><div class="ptabladato"><? echo $row_tarjeta['tipo'];?></div></td>
	<th><div class="ptablatexto">Valor</div></th>
	<td><div class="ptablatotal"><? echo $monto;?></div></td>
</tr>
</table>
<? 
	$horas=0;
	$min=$minutos;
	while ($min >= 60) {
	$min=$min-60;
	$horas=$horas+1;;
	}?>
<h1>Tiempo:	<?if($horas<10){
			echo "0".$horas	;
			}else{
			echo $horas ;
			}
			?>
			:
			<?if($min<10){
			echo "0".$min	;
			}else{
			echo $min ;
			}?></h1>
<? }
}else{ ?>
<table id="tfhover" class="tftable">
<tr>
	<th><div class="ptablatexto">Hora ingreso</div></th>
	<td><div class="ptabladato"> - </div></td>
	<th><div class="ptablatexto">Hora salida</div></th>
	<td><div class="ptabladato"> - </div></td>
</tr>
<tr>
	<th><div class="ptablatexto">Tarjeta</div></th>
	<td><div class="ptabladato"> - </div></td>
	<th><div class="ptablatexto">Estado</div></th>
	<td><div class="ptabladato"> - </div></td>
</tr>
<tr>
	<th><div class="ptablatexto">Tipo</div></th>
	<td><div class="ptabladato"> - </div></td>
	<th><div class="ptablatexto">Valor</div></th>
	<td><div class="ptablatotal"> - </div></td>
</tr>
</table>
<?}?>

<br>


Cantidad de entradas abiertas 
<table id="tfhover" class="tftable">
<? do{ ?>
<tr>	
	<th><div class="ptablatexto"><a href="#" title="ver detalle" onClick="abrirVentana('verdetalle.php?tipo=<? echo $row_estadia['id_tipo'];?>')"><? echo $row_estadia['tipo'];?></a></div></th>
	<td><div class="ptablatexto"><? echo $row_estadia['count'];?></div></td>
	<?$numero_estadia=$numero_estadia+$row_estadia['count'];?>
</tr>
<? }while ($row_estadia = mysql_fetch_array($estadia));?>
<tr>	
	<th><div class="ptablatexto">Total</div></th>
	<td><div class="ptablatotal"><? echo $numero_estadia;?></div></td>
</tr>
<tr>
	<td>Hora actual</td>
	<td><form name="form_reloj">
		<input type="text" name="reloj" size="7">
		</form></td>
</tr>
</table>	

<?
//Esto es para crear el grafico de torta

include "libchart/classes/libchart.php";

	$chart = new PieChart();
	
	$chart->getPlot()->getPalette()->setPieColor(array(
			new Color(255, 255, 255),
			new Color(255, 0, 0)
	));
	$lugaresvacios=$row_estacionamiento['cant_lugares']-$numero_estadia;
	
	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Ocupados= ".$numero_estadia, $numero_estadia));
	$dataSet->addPoint(new Point("Disponibles= ".$lugaresvacios, $lugaresvacios));
	$chart->setDataSet($dataSet);

	$chart->setTitle("Lugares");
	$chart->render("imagenes/pie_chart_color.png");
?>

<!--<img alt="Pie chart color test"  src="imagenes/pie_chart_color.png" style="border: 1px solid gray;"/>-->


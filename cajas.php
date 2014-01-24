<? include_once("menu.php");
$query="SELECT * FROM `caja` LIMIT 0,20";   
$caja=mysql_query($query) or die(mysql_error());
$row_caja = mysql_fetch_assoc($caja);
$numero_cajas = mysql_num_rows($caja);
?>
<table id="tfhover" class="tftable">
<tr>
<th>Fecha</th>
<th>Valor Medido</th>
<th>Valor Real</th>
<th>Cantidad de estadias</th>
</tr>
<?do{?>
<tr>
<td><a class="button" href="caja.php?fecha=<? echo $row_caja['fecha'];?>"><? echo $row_caja['fecha'];?></a></td>
<td><? echo $row_caja['valor_medido'];?></td>
<td><? echo $row_caja['valor_real'];?></td>
<td><? echo $row_caja['cant_estadia'];?></td>
</tr>
<? }while ($row_caja = mysql_fetch_array($caja)) ?>
</table>
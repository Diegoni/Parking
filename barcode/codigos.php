<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<? include_once("menu.php");
if(isset($_GET['eliminar'])){
$query="SELECT * FROM `estadia` WHERE id_tarjeta='$_GET[eliminar]' and id_estado=1";   
$estadia=mysql_query($query) or die(mysql_error());
$row_estadia = mysql_fetch_assoc($estadia);
$numero_estadias = mysql_num_rows($estadia);

if($numero_estadias>0){
	echo "hay estadias ".$numero_estadias." abiertas con esta tarjeta, por favor cierrelas antes de dar de baja a la tarjeta";
}else{
mysql_query("UPDATE tarjeta SET id_estado=0 WHERE id_tarjeta='$_GET[eliminar]'") or die(mysql_error());
echo "se ha dado de baja a la tarjeta";
}
}

if(isset($_GET['codigo'])){
$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo)WHERE tarjeta.codigo like '%$_GET[codigo]%' AND tarjeta.id_estado=1 ORDER BY tarjeta.codigo";   
$tarjeta=mysql_query($query) or die(mysql_error());
$row_tarjeta = mysql_fetch_assoc($tarjeta);
}else{
$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo)WHERE tarjeta.id_estado=1 ORDER BY tarjeta.codigo";   
$tarjeta=mysql_query($query) or die(mysql_error());
$row_tarjeta = mysql_fetch_assoc($tarjeta);
}
?>
	<table>
	<tr>
	<form action="codigos.php">
	<td><input name="codigo" type="text"</td>
	<td><button type="submit">buscar</button></td>
	<td></td>
	</form>
	</tr>
	<tr>
	<td>Codigo</td>
	<td>Tarjeta</td>
	<td></td>
	</tr>
	<tr>
	<?do{?>
	<tr>
	<td><?echo $row_tarjeta['codigo'];?></td>
	<td><?echo $row_tarjeta['tipo'];?></td>
	<td><a href="codigos.php?eliminar=<? echo $row_tarjeta['id_tarjeta']?>">x</a></td>
	</tr>
	<? }while ($row_tarjeta = mysql_fetch_array($tarjeta));?>
	</tr>
	</table>
	
	<footer id="footer">
	</footer >

	

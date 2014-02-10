<? 
session_start();
	if($_SESSION['id_tipousuario']!=1){
	header("Location: index.php");
	}
include_once("menu.php");
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
$query="SELECT * FROM `tarjeta` INNER JOIN tipo ON(tarjeta.id_tipo=tipo.id_tipo)WHERE tarjeta.id_estado=1 ORDER BY tarjeta.codigo LIMIT 0,20";   
$tarjeta=mysql_query($query) or die(mysql_error());
$row_tarjeta = mysql_fetch_assoc($tarjeta);
}
?>
	<table id="tfhover" class="tftable">
	<tr>
	<form action="codigos.php">
	<td><input name="codigo" type="text" placeholder="ingrese codigo" required></td>
	<td><button type="submit">buscar</button></td>
	<td><a href="codigo.php" class="button">Nuevo<a></td>
	</form>
	</tr>
	<tr>
	<th>Codigo</th>
	<th>Tarjeta</th>
	<th>Eliminar</th>
	</tr>
	<tr>
	<?do{?>
	<tr>
	<td><?echo $row_tarjeta['codigo'];?></td>
	<td><?echo $row_tarjeta['tipo'];?></td>
	<td><a class="button_rojo" href="codigos.php?eliminar=<? echo $row_tarjeta['id_tarjeta']?>">x</a></td>
	</tr>
	<? }while ($row_tarjeta = mysql_fetch_array($tarjeta));?>
	</tr>
	</table>


<?php
$fecha_americana=date( "Y-d-m", strtotime( $_GET['fecha'] ) );
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Caja ".$_GET['fecha'].".xls");
?>
<HTML LANG=”es”>
<title>Bases de Datos.</title>
<TITLE>Titulo de la Página.</TITLE>
</head>
<body>
<?php
$username="root";
		$password="bluepill";
		$database="estacionamiento";
		$url="localhost";
		mysql_connect($url,$username,$password);
		@mysql_select_db($database) or die( "No pude conectarme a la base de datos");
		
	
	$query="SELECT *
			FROM estadia
			INNER JOIN tipo ON(estadia.id_tipo=tipo.id_tipo)
			INNER JOIN usuarios ON(estadia.id_usuario_salida=usuarios.usuario_id)
			INNER JOIN estado ON(estadia.id_estado=estado.id_estado)
			INNER JOIN tarjeta ON(estadia.id_tarjeta=tarjeta.id_tarjeta)
			WHERE DATE_FORMAT(entrada, '%Y-%m-%d') = '$fecha_americana' 
			AND estadia.id_estado=0;";   
	$estadia=mysql_query($query) or die(mysql_error());
	$row_estadia = mysql_fetch_assoc($estadia);
	$numero_etadias = mysql_num_rows($estadia);
	
$total=0;
?>

<TABLE BORDER=1 align=”center” CELLPADDING=1 CELLSPACING=1>
<TR>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Usuario-Salida</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Tarjeta&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Entrada&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Salida&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Estado&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Tipo&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Minutos&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Valor&nbsp;</span></TD>

</TR>
<?php
 do{ 
printf("
<tr>
<td>&nbsp;%s</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s</td>
<td>&nbsp;%s</td>
<td>&nbsp;%s</td>
<td>&nbsp;%s</td>
<td>&nbsp;%s</td>
<td>&nbsp;%s</td>
</tr>", 
$row_estadia['usuario_nombre'],
$row_estadia["codigo"],
date("H:i:s", strtotime( $row_estadia['entrada'])),
date("H:i:s", strtotime( $row_estadia['salida'])),
$row_estadia["estado"],
$row_estadia["tipo"],
$row_estadia["min"],
$row_estadia["monto"]);
$total=$total+$row_estadia['monto'];
}while ($row_estadia = mysql_fetch_array($estadia));
?>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;-</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;-&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;-&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;-&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;-&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;-&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;Total&nbsp;</span></TD>
<TD  bgcolor=”#000000″><span style=”color:#FFFFFF; font-weight:bold;”>&nbsp;<?echo $total;?>&nbsp;</span></TD>
</table>
</body>
</html>
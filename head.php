<? 
//configuracion de base de datos
include_once("config/database.php");
?>
<html>
<head>

<title>Sistema de Estacionamiento</title>

<!-- Charset tiene que estar en utf-8 para que tome ñ y acentos -->
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- Iconos -->
<link type="image/x-icon" href="imagenes/favicon.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/favicon.ico" rel="shortcut icon" />

<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/1.css">
<link rel="stylesheet" href="css/2.css">
<link rel="stylesheet" href="css/3.css">

<script src="js/jquery.js"></script>
<script src="js/ui.js"></script>


<script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
</script>



<script language="JavaScript">
function mueveReloj(){
	momentoActual = new Date()
	hora = momentoActual.getHours()
	minuto = momentoActual.getMinutes()
	segundo = momentoActual.getSeconds()
	
	horaImprimible = hora + " : " + minuto + " : " + segundo
	
	document.form_reloj.reloj.value = horaImprimible
	
	setTimeout("mueveReloj()",1000)
}
</script>

<script type="text/javascript">
function abrirVentana(url) {
    window.open(url, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=450, height=500");
};

function cerrarse(){ 
window.close() 
} 
</script>
<script type="text/javascript">
	window.onload=function(){
	var tfrow = document.getElementById('tfhover').rows.length;
	var tbRow=[];
	for (var i=1;i<tfrow;i++) {
		tbRow[i]=document.getElementById('tfhover').rows[i];
		tbRow[i].onmouseover = function(){
		  this.style.backgroundColor = '#ffffff';
		};
		tbRow[i].onmouseout = function() {
		  this.style.backgroundColor = '#d4e3e5';
		};
	}
};
</script>
</head>
<center>
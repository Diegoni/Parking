<? include_once("head.php");


$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['usuario'])) {
  $loginUsername=$_POST['usuario'];
  $password=$_POST['contraseña'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "inicio.php";
  $MM_redirectLoginFailed = "error.php";
  $MM_redirecttoReferrer = false;
  
  $LoginRS__query=sprintf("SELECT nombre, password FROM usuarios WHERE nombre=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $Facultad) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
	<p>Por favor ingrese usuario y contraseña para conectarse con el sistema</p>
	<center>
	<table>
	<form id="form2" name="form2" method="POST" action="<?php echo $loginFormAction; ?>" style="">
    <td>Usuario</td>
    <td><input name="usuario" type="text" id="usuario" value=""/></td>
    </tr>
	<tr>
	<td>Contraseña </td>
	<td><input type="password" name="contraseña" id="contraseña" value="" /></td>
	</tr>
	<tr>
	<td></td>
	<td><input type="submit" name="button" id="button" value="Enviar" /></td>
	</tr>
	</form>
	<script language="JavaScript">
	document.form2.usuario.focus();
	</script> 
	</table>
	</center>
	
													</div>
													<div class="cleared"></div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="cleared"></div>
									</div>
								</div>
							</div>
							<div class="cleared"></div>
							<div class="art-footer">
								<div class="art-footer-t"></div>
								<div class="art-footer-l"></div>
								<div class="art-footer-b"></div>
								<div class="art-footer-r"></div>
								<div class="art-footer-body"><a href="./#" class="art-rss-tag-icon" title="RSS"></a>
									<div class="art-footer-text">
										<p>Contacto sistemasmendoza@hotmail.com</p>
									</div>
									<div class="cleared"></div>
								</div>
							</div>
							<div class="cleared"></div>
						</div>
					</div>
					<div class="cleared"></div>
					
				</div>
			</div>
		</div>
	</body>
</php>

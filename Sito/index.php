<!--
 * Arduindoor - http://arduindoor.altervista.org/
 * Copyright © 2015 Antonio Chioda <antonio.chioda@gmail.com>
 * Copyright © 2015 Bruno Palazzi <brunopalazzi0@gmail.com>
 * Copyright © 2015 Giacomo Perico <giacomo.perico@hotmail.it>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 -->

<?php
	//include 'db_connect.php';
	include 'functions.php';
	sec_session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
	try also with
	http://htmlhelp.com/tools/validator/
	http://infohound.net/tidy/
-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1;" />
	<title>Welcome to arduindoor</title>
<style type="text/css">
	html{
		background : white;
		font-color: black;
		font-size: 16px;
		font-family: Arial;
	}
	@media screen and (min-width:1200px){
		html{
			background: url(./wall.jpg);
			color: white;
			font-size: 18px;
			font-family: Arial;
		}
	}
	#pulsante
	{
        background: #FFFFFF;
		color: black;
	    opacity:0.9;
		float:left; 
		padding:1px;
		text-align:center;
		width:100%;
        transition: background 0.5s;
        -webkit-transition: background 0.5s; /* Safari */
    }
    #pulsante:hover
	{
        background: #2ECC71;
    }
</style>
	<script type='text/javascript'>
		function elimina(pForm) {
		pForm.LOGIN.value="";
		pForm.PASSWORD.value="";
		} 
	</script>
</head>
<body>
	<div style="float:left; width:20%;">
	<?php
		if(isset($_COOKIE['user'])&&isset($_COOKIE['password'])) //se son settati i cookie compilo gia le caselle 
		{
			echo"
			<div style=\"float:left; width:100%; padding:10px; margin:10px; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px;\">
			<form action=\"home.php\" name=\"guest\" method=\"post\">
			    <div id=\"pulsante\" onclick=\"document.forms.guest.submit();\">
			        <label>Entra come ospite</label>
					<input type=\"hidden\" name=\"LOGIN\" value=\"guest\" />
					<input type=\"hidden\" name=\"PASSWORD\" value=\"guest\" />
				</div>
			</form>
			</div>
			<div style=\"float:left; width:100%; padding:10px; margin:10px; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px;\">
			<form method=\"post\" name=\"ricevi_dati\" action=\"home.php\">
			<h1>Entra ora:</h1>
			<hr style=\"color:#ffffff; \" />
			<label>Utente:</label><br><input type=\"text\" value=" .( $_COOKIE['user']). " name=\"LOGIN\">
			<br>
			<label>Password:</label><br><input type=\"password\" value=" .( $_COOKIE['password']). " name=\"PASSWORD\">
			<br>
			Ricordami: <input type=\"checkbox\" name=\"rememberme\" value=" .(1). "><br /><br />
			<input type=\"submit\" name=\"MemoDati\" value=\"Accedi\">
			<input TYPE=\"button\"  VALUE=\"cancella\" onClick=\"elimina(this.form)\" name=\"VediElem\">
			</form>
			</div>";
		}
		else	//altrimenti le metto vuote
		{
			echo"
			<div style=\"float:left; width:100%; padding:10px; margin:10px; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px;\">
			<form action=\"home.php\" name=\"guest\" method=\"post\">
			    <div id=\"pulsante\" onclick=\"document.forms.guest.submit();\">
			        <label>Entra come ospite</label>
					<input type=\"hidden\" name=\"LOGIN\" value=\"guest\" />
					<input type=\"hidden\" name=\"PASSWORD\" value=\"guest\" />
				</div>
			</form>
			</div>
			<div style=\"float:left; width:100%; padding:10px; margin:10px; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px;\">
			<form method=\"post\" name=\"ricevi_dati\" action=\"home.php\">
			<h1>Entra ora:</h1>
			<hr style=\"color:#ffffff; \" />
			<label>Login:</label><br><input type=\"text\" name=\"LOGIN\">
			<br>
			<label>Password:</label><br><input type=\"password\" name=\"PASSWORD\">
			<br>
			Ricordami: <input type=\"checkbox\" name=\"rememberme\" value=" .(1). "><br /><br />
			<input type=\"submit\" name=\"MemoDati\" value=\"Accedi\">
			<input TYPE=\"button\"  VALUE=\"cancella\" onClick=\"elimina(this.form)\" name=\"VediElem\">
			</form>
			</div>";
		}
		
	?>
	<div style="float:left; width:100%; padding:10px; margin:10px; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px;">
		<form method="get" name="registra" action="reg.php">
			<h1>Registrati ora:</h1>
			<hr style="color:#ffffff; " />
			<label>Nome utente:</label><br><input type="text" name="utente">
			<br>
			<label>Password:</label><br><input type="password" name="password">
			<br><br>
			<input TYPE="submit"  VALUE="registrati" name="registr">
		</form>
	</div>
</div>
<div style="float:left; width:70%; padding:10px; ">
	<div style="float:right; width:40%;">
		<img src="./serra1.jpg" style="vertical-align:middle;  height:60%;" />
	</div>
	<div style="float:right; width:40%;">
		<img src="./serra2.jpg" style="vertical-align:middle;  height:60%;" />
	</div>
</div>
</body>	
<?php
	if(isset($_SESSION["loggato"]))
	{
		session_unset();	//distruggo la sessione qualora abbiano fatto il logout
		session_destroy();
	}
?>
	
 </html>


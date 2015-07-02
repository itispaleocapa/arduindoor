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
	include 'functions.php';
	sec_session_start();
	include 'db_connect.php';
	$siteKey = "6LcfwQUTAAAAAG_Et94Sxouwtpr1AQXNosU7s061";
	$secret = "6LcfwQUTAAAAABvH56SHSXAnr7leoIripwRpstLW";
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
	<title>Salvataggio</title>
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
	</style> 
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
</head>

<body>

<?php
	if (isset($_GET["g-recaptcha-response"]))
	{
		//echo "entro nel chapta";
		$recaptchaBase = './ReCaptcha/src/ReCaptcha';
		require_once $recaptchaBase . '/ReCaptcha.php';
		require_once $recaptchaBase . '/RequestMethod.php';
		require_once $recaptchaBase . '/RequestParameters.php';
		require_once $recaptchaBase . '/Response.php';
		require_once $recaptchaBase . '/RequestMethod/Post.php';
		require_once $recaptchaBase . '/RequestMethod/Socket.php';
		require_once $recaptchaBase . '/RequestMethod/SocketPost.php';
		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$resp = $recaptcha->verify($_GET['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if ($resp->isSuccess())
		{
			$criptata=crypt($_GET["ps"]);
			$us=$_GET["us"];
			$sql="insert into members(username,password) values('$us','$criptata')";
			if($mysqli->query($sql)==true)
			{
				echo"registrato con successo";
				header( "refresh:3;url=index.php" );
			}
			else
			{
				error();
				echo "Query non andata a buon fine";
				header( "refresh:1;url=index.php" );
			}
		}
		else
		{
			echo"Errore nell'inserimento";
		}
	}
	else if(isset($_GET["password"],$_GET["utente"]))
	{
		if($_GET["password"]!="" && $_GET["utente"]!="")
		{
			echo"
				<form name=\"secure\" method=\"get\" action=\"?\">
				Inserisci il seguente codice per confermare: 
				<div class=\"g-recaptcha\" data-sitekey=\"6LcfwQUTAAAAAG_Et94Sxouwtpr1AQXNosU7s061\"></div>
				<input type=\"hidden\" name=\"us\" value=".$_GET["utente"]." />
				<input type=\"hidden\" name=\"ps\" value=".$_GET["password"]." />
				<input type=\"submit\" name=\"conf\" value=\"Conferma\" />
				</form>";
		}
		else 
		{
			error();
			echo "Valori mancanti o non validi";
			header( "refresh:1;url=index.php" );
		}
	}
	else
	{
			error();
			echo "Valori mancanti o non validi";
			header( "refresh:1;url=index.php" );
	}
	?>

</body>
</html>

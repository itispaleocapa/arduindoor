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
	<title>Stato Arduindoor</title>
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
		
			
	<?php
	if(isset($_POST['rememberme']))	//se ricordami creo/aggiorno i cookie
	{
		setcookie('user',$_POST["LOGIN"],time()+(86400*30),"/");
		setcookie('password',$_POST["PASSWORD"],time()+(86400*30),"/");
	}
	if(isset($_POST["LOGIN"],$_POST["PASSWORD"]))	//se settati parametri di login controllo se corretto
	{
		$user=$_POST["LOGIN"];
		$passwd=$_POST["PASSWORD"];
		if(login($user,$passwd)==true)
		{
			
			$_SESSION["utente"]=$user;	//se dati validi memorizzo come sessione il nome utente e loggato a true altrimenti il contrario
			$_SESSION["loggato"]=true;
		}
		else
			$_SESSION["loggato"]=false;
	}
	if(isset($_SESSION["loggato"]))
	{
		if($_SESSION["loggato"]==false)	//in base a loggato scelgo cosa ritornare, se metto solo il pulsante per tornare a index
			go_index();
		else
		{	//struttura base da tenere in alto che contiene tutti i pulsanti alle sezioni importanti
			tasti_rapidi();
			mostra_arduindoor();
		}
	}
	else
		go_index();
	
	
?>
</head>
<body>


</body>

</html>


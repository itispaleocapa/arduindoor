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
	<title>Mostra colture</title>
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
<script type='text/javascript'>
	function complete()
	{
		str=document.getElementById("nome").value;
		var xmlhttp=null;
		if (window.XMLHttpRequest) 
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} 
		else 
		{
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				document.getElementById("tabella1").innerHTML = xmlhttp.responseText;
				//alert( xmlhttp.responseText);
			}
		}
		xmlhttp.open("GET","mostra2.php?nome="+str,true);
		xmlhttp.send();
	}
</script>
</head>
<body>
<?php
	include 'db_connect.php';
	if(isset($_SESSION["loggato"]))
	{
		if($_SESSION["loggato"]==true)
		{
			tasti_rapidi();
			echo"<br><br>";
			if (mysqli_connect_errno())// verifica dell'avvenuta connessione
			{
				error();
				echo "Errore in connessione al DBMS";// notifica in caso di errore
				exit();// interruzione delle esecuzioni i caso di errore
			}
			echo"Seleziona quale coltura vuoi estrarre dal database: <br /><br />";
			$riga = $mysqli->query("SELECT nome FROM pianta");
			$outp='<select id="nome" name="nome" onChange="complete()" style="width:100px;">';
			$outp .="<option value='' selected='selected'>";
			while($rs = $riga->fetch_array())
			{
			
				$nome=$rs['nome'];
				$outp .="<option value=".($nome).">$nome";
			}
			$outp .= "</select>";
			echo $outp;
		}
		else
		{
			go_index();
		}
	}
	else
	{
		go_index();
	}
?>
	<div id="tabella1"></div>
</body>
</html>




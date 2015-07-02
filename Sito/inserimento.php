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
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1;" />
	<title>Inserimento metodi</title>
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
	include 'db_connect.php';
	if(isset($_SESSION["loggato"]))
	{
		if($_SESSION["loggato"]==true)	//se è loggato mostro il contenuto della pagina altrimenti gli mostro solo la pagina per tornare all'index
		{
			tasti_rapidi();
			if (mysqli_connect_errno())// verifica dell'avvenuta connessione
			{
				echo "Errore in connessione al DBMS: ".mysqli_connect_error();// notifica in caso di errore
				exit();// interruzione delle esecuzioni i caso di errore
			}
			echo"
				<form align=\"center\" method='POST' id='inserisci' name='inserisci' action='insdatabase.php'>
               <div style=\"float:left; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px; margin:20px; padding:10px;\">
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:10px;\">
						<h3>PIANTA</h3>
							<label>Nome:</label><br><input type='text' name='NOME_P'>
					</div>
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:10px;\">
							<h3>TIPO PIANTA </h3>
								<label>Tipo della pianta:</label><br><input type='text' name='NOME'>
								<br>
								<br>
								<label>Zona:</label><br><input type='text' name='ZONA'>
								<br>
								<br>
								<label>Note:</label><br><input type='text' name='NOTE'>
					</div>
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:10px;\">
							<h3>VEGETATIVA</h3>
								<label>Ore luce:</label><br><input type='text' name='V_ORELUCE'>
								<br>
								<br>
								<label>Temperatura minima:</label><br><input type='text' name='V_TMIN'>
								<br>
								<br>
								<label>Temperatura massima:</label><br><input type='text' name='V_TMAX'>
								<br>
								<br>
								<label>Umidit&agrave minima:</label><br><input type='text' name='V_UMIMIN'>
								<br>
								<br>
								<label>Umidit&agrave massima:</label><br><input type='text' name='V_UMIMAX'>
					</div>
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:10px;\">
							<h3>FIORITURA</h3>
								<label>Ore luce:</label><br><input type='text' name='ORELUCE'>
								<br>
								<br>
								<label>Temperatura minima:</label><br><input type='text' name='TMIN'>
								<br>
								<br>
								<label>Temperatura massima:</label><br><input type='text' name='TMAX'>
								<br>
								<br>
								<label>Umidit&agrave minimo:</label><br><input type='text' name='UMIMIN'>
								<br>
								<br>
								<label>Umidit&agrave massima:</label><br><input type='text' name='UMIMAX'>
					</div>
					<div style=\"clear:both;\"></div>
						<div style=\"float:right; margin:10px; padding:10px;\" id=\"pulsante\" onclick=\"document.forms.inserisci.submit();\">
			            <label>Inserisci</label>
				    </div>
			    </form>
						</div>
					</div>
				</form>";
		}
		else
		{
			go_index();
		}
	}
	else	//in caso di mancato login si può tornare solo a index
	{
		go_index();
	}
?>
</head>
<body>
</body>

</html>


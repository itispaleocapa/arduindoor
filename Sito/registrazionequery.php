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
<?php
if(isset($_SESSION["loggato"]))
{
	if($_SESSION["loggato"]==true)
	{
		tasti_rapidi();
		if(isset($_POST["mac"]))
		{
			if($_POST["mac"]!="" && $_POST["mac"]!=NULL && strlen($_POST["mac"])>33)
			{
				$mac=$_POST["mac"];
				$indirizzo=$mac[2];
				$indirizzo .=$mac[3];
				$ind=(hexdec($indirizzo));
				
				$indirizzo=$mac[8];
				$indirizzo .=$mac[9];
				$ind .=(hexdec($indirizzo));
				
				$indirizzo=$mac[14];
				$indirizzo .=$mac[15];
				$ind .=(hexdec($indirizzo));
				
				$indirizzo=$mac[20];
				$indirizzo .=$mac[21];
				$ind .=(hexdec($indirizzo));
				
				$indirizzo=$mac[26];
				$indirizzo .=$mac[27];
				$ind .=(hexdec($indirizzo));
				
				$indirizzo=$mac[32];
				$indirizzo .=$mac[33];
				$ind .=(hexdec($indirizzo));
				$user=$_SESSION["utente"];
				if (mysqli_connect_errno())// verifica dell'avvenuta connessione
				{
					error();
					echo "Errore in connessione al DBMS";// notifica in caso di errore
					exit();// interruzione delle esecuzioni i caso di errore
				}
				$sql="SELECT id FROM members WHERE username='$user'";
				$result = $mysqli->query($sql);
				if(($rs = $result->fetch_array(MYSQLI_ASSOC))==true)
				{
					$sql="select id from arduindoor_stato where indirizzo='$ind'";
					$result=$mysqli->query($sql);
					if($result->num_rows>0)
					{
						$id=$rs["id"];
						$sql="update arduindoor_stato set id_utente='$id' where indirizzo='$ind'";
						if($mysqli->query($sql) == true)
						{
							echo"Arduindoor registrato";
							header( "refresh:2;url=home.php" );
						}
						else 
						{
							error();
							echo "Nessun mac corrispondente, assicurati di aver inserito sia le virgole che gli spazi e che il tuo arduindoor sia connesso ad internet<br />";
						}
					}
					else 
						{
							error();
							echo "Nessun mac corrispondente, assicurati di aver inserito sia le virgole che gli spazi e che il tuo arduindoor sia connesso ad internet<br />";
						}
				}
				else
				{
					error();
					echo"Errore durante l'esecuzione della query<br />";
				}
			}
			else
			{
				error();
				echo "mac non impostato oppure non corrispondente a quello riportato sull'etichetta<br />";
			}
		}
		else
		{
			error();
			echo "mac non impostato<br />";
		}
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
</head>
<body>
</body>
</html>

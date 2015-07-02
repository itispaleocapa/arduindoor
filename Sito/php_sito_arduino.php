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
	include 'db_connect.php';
	//controllo se sono presenti i parametri valore e localita
	if(isset($_GET["temperatura"],$_GET["umidita"],$_GET["irrigazione"],$_GET["stato_luce"],$_GET["mac"]))
	{
		$temperatura = $_GET["temperatura"];
		$umidita = $_GET["umidita"];
		$irrigazione = $_GET["irrigazione"];
		$stato_luce = $_GET["stato_luce"];
		$mac= $_GET["mac"];
		
		if (mysqli_connect_errno()) 
		{
			exit();
		}
		else 
		{ 
			$result=mysqli_query($mysqli,"select * from arduindoor_stato where indirizzo = '$mac'");
			if(mysqli_num_rows($result)==0)
			{
				$sql = "INSERT INTO arduindoor_stato(temperatura ,umidita ,irrigazione ,stato_luce ,indirizzo)VALUES('$temperatura', '$umidita' ,'$irrigazione', '$stato_luce' ,'$mac')";
			}
			else
			{ 
				$sql = "UPDATE arduindoor_stato SET temperatura='$temperatura',umidita='$umidita',irrigazione='$irrigazione',stato_luce='$stato_luce' WHERE indirizzo='$mac'";
			}
			//eseguo la query
			$query = mysqli_query($mysqli,$sql);
		
			//gestione degli errori
			if(!$query ){die('Impossibile eseguire la query: ' . mysqli_error($mysqli));}
			else
			{
				echo"ok";
			}
		
			//chiudo la connessione al db
			mysqli_close($mysqli);
		}
	}
?>



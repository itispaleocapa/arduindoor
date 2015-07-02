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
 <?php
	include 'db_connect.php';
	if(isset($_POST["cellulare"]))
	{
		if(isset($_POST["arduindoor"],$_POST["ora_a"],$_POST["min_a"],$_POST["ora_s"],$_POST["min_s"],$_POST["u_aria"],$_POST["t_aria"],$_POST["terra"]))	//vanno obbligatoriamente settati sempre tutti i parametri
		{
			if($_POST["ora_a"] == $_POST["ora_s"] && $_POST["min_a"] == $_POST["min_s"] && $_POST["ora_a"]!=0 && $_POST["min_a"]!=0 )	// controllo se ore impostate male oppure impostate solo in parte(se tutte a 0 non aggiorno ora)
			{
				echo "0";
			}
			else
			{
				if($_POST["ora_a"]<24 && $_POST["ora_a"]>=0 && $_POST["ora_s"]<24 && $_POST["ora_s"]>=0 && $_POST["min_a"]<60 && $_POST["min_a"]>=0 && $_POST["min_s"]<60 && $_POST["min_s"]>=0)
				{
					if($_POST["u_aria"]<100 && $_POST["u_aria"]>=0 && $_POST["t_aria"]<36 && $_POST["t_aria"]>=0 && $_POST["terra"]>=0 && $_POST["terra"]<=1023)	//controlli ulteriori su ora e valori impostati delle altre variabili
					{
						if (mysqli_connect_errno())	//se c'è un errore metto i tasti per tornare e basta
						{
							echo "0";
						}
						else
						{
							
							$id=$_POST["arduindoor"];
							$result = $mysqli->query("SELECT indirizzo,umidita,temperatura,irrigazione FROM arduindoor_stato WHERE indirizzo='$id'");
							if(!$result)
							{
								echo "0";
							}
							else
							{
								$rs = $result->fetch_array(MYSQLI_ASSOC);
								if($rs["umidita"]==$_POST["u_aria"] && $rs["irrigazione"]==$_POST["terra"] && $rs["temperatura"]==$_POST["t_aria"] && $_POST["ora_a"]==0 && $_POST["ora_s"]==0 && $_POST["min_a"]==0 && $_POST["min_s"]==0)
								{
									echo "0";
								}
								else
								{
									$result = $mysqli->query("SELECT indirizzo,umidita_aria,temperatura_aria,umidita_terra FROM arduindoor_update WHERE indirizzo='$id'");
									if(mysqli_num_rows($result)==0)	//se nessuna corrispondenza significa che è il primo aggiornamento
									{
										$ora_a=$_POST["ora_a"];
										$min_a=$_POST["min_a"];
										$ora_s=$_POST["ora_s"];
										$min_s=$_POST["min_s"];
										$u_aria=$_POST["u_aria"];
										$t_aria=$_POST["t_aria"];
										$terra=$_POST["terra"];
										$sql="insert into arduindoor_update(indirizzo,ora_accensione,minuto_accensione,ora_spegnimento,minuto_spegnimento,umidita_aria,temperatura_aria,umidita_terra) values ('$id','$ora_a','$min_a','$ora_s','$min_s','$u_aria','$t_aria','$terra')";
										if($mysqli->query($sql) == true)
										{
											echo "1";
										}
										else
										{
											echo "0";
										}
									}
									else
									{
										
										$ora_a=$_POST["ora_a"];
										$min_a=$_POST["min_a"];
										$ora_s=$_POST["ora_s"];
										$min_s=$_POST["min_s"];
										$u_aria=$_POST["u_aria"];
										$t_aria=$_POST["t_aria"];
										$terra=$_POST["terra"];
										$sql="update arduindoor_update set ora_accensione='$ora_a',minuto_accensione='$min_a',ora_spegnimento='$ora_s',minuto_spegnimento='$min_s',umidita_aria='$u_aria',temperatura_aria='$t_aria',umidita_terra='$terra' where indirizzo='$id'";
										if($mysqli->query($sql) == true)
										{
											echo "1";
										}
										else
										{
											echo "0";
										}
										
									}
								}
							}
						}
					}
					else
					{
						echo "0";
					}
				}
				else
				{
					echo "0";
				}
			}
		}
		else
		{
			echo "0";
		}
	}
	else
	{
		echo"
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
			<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en\">
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1;\" />
					<title>Carica</title>
					<style type=\"text/css\">
						html{
							background : white;
							font-color: black;
							font-size: 16px;
							font-family: Arial;
							}
						@media screen and (min-width:1200px)
						{
							html{
								background: url(./wall.jpg);
								color: white;
								font-size: 18px;
								font-family: Arial;
								}
						}
					</style>
				</head>
			<body>";
		if(isset($_SESSION["loggato"]))	// controllo sia loggato
		{
			if($_SESSION["loggato"]==true)
			{
				tasti_rapidi();
				if(isset($_POST["arduindoor"],$_POST["ora_a"],$_POST["min_a"],$_POST["ora_s"],$_POST["min_s"],$_POST["u_aria"],$_POST["t_aria"],$_POST["terra"]))	//vanno obbligatoriamente settati sempre tutti i parametri
				{
					if($_POST["ora_a"] == $_POST["ora_s"] && $_POST["min_a"] == $_POST["min_s"] && $_POST["ora_a"]!=0 && $_POST["min_a"]!=0 )	// controllo se ore impostate male oppure impostate solo in parte(se tutte a 0 non aggiorno ora)
					{
						error();
						echo "<br>Ore impostate male<br>";
					}
					else
					{
						if($_POST["ora_a"]<24 && $_POST["ora_a"]>=0 && $_POST["ora_s"]<24 && $_POST["ora_s"]>=0 && $_POST["min_a"]<60 && $_POST["min_a"]>=0 && $_POST["min_s"]<60 && $_POST["min_s"]>=0)
						{
							if($_POST["u_aria"]<100 && $_POST["u_aria"]>=0 && $_POST["t_aria"]<36 && $_POST["t_aria"]>=0 && $_POST["terra"]>=0 && $_POST["terra"]<=1023)	//controlli ulteriori su ora e valori impostati delle altre variabili
							{
								if (mysqli_connect_errno())	//se c'è un errore metto i tasti per tornare e basta
								{
									error();
									// notifica in caso di errore
									echo "<br>Errore in connessione al DBMS<br> ";//.mysqli_connect_error()."<br>";
									// interruzione delle esecuzioni i caso di errore
									exit();
								}
								else
								{
									
									$id=$_POST["arduindoor"];
									$result = $mysqli->query("SELECT indirizzo,umidita,temperatura,irrigazione FROM arduindoor_stato WHERE indirizzo='$id'");
									if(!$result)
									{
										error();
									}
									else
									{
										$rs = $result->fetch_array(MYSQLI_ASSOC);
										if($rs["umidita"]==$_POST["u_aria"] && $rs["irrigazione"]==$_POST["terra"] && $rs["temperatura"]==$_POST["t_aria"] && $_POST["ora_a"]==0 && $_POST["ora_s"]==0 && $_POST["min_a"]==0 && $_POST["min_s"]==0)
										{
											error();
											echo "<br>Stai tentando di inserire una modifica che non modificherebbe niente";
										}
										else
										{
											$result = $mysqli->query("SELECT indirizzo,umidita_aria,temperatura_aria,umidita_terra FROM arduindoor_update WHERE indirizzo='$id'");
											if(mysqli_num_rows($result)==0)	//se nessuna corrispondenza significa che è il primo aggiornamento
											{
												echo"creo nuova entry<br>";
												$ora_a=$_POST["ora_a"];
												$min_a=$_POST["min_a"];
												$ora_s=$_POST["ora_s"];
												$min_s=$_POST["min_s"];
												$u_aria=$_POST["u_aria"];
												$t_aria=$_POST["t_aria"];
												$terra=$_POST["terra"];
												$sql="insert into arduindoor_update(indirizzo,ora_accensione,minuto_accensione,ora_spegnimento,minuto_spegnimento,umidita_aria,temperatura_aria,umidita_terra) values ('$id','$ora_a','$min_a','$ora_s','$min_s','$u_aria','$t_aria','$terra')";
												if($mysqli->query($sql) == true)
												{
													echo "query andata a buon fine";
													header( "refresh:2;url=home.php" );
												}
												else
												{
													error();
													echo"<br> Problema nell'esecuzione della query<br>";
													//echo "Error: " . $sql . "<br>" . $mysqli->error;
												}
											}
											else
											{
												echo "aggiorno<br>";
												$ora_a=$_POST["ora_a"];
												$min_a=$_POST["min_a"];
												$ora_s=$_POST["ora_s"];
												$min_s=$_POST["min_s"];
												$u_aria=$_POST["u_aria"];
												$t_aria=$_POST["t_aria"];
												$terra=$_POST["terra"];
												$sql="update arduindoor_update set ora_accensione='$ora_a',minuto_accensione='$min_a',ora_spegnimento='$ora_s',minuto_spegnimento='$min_s',umidita_aria='$u_aria',temperatura_aria='$t_aria',umidita_terra='$terra' where indirizzo='$id'";
												if($mysqli->query($sql) == true)
												{
													echo "query andata a buon fine";
													header( "refresh:2;url=home.php" );
												}
												else
												{
													error();
													echo"<br> Problema nell'esecuzione della query<br>";
													//echo "Error: " . $sql . "<br>" . $mysqli->error;
												}
												
											}
										}
									}
								}
							}
							else
							{
								error();
								echo"<br>Hai impostato male i valori<br>";
							}
						}
						else
						{
							error();
							echo"<br>Hai impostato male i valori<br>";
						}
					}
				}
				else
				{
					error();
					echo"<br>Non hai impostato tutti i valori necessari<br>";
				}
				
			}
			else
			go_index();
		}
		else
			go_index();
        echo"
		</body>
		</html>";
	}
	
	
 ?>




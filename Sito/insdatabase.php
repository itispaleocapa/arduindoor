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
	if(isset($_SESSION["loggato"],$_POST["NOME"],$_POST["ZONA"],$_POST["NOTE"],$_POST["TMIN"],$_POST["TMAX"],$_POST["UMIMIN"],$_POST["UMIMAX"],$_POST["ORELUCE"],$_POST["V_TMIN"],$_POST["V_TMAX"],$_POST["V_UMIMIN"],$_POST["V_UMIMAX"],$_POST["V_ORELUCE"],$_POST["NOME_P"])) //controllo ulteriore sul login e sui campi inseriti
	{
		if($_POST["NOME"]=="" || $_POST["ZONA"]=="" || $_POST["TMIN"]=="" || $_POST["TMAX"]=="" || $_POST["UMIMIN"]=="" ||$_POST["UMIMAX"]=="" || $_POST["ORELUCE"]=="" || $_POST["V_TMIN"]=="" || $_POST["V_TMAX"]=="" || $_POST["V_UMIMIN"]=="" || $_POST["V_UMIMAX"]=="" || $_POST["V_ORELUCE"]=="" || $_POST["NOME_P"]=="" ) //controllo sui valori passati per campi settati correttamente
		{
			error();
			echo "<br> Non tutti i campi necessari sono stati impostati<br>";
			exit();
		}
		else
		{
			if($_SESSION["loggato"]==true)	//se loggato è valido inserisco i valori uno a uno nel database tramite variabili di passaggio da mettere nelle queri per una letttura più smart
			{
				//include 'db_connect.php';
				//TIPO PIANTA
				$nome=$_POST["NOME"];
				$zona=$_POST["ZONA"];
				$note=$_POST["NOTE"];
				
				//FIORITURA
				$tmin=$_POST["TMIN"];
				$tmax=$_POST["TMAX"];
				$umimin=$_POST["UMIMIN"];
				$umimax=$_POST["UMIMAX"];
				$oreluce=$_POST["ORELUCE"];
				
				//VEGETATIVA
				$V_oreluce=$_POST["V_ORELUCE"];
				$V_tmin=$_POST["V_TMIN"];
				$V_tmax=$_POST["V_TMAX"];
				$V_umimin=$_POST["V_UMIMIN"];
				$V_umimax=$_POST["V_UMIMAX"];
				
				if($tmin>$tmax || $umimin>$umimax || $oreluce<0 || $oreluce>24 || $V_tmin>$V_tmax || $V_umimin>$V_umimax || $V_oreluce<0 || $V_oreluce>24) 	//controllo corretta impostazione dei valori
				{
					error();
					echo "<br>Errore nei parametri impostati<br>";
					exit();
				}
				else
				{
					//PIANTA
					$nome_P=$_POST["NOME_P"];
					$sql="select id from pianta where nome='$nome_P'";
					$result=$mysqli->query($sql);
					if(mysqli_num_rows($result)>0)
					{
						error();
						echo "Pianta gia presente nel database";
						exit();
					}
					
					//$mysqli = new mysqli("localhost", "root", "", "my_arduindoor");//mi connetto al db
					
					if (mysqli_connect_errno())	//se c'è un errore metto i tasti per tornare e basta
					{
						tasti_rapidi();
						// notifica in caso di errore
						echo "Errore in connessione al DBMS: ".mysqli_connect_error()."<br>";
						// interruzione delle esecuzioni i caso di errore
						exit();
					}	
					else //altrimenti mostro i tasti e faccio un inserimento
					{
						tasti_rapidi();
						// notifica in caso di connessione attiva
						//echo "Inserisco"."<br>";
						$sql="savepoint inserimento";	//creo un savepoint perchè le culture andranno inserite o tutto o niente
						if($mysqli->query($sql) == true)		//controllo si a stato creato il savepoint
						{
							$sql="select id from tipo where nome_tipo='$nome' and zona='$zona' and note='$note'";
							$result=$mysqli->query($sql);
							//var_dump($result);
							$prova=mysqli_num_rows($result);
							//var_dump($prova);
							if(mysqli_num_rows($result)==0)	//primo controllo se il tipo di pianta è gia nel database
							{
								$sql="insert into tipo(nome_tipo,zona,note) values ('$nome','$zona','$note')";	//inserisco tipo fioritura vegetativa salvando gli id che metterò nel collegamento di pianta
								if($mysqli->query($sql) == true)		//controllo query a buon fine
								{
									$idNomeTipo=$mysqli->insert_id;		//prendo l'id appena inserito
									$sql="select id from fioritura where temperatura_min='$tmin' and temperatura_max='$tmax' and umidita_min='$umimin' and umidita_max='$umimax' and ore_luce='$oreluce'";
									$result=$mysqli->query($sql);
									if(mysqli_num_rows($result)==0)	//controllo non ci sia gia una entry uguale
									{
										$sql="insert into fioritura(temperatura_min,temperatura_max,umidita_min,umidita_max,ore_luce) values ('$tmin','$tmax','$umimin','$umimax','$oreluce')";
										if($mysqli->query($sql) == true)
										{
											$idFioritura=$mysqli->insert_id;	//prendo l'id appena inserito
											$sql="select id from vegetativa where temperatura_min='$V_tmin' and temperatura_max='$V_tmax' and umidita_min='$V_umimin' and umidita_max='$V_umimax' and ore_luce='$V_oreluce'";
											$result=$mysqli->query($sql);
											if(mysqli_num_rows($result)==0)	//controllo non ci sia una entry uguale
											{
												$sql="insert into vegetativa(ore_luce,temperatura_min,temperatura_max,umidita_min,umidita_max) values ('$V_oreluce','$V_tmin','$V_tmax','$V_umimin','$V_umimax')";
												if($mysqli->query($sql) == true)
												{
													$idVegetazione=$mysqli->insert_id;	//prendo l'id appena inserito
													$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
													if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
													{
														echo "Tutto andato a buon fine";
														$sql="commit";
														$mysqli->query($sql);
														header( "refresh:3;url=home.php" );
													}
													else	// se ci sono errori faccio un roolback all'ultima impostazione valida
													{
														$sql="rollback to inserimento;";
														$mysqli->query($sql);
														error();
														echo "Errore inserimento <br>";
													}
												}
												else
												{
													$sql="rollback to inserimento;";	//torno al savepoint
													$mysqli->query($sql);
													error();	//chiamo la funzione errore
													echo "Errore inserimento <br>";
												}
											}
											else		//Terzo c'è gia la vegetativa uguale
											{
												while($rs=$result->fetch_array(MYSQLI_ASSOC))
												{
													$idVegetazione=$rs["id"];
												}
												$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
												if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
												{
													echo "<br />Tutto andato a buon fine";
													$sql="commit";
													$mysqli->query($sql);
													header( "refresh:3;url=home.php" );
												}
												else	// se ci sono errori faccio un roolback all'ultima impostazione valida
												{
													$sql="rollback to inserimento;";
													$mysqli->query($sql);
													error();
													echo "Errore inserimento <br>";
												}
											}
										}
										else
										{
											$sql="rollback to inserimento;";
											$mysqli->query($sql);
											error();
											echo "Errore inserimento <br>";
										}
									}
									else		//		SECONDO c'è gia una fioritura uguale
									{
										while($rs=$result->fetch_array(MYSQLI_ASSOC))
										{
											$idFioritura=$rs["id"];
										}
										$sql="select id from vegetativa where temperatura_min='$V_tmin' and temperatura_max='$V_tmax' and umidita_min='$V_umimin' and umidita_max='$V_umimax' and ore_luce='$V_oreluce'";
										$result=$mysqli->query($sql);
										if(mysqli_num_rows($result)==0)
										{
											$sql="insert into vegetativa(ore_luce,temperatura_min,temperatura_max,umidita_min,umidita_max) values ('$V_oreluce','$V_tmin','$V_tmax','$V_umimin','$V_umimax')";
											if($mysqli->query($sql) == true)
											{
												$idVegetazione=$mysqli->insert_id;
												$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
												if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
												{
													echo "Tutto andato a buon fine";
													$sql="commit";
													$mysqli->query($sql);
													header( "refresh:3;url=home.php" );
												}
												else	// se ci sono errori faccio un roolback all'ultima impostazione valida
												{
													$sql="rollback to inserimento;";
													$mysqli->query($sql);
													error();
													echo "Errore inserimento <br>";
												}
											}
											else
											{
												$sql="rollback to inserimento;";
												$mysqli->query($sql);
												error();
												echo "Errore inserimento <br>";
											}
										}
										else
										{
											while($rs=$result->fetch_array(MYSQLI_ASSOC))
											{
												$idVegetazione=$rs["id"];
											}
											$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
											if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
											{
												echo "Tutto andato a buon fine";
												$sql="commit";
												$mysqli->query($sql);
												header( "refresh:3;url=home.php" );
											}
											else	// se ci sono errori faccio un roolback all'ultima impostazione valida
											{
												$sql="rollback to inserimento;";
												$mysqli->query($sql);
												error();
												echo "Errore inserimento <br>";
											}
										}
									}
								}
								else
								{
									$sql="rollback to inserimento;";
									$mysqli->query($sql);
									error();
									echo "Errore inserimento <br>";
								}
							}
							else	//		---PRIMO ELSE SE C'è GIA il tipo in UNA RIGA UGUALE
							{
								while($rs=$result->fetch_array(MYSQLI_ASSOC))
								{
									$idNomeTipo=$rs["id"];
								}
								$sql="select id from fioritura where temperatura_min='$tmin' and temperatura_max='$tmax' and umidita_min='$umimin' and umidita_max='$umimax' and ore_luce='$oreluce'";
								$result=$mysqli->query($sql);
								if(mysqli_num_rows($result)==0)
								{
									$sql="insert into fioritura(temperatura_min,temperatura_max,umidita_min,umidita_max,ore_luce) values ('$tmin','$tmax','$umimin','$umimax','$oreluce')";
									if($mysqli->query($sql) == true)
									{
										$idFioritura=$mysqli->insert_id;
										$sql="select id from vegetativa where temperatura_min='$V_tmin' and temperatura_max='$V_tmax' and umidita_min='$V_umimin' and umidita_max='$V_umimax' and ore_luce='$V_oreluce'";
										$result=$mysqli->query($sql);
										if(mysqli_num_rows($result)==0)
										{
											$sql="insert into vegetativa(ore_luce,temperatura_min,temperatura_max,umidita_min,umidita_max) values ('$V_oreluce','$V_tmin','$V_tmax','$V_umimin','$V_umimax')";
											if($mysqli->query($sql) == true)
											{
												$idVegetazione=$mysqli->insert_id;
												$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
												if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
												{
													echo "Tutto andato a buon fine";
													$sql="commit";
													$mysqli->query($sql);
													header( "refresh:3;url=home.php" );
												}
												else	// se ci sono errori faccio un roolback all'ultima impostazione valida
												{
													$sql="rollback to inserimento;";
													$mysqli->query($sql);
													error();
													echo "Errore inserimento <br>";
												}
											}
											else
											{
												$sql="rollback to inserimento;";
												$mysqli->query($sql);
												error();
												echo "Errore inserimento <br>";
											}
										}
										else	//fioritura uguale
										{
											while($rs=$result->fetch_array(MYSQLI_ASSOC))
											{
												$idFioritura=$rs["id"];
											}
											$sql="select id from vegetativa where temperatura_min='$V_tmin' and temperatura_max='$V_tmax' and umidita_min='$V_umimin' and umidita_max='$V_umimax' and ore_luce='$V_oreluce'";
											$result=$mysqli->query($sql);
											if(mysqli_num_rows($result)==0)
											{
												$sql="insert into vegetativa(ore_luce,temperatura_min,temperatura_max,umidita_min,umidita_max) values ('$V_oreluce','$V_tmin','$V_tmax','$V_umimin','$V_umimax')";
												if($mysqli->query($sql) == true)
												{
													$idVegetazione=$mysqli->insert_id;
													$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
													if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
													{
														echo "Tutto andato a buon fine";
														$sql="commit";
														$mysqli->query($sql);
														header( "refresh:3;url=home.php" );
													}
													else	// se ci sono errori faccio un roolback all'ultima impostazione valida
													{
														$sql="rollback to inserimento;";
														$mysqli->query($sql);
														error();
														echo "Errore inserimento <br>";
													}
												}
												else
												{
													$sql="rollback to inserimento;";
													$mysqli->query($sql);
													error();
													echo "Errore inserimento <br>";
												}
											}
											else	//se c'è una vegatativa uguale
											{
												while($rs=$result->fetch_array(MYSQLI_ASSOC))
												{
													$idVegetazione=$rs["id"];
												}
												$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
												if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
												{
													echo "Tutto andato a buon fine";
													$sql="commit";
													$mysqli->query($sql);
													header( "refresh:3;url=home.php" );
												}
												else	// se ci sono errori faccio un roolback all'ultima impostazione valida
												{
													$sql="rollback to inserimento;";
													$mysqli->query($sql);
													error();
													echo "Errore inserimento <br>";
												}
											}
										}
									}
									else
									{
										$sql="rollback to inserimento;";
										$mysqli->query($sql);
										error();
										echo "Errore inserimento <br>";
									}
								}
								else	//fioritura uguale
								{
									while($rs=$result->fetch_array(MYSQLI_ASSOC))
										{
											$idFioritura=$rs["id"];
										}
									$sql="select id from vegetativa where temperatura_min='$V_tmin' and temperatura_max='$V_tmax' and umidita_min='$V_umimin' and umidita_max='$V_umimax' and ore_luce='$V_oreluce'";
									$result=$mysqli->query($sql);
									if(mysqli_num_rows($result)==0)
									{
										$sql="insert into vegetativa(ore_luce,temperatura_min,temperatura_max,umidita_min,umidita_max) values ('$V_oreluce','$V_tmin','$V_tmax','$V_umimin','$V_umimax')";
										if($mysqli->query($sql) == true)
										{
											$idVegetazione=$mysqli->insert_id;
											$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
											if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
											{
												echo "Tutto andato a buon fine";
												$sql="commit";
												$mysqli->query($sql);
												header( "refresh:3;url=home.php" );
											}
											else	// se ci sono errori faccio un roolback all'ultima impostazione valida
											{
												$sql="rollback to inserimento;";
												$mysqli->query($sql);
												error();
												echo "Errore inserimento <br>";
											}
										}
										else
										{
											$sql="rollback to inserimento;";
											$mysqli->query($sql);
											error();
											echo "Errore inserimento <br>";
										}
									}
									else	//se c'è una vegatativa uguale
									{
										while($rs=$result->fetch_array())
										{
											$idVegetazione=$rs["id"];
										}
										$sql="insert into pianta(nome,tipo,vegetativa,fioritura) values ('$nome_P','$idNomeTipo','$idVegetazione','$idFioritura')";
										if($mysqli->query($sql) == true)	// se tutto a buon fine faccio un commit che evidenzia che il blocco di operazioni è andato a buon fine
										{
											echo "Tutto andato a buon fine";
											$sql="commit";
											$mysqli->query($sql);
											header( "refresh:3;url=home.php" );
										}
										else	// se ci sono errori faccio un roolback all'ultima impostazione valida
										{
											$sql="rollback to inserimento;";
											$mysqli->query($sql);
											error();
											echo "Errore inserimento <br>";
										}
									}
								}
							}
						}
					}
				}
			}
			else	//se non loggato segno un errore e lo forzo a index
			{			
				go_index();
			}
		}
	}
	else	//se per caso manca uno dei parametri o non è settata la sessione lo rimando a idex
	{	
		go_index();
	}
?>
</head>
<body>

</body>
</html>


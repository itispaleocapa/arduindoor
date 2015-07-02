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
if(isset($_SESSION["loggato"]))
{
	if($_SESSION["loggato"]==true)
	{
		if(isset($_GET["nome"]) && $_GET["nome"]!=NULL)
		{
			$pianta=$_GET["nome"];
			if (mysqli_connect_errno())// verifica dell'avvenuta connessione
			{
				error();
				echo "Errore in connessione al DBMS <br />";
				exit();// interruzione delle esecuzioni i caso di errore
			}
			$riga = $mysqli->query("SELECT tipo, vegetativa, fioritura FROM pianta WHERE nome='$pianta'");
			while($rs = $riga->fetch_array())
			{
				$tipo=$rs['tipo'];
				$veg=$rs['vegetativa'];
				$fio=$rs['fioritura'];
			}
			$riga=$mysqli->query("SELECT nome_tipo,zona,note FROM tipo WHERE id='$tipo'");
			while($rs = $riga->fetch_array())
			{
				$nome_tipo=$rs['nome_tipo'];
				$zona=$rs['zona'];
				$note=$rs['note'];
			}
			$riga=$mysqli->query("SELECT ore_luce, temperatura_min, temperatura_max, umidita_min, umidita_max FROM vegetativa WHERE id='$veg'");
			while($rs = $riga->fetch_array())
			{
				$vluce=$rs['ore_luce'];
				$vt_min=$rs['temperatura_min'];
				$vt_max=$rs['temperatura_max'];
				$vu_min=$rs['umidita_min'];
				$vu_max=$rs['umidita_max'];
			}
			$riga=$mysqli->query("SELECT ore_luce, temperatura_min, temperatura_max, umidita_min, umidita_max FROM fioritura WHERE id='$fio'");
			while($rs = $riga->fetch_array())
			{
				$fluce=$rs['ore_luce'];
				$ft_min=$rs['temperatura_min'];
				$ft_max=$rs['temperatura_max'];
				$fu_min=$rs['umidita_min'];
				$fu_max=$rs['umidita_max'];
			}
			
			echo"
					<div style=\"float:left; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px; margin:20px; padding:10px;\">
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:5px;\">
						<h3>TIPO&nbsp;PIANTA</h3>
						<div style=\"float:left; text-align:left; padding-right:5px; border-right:2px solid #ffffff;\">
							<label>Tipo della pianta:</label>
							<br />
							<label>Zona:</label>
							<br />
							<label>Note:</label>
						</div>
						<div style=\"float:left; text-align:left; margin-left:5px;\">
							<label>".$nome_tipo."</label>
							<br />
							<label>".$zona."</label>
							<br />
							<label>".$note."</label>
						</div>
					<div style=\"clear:both;\"></div>
						<br />
					</div>
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:5px;\">
						<h3>VEGETATIVA</h3>
						<div style=\"float:left; text-align:left; padding-right:5px; border-right:2px solid #ffffff;\">
							<label>Ore luce:</label>
							<br />
							<label>Temperatura minima:</label>
							<br />
							<label>Temperatura massima:</label>
							<br />
							<label>Umidit&agrave; minima:</label>
							<br />
							<label>Umidit&agrave; massima:</label>
							</div>
							<div style=\"float:left; text-align:left; margin-left:5px;\">
							<label>".$fluce."</label>
							<br />
							<label>".$ft_min."</label>
							<br />
							<label>".$ft_max."</label>
							<br />
							<label>".$fu_min."</label>
							<br />
							<label>".$fu_max."</label>
							</div>
					<div style=\"clear:both;\"></div>
						<br />
					</div>
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:5px;\">
						<h3>FIORITURA</h3>
						<div style=\"float:left; text-align:left; padding-right:5px; border-right:2px solid #ffffff;\">
							<label>Ore luce:</label>
							<br />
							<label>Temperatura minima:</label>
							<br />
							<label>Temperatura massima:</label>
							<br />
							<label>Umidit&agrave; minima:</label>
							<br />
							<label>Umidit&agrave; massima:</label>
							</div>
							<div style=\"float:left; text-align:left; margin-left:5px;\">
							<label>".$vluce."</label>
							<br />
							<label>".$vt_min."</label>
							<br />
							<label>".$vt_max."</label>
							<br />
							<label>".$vu_min."</label>
							<br />
							<label>".$vu_max."</label>
							</div>
					<div style=\"clear:both;\"></div>
						<br />
					</div>
					</div>
					<div style=\"clear:both;\"></div>";
			$us=$_SESSION["utente"];
			if (mysqli_connect_errno())// verifica dell'avvenuta connessione
			{
				error();
				echo "Errore in connessione al DBMS<br />";
				exit();// interruzione delle esecuzioni i caso di errore
			}
			$result = $mysqli->query("SELECT arduindoor_stato.id,indirizzo FROM arduindoor_stato,members WHERE members.id=arduindoor_stato.id_utente AND members.username='$us'");
			if($result->num_rows==0)	//se nessuna corrispondenza vuoto
			{
				error();
				echo "Nessun Arduindoor ancora registrato<br />";
				exit();
			}
			echo"
				<form method=\"post\" name=\"carica_dati\" action=\"carica.php\">
					<div style=\"float:left; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px; margin:20px; padding:10px;\">";
			while($rs = $result->fetch_array(MYSQLI_ASSOC)) //per tutti i risultati creo un pulsante per fare l'update
			{	//prima metto tutti i valori in campi hidden per la coltura in caso di fioritura 
				echo"
					<div style=\"float:left; margin:10px; border: 2px solid #ffffff; padding:10px; padding-right:20px;\">
					<input type=\"hidden\" name=\"arduindoor\" value=".$rs["indirizzo"]." />
					<label style=\"text-height:20px;\">Arduindoor".$rs["id"]."</label><br />
						<div style=\"float:left; border-right:2px solid #ffffff; width:50%;\">
					<input style=\"width:100%;\" type=\"submit\" name=\"load\" value=\"Carica fioritura\" />";
				echo"
					<input type=\"hidden\" name=\"ora_a\" value=\"8\" />
					<input type=\"hidden\" name=\"ora_s\" value=".(8+$fluce)." />
					<input type=\"hidden\" name=\"min_a\" value=\"0\" />
					<input type=\"hidden\" name=\"min_s\" value=\"0\" />
					<input type=\"hidden\" name=\"u_aria\" value=".(($fu_min+$fu_max)/2). " />
					<input type=\"hidden\" name=\"t_aria\" value=".(($ft_min+$ft_max)/2). " />
					";
				if($zona=="mediterranea")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"600\" />
						</form><br />";
				}
				else if($zona=="equatoriale")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"800\" />
						</form><br />";
				}
				else if($zona=="desertica")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"1000\" />
						</form><br />";
				}
				else if($zona=="tropicale")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"300\" />
						</form><br />";
				}
				else
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"500\" />
						</form><br />";
				}
				// poi metto le possibilita per la coltura di tipo vegatativa
				echo"
							</div>
							<div style=\"float:left; width:45%;\">
					<input type=\"hidden\" name=\"arduindoor\" value=".$rs["indirizzo"]." />
					<input type=\"submit\" name=\"load\" value=\"Carica vegetazione\" />";
				echo"
					<input type=\"hidden\" name=\"ora_a\" value=\"8\" />
					<input type=\"hidden\" name=\"ora_s\" value=".(8+$vluce)." />
					<input type=\"hidden\" name=\"min_a\" value=\"0\" />
					<input type=\"hidden\" name=\"min_s\" value=\"0\" />
					<input type=\"hidden\" name=\"u_aria\" value=".(($vu_min+$fu_max)/2). " />
					<input type=\"hidden\" name=\"t_aria\" value=".(($vt_min+$ft_max)/2). " />
					";
				if($zona=="mediterranea")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"600\" />
						</form><br />";
				}
				else if($zona=="equatoriale")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"800\" />
						</form><br />";
				}
				else if($zona=="desertica")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"1000\" />
						</form><br />";
				}
				else if($zona=="tropicale")
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"300\" />
						</form><br />";
				}
				else
				{
					echo"
						<input type=\"hidden\" name=\"terra\" value=\"500\" />
						</form><br />";
				}
				echo"
							</div>
					</div>
					<div style=\"clear:both;\"></div>";
			}
			echo"</div>";
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

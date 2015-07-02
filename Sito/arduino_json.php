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
include 'db_connect.php';	//includo il db
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
if(isset($_GET["mac"]))
{
	$mac=$_GET["mac"];
	$result = $mysqli->query("SELECT ora_accensione,minuto_accensione,ora_spegnimento,minuto_spegnimento,temperatura_aria,umidita_terra,umidita_aria FROM arduindoor_update WHERE indirizzo='$mac'");	//seleziono dal db
	if(!$result)
	{
		echo"{}";	// alla peggio devo sempre inviare una json vuota per evitare che arduino stalli dentro al ciclo di lettura
	}
	else if(mysqli_num_rows($result)>0)
	{
		$rs = $result->fetch_array(MYSQLI_ASSOC);
		$outp="{";
		if($rs["ora_accensione"]==0 && $rs["minuto_accensione"]==0 && $rs["ora_spegnimento"]==0 && $rs["minuto_spegnimento"]==0)	//se non ci sono update per le ore non le invio
		{
		}
		else	//altrimenti metto in testa gli update dell'ora
		{
			$outp .="ora_a:";
			$outp .= $rs["ora_accensione"];
			$outp .=",min_a:";
			$outp .= $rs["minuto_accensione"];
			$outp .=",ora_s:";
			$outp .= $rs["ora_spegnimento"];
			$outp .=",min_s:";
			$outp .= $rs["minuto_spegnimento"].",";
		}	//allego poi sempre gli update di temperatura e umidità aria ed umidità terra
		$outp .="temperatura:";
		$outp .= $rs["temperatura_aria"];
		$outp .=",umidita_t:";
		$outp .= $rs["umidita_terra"];
		$outp .=",umidita_a:";
		$outp .= $rs["umidita_aria"];
		$outp .="}";
		echo($outp);
		//$mysqli->query("DELETE from arduindoor_update WHERE indirizzo='$mac'");	//elimino l'update appena trasmesso
	}
	else
	{
		echo"{}";	//alla peggio invio una json vuota per evitare che arduino cicli all'infinito dentro la lettura
	}
}
?>

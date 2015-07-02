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
if(isset($_POST["utente"]))
{
	$us=$_POST["utente"];
	$result = $mysqli->query("SELECT arduindoor_stato.id, indirizzo, temperatura, umidita, irrigazione, stato_luce,indirizzo FROM arduindoor_stato,members WHERE members.id=arduindoor_stato.id_utente AND members.username='$us'");	//seleziono dal db
	$outp = "[";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {	//li restituisco con formato json
		if ($outp != "[") {$outp .= ",";}
		$outp .= '{"id":"'  . $rs["id"] . '",';
		$outp .= '"temperatura":"'   . $rs["temperatura"]        . '",';
		$outp .= '"umidita":"'   . $rs["umidita"]        . '",';
		$outp .= '"irrigazione":"'. $rs["irrigazione"]     . '",'; 
		$outp .= '"stato_luce":"'. $rs["stato_luce"]     . '",'; 
        $outp .= '"indirizzo":"' . $rs["indirizzo"]  .'"}';
	}
	$outp .="]";
   
	echo($outp);//restituisco il contenuto del formato json
}
else
	echo("[]");
?>

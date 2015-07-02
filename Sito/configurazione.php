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
    <title>Configurazione</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="http://yui.yahooapis.com/3.18.1/build/yui/yui-min.js"></script>
    <script>
       jQuery(document).ready(function()
       {
           YUI().use("node", "event", function (Y)
           {
               var zero = (document.getElementById("zero"));
               var uno = Y.one("#uno");
               //var due = Y.one("#due");
               var tre = Y.one("#tre");
               var quattro = Y.one("#quattro");
               var cinque = Y.one("#cinque");
               var sei = Y.one("#sei");
               var sette = Y.one("#sette");
               var otto = Y.one("#otto");
               var nove = Y.one("#nove");
               var div_height=zero.offsetHeight;
               uno.set("offsetHeight", div_height);
               //due.set("offsetHeight", div_height);
               tre.set("offsetHeight", div_height);
               quattro.set("offsetHeight", div_height);
               cinque.set("offsetHeight", div_height);
               sei.set("offsetHeight", div_height);
               sette.set("offsetHeight", div_height);
               otto.set("offsetHeight", div_height);
               nove.set("offsetHeight", div_height);
           });
       });
    </script>
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
    </head>
    <body>
    <?php
    include 'db_connect.php';
    if(isset($_SESSION["loggato"]))
    {
        if($_SESSION["loggato"]==true)
        {
            tasti_rapidi();
            /*
            -ora inizio luce 
            -minuto inizio luce 
            -ora fine luce 
            -minuto fine luce 
            -umidità aria 
            -umidità terra 
            -temperatura
            */
            if (mysqli_connect_errno())// verifica dell'avvenuta connessione
            {
                error();
                //echo "Errore in connessione al DBMS: ".mysqli_connect_error();// notifica in caso di errore
                exit();// interruzione delle esecuzioni i caso di errore
            }
            $us=$_SESSION["utente"];
            $result = $mysqli->query("SELECT arduindoor_stato.id,indirizzo,irrigazione,temperatura,umidita FROM arduindoor_stato,members WHERE members.id=arduindoor_stato.id_utente AND members.username='$us'");
            if(!$result)    //se nessuna corrispondenza vuoto
            {
                error();
                echo "<br>Nessun username corrispondente<br>";
                exit();
            }
			/* parte tolta causa disallineamento schermo
			<div id=\"due\" style=\"float:left; width:10%; border:2px solid #ffffff;\">
                      <label>Indirizzo</label>
                    </div>
			*/
            echo "<div style=\"float:left; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px; margin:30px; padding:10px; width:90%;\">
                  <div id=\"zero\" style=\"float:left; width:100%; border:0px solid #ffffff; margin-bottom:20px;\">
                    <div id=\"uno\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Arduindoor</label>
                    </div>
                    
                    <div id=\"tre\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Ora accensione luce</label>
                    </div>
                    <div id=\"quattro\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Minuto accensione luce</label>
                    </div>
                    <div id=\"cinque\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Ora spegnimento luce</label>
                    </div>
                    <div id=\"sei\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Minuto spegnimento luce</label>
                    </div>
                    <div id=\"sette\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Umidit&agrave aria</label>
                    </div>
                    <div id=\"otto\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Temperatura aria</label>
                    </div>
                    <div id=\"nove\" style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\">
                      <label>Umidit&agrave terra</label>
                    </div>
                    <div style=\"clear:both;\"></div>
                </div>
                <div style=\"clear:both;\"></div>";
            while($rs = $result->fetch_array(MYSQLI_ASSOC))
            {
                echo "<div style=\"float:left; width:100%; border:0px solid #ffffff; margin-top:5px;\">
                         <form method=\"post\" name=\"carica_dati\" action=\"carica.php\">";
                $indirizzo=$rs["indirizzo"];
                $id=$rs["id"];
                $outp ="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><div>Arduindoor".$id."</div>
                        <div><input type=\"submit\" name=\"load\" value=\"Carica impostazione\" /></div></div>";
                //$outp .="<div style=\"float:left; width:10%; border:2px solid #ffffff;\"><input type=\"hidden\" name=\"arduindoor\" value=".($indirizzo)." /> ".$indirizzo."</div>";
				$outp .="<input type=\"hidden\" name=\"arduindoor\" value=".($indirizzo)." /> ";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"ora_a\" method=\"post\">";
                for($i=0;$i<24;$i++)    //ore accensione
                {
                    $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"min_a\" method=\"post\">";
                for($i=0;$i<60;$i++)    //minuti accensione
                {
                    $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"ora_s\" method=\"post\">";
                for($i=0;$i<24;$i++)    //ore spegnimento
                {
                    $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"min_s\" method=\"post\">";
                for($i=0;$i<60;$i++)    //minuti spegnimento
                {
                    $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"u_aria\" method=\"post\">";
                for($i=0;$i<100;$i++)    //umidita aria in %
                {
                    if($i==$rs["umidita"])
                        $outp .="<option selected=\"selected\" value=".($i).">$i</option>";
                    else
                        $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"t_aria\" method=\"post\">";
                for($i=0;$i<36;$i++)    //temperatura aria
                {
                    if($i==$rs["temperatura"])
                        $outp .="<option selected=\"selected\" value=".($i).">$i</option>";
                    else
                        $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                $outp="<div style=\"float:left; width:10%; border:2px solid #ffffff; padding:10px;\"><select name=\"terra\" method=\"post\">";
                for($i=0;$i<1024;$i++)    //umidità terra
                {
                    if($i==$rs["irrigazione"])
                        $outp .="<option selected=\"selected\" value=".($i).">$i</option>";
                    else
                        $outp .="<option value=".($i).">$i</option>";
                }
                $outp .= "</select></div>";
                echo $outp;
                echo"<div style=\"clear:both;\"></div>
                </form></div>";
            }
        echo '</div>';
        }
        else
        go_index();
    }
    else
        go_index();
    
    ?>


</body>

</html>


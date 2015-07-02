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
function sec_session_start() {
        $session_name = 'sec_session_id'; // Imposta un nome di sessione
        $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
        $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
        ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
        $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
        session_start(); // Avvia la sessione php.
        session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
}
function login($us,$ps) //funzione login, ritorna true se username e password sono validi altrimenti false
	{
		include 'db_connect.php';
		
		if($us!="" && $us!=null) //se nessun username login non valido
		{
			if (mysqli_connect_errno())// verifica dell'avvenuta connessione
			{
				echo "Errore in connessione al DBMS: ".mysqli_connect_error();// notifica in caso di errore
				exit();// interruzione delle esecuzioni i caso di errore
			}
			/*$stmt=$mysqli->prepare("SELECT username, password FROM members WHERE username='$us'");//preparo il contenuto della query
			$stmt->execute(); //la eseguo
			$result = $stmt->get_result();	//estraggo i risultati ****così sarebbe stato anti sql-injection
			*/
			$sql="SELECT username, password FROM members WHERE username='$us'";
			$result = $mysqli->query($sql);	//seleziono solo le linee in cui lo username corrisponde a quello inserito
			if(!$result)	//se nessuna corrispondenza login non valido
			{
				return false;
			}
			else
			{
				while($rs = $result->fetch_array(MYSQLI_ASSOC))	//controllo per tutte le corrispondenze se una ha la password uguale e quindi c'è corrispondenza username e password
				{
					//if($ps==$rs["password"])
					$inserita=$rs["password"];
					if($inserita==crypt($ps, $inserita))	//controllo criptando le password
					{
						return true;
						exit();
					}
				}
			return false;
			}
		}
		else
		return false;
	}
	function mostra_arduindoor()
	{
		include 'db_connect.php';
		//$conn = new mysqli("localhost", "root", "", "my_arduindoor"); //mi connetto al database e chiedo tutti gli arduindoor con utente corrispndente
		$us=$_SESSION["utente"];
		if (mysqli_connect_errno())// verifica dell'avvenuta connessione
			{
				error();
				//echo "Errore in connessione al DBMS: ".mysqli_connect_error();// notifica in caso di errore
				exit();// interruzione delle esecuzioni i caso di errore
			}
		if($us!="guest")
			{
			$sql="SELECT arduindoor_stato.id,temperatura, umidita, irrigazione, stato_luce FROM arduindoor_stato,members WHERE members.id=arduindoor_stato.id_utente AND members.username='$us'";
			}
		else
			{
			$sql="SELECT arduindoor_stato.id,temperatura, umidita, irrigazione, stato_luce FROM arduindoor_stato limit 9";
			}
		$result = $mysqli->query($sql);
		if($result->num_rows == 0)	//se nessuna corrispondenza vuoto
			{
				error();
				echo "Nessun Arduindoor ancora registrato";
				exit();
			}
		echo "<div style=\"float:left; margin:30px; padding:15px; padding-left:0px; border: 2px solid #ffffff; border-radius:10px 10px 10px 10px;\">";
		$i=0;
		while($rs = $result->fetch_array(MYSQLI_ASSOC)) //per tutti i risultati inserisco i valori nella struttura base
		{
			$outp ="<div style=\"float:left; margin-left:15px; border: 2px solid #ffffff; background: url(./fondale.jpg); background-repeat: no-repeat; background-size: 100% 150%; color: #000000;\">";
			$outp .= "<div style=\"padding:10px;\"><h1>Arduindoor".$rs["id"]."</h1></div>";
			$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./temperatura.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Temperatura:&nbsp;". $rs["temperatura"] ." &deg;</label></div><div style=\"clear:both;\"></div>";
			$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./umidita.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Umidita:&nbsp;". $rs["umidita"] ." %</div><div style=\"clear:both; padding:10px;\"></div>";
			if($rs["irrigazione"]>1000)
				$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./irrigazione.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Irrigazione:&nbsp;Arido (".$rs["irrigazione"].")</div><div style=\"clear:both;\"></div>";
			else if($rs["irrigazione"]<1000 && $rs["irrigazione"]>400)
				$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./irrigazione.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Irrigazione:&nbsp;Umido (".$rs["irrigazione"].")</div><div style=\"clear:both;\"></div>";
			else
				$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./irrigazione.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Irrigazione:&nbsp;Allagato (".$rs["irrigazione"].")</div><div style=\"clear:both;\"></div>";
			if($rs["stato_luce"]==1)
				$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./on.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Stato luce:&nbsp;Accesa</div><div style=\"clear:both;\"></div>";
			else
				$outp .= "<div style=\"float:left; padding:10px;\"><label><img src=\"./off.png\" style=\"width:80px; height:80px; vertical-align:middle;\" alt=\"Server unreachable\" />&nbsp;Stato luce:&nbsp;Spenta</div><div style=\"clear:both;\"></div>";
			$outp .="</div>";
			echo $outp;
			$i++;
			if($i%3==0)
				echo "<div style=\"clear:both;\"></div><br />";
		}
		echo"</div>";
		
	}
	function tasti_rapidi()
	{
		echo"
	         <style type=\"text/css\">
	             #pulsante
				 {
                     background: #FFFFFF;
					 color: black;
	                 opacity:0.9;
					 float:left; 
					 padding:30px;
                     transition: background 0.5s;
                     -webkit-transition: background 0.5s; /* Safari */
                 }
                 #pulsante:hover
				 {
                     background: #2ECC71;
                 }
	        </style>
			<div>
				<form action=\"index.php\" name=\"fallito\">
			        <div id=\"pulsante\" onclick=\"document.forms.fallito.submit();\">
			            <label>Logout</label>
				    </div>
			    </form>
				<form action=\"home.php\" name=\"go_home\">
			        <div id=\"pulsante\" onclick=\"document.forms.go_home.submit();\">
			            <label>Torna alla home</label>
				    </div>
			    </form>
				<form action=\"inserimento.php\" name=\"aggiungi_coltura\">
			        <div id=\"pulsante\" onclick=\"document.forms.aggiungi_coltura.submit();\">
			            <label>Aggiungi delle nuove colture</label>
				    </div>
			    </form>
				<form action=\"configurazione.php\" name=\"config\">
			        <div id=\"pulsante\" onclick=\"document.forms.config.submit();\">
			            <label>Configura arduindoor</label>
				    </div>
			    </form>
				<form action=\"mostra.php\" name=\"show\">
			       <div id=\"pulsante\" onclick=\"document.forms.show.submit();\">
			            <label>Vedi le colture</label>
				    </div>
			    </form>
				<form action=\"registrazione.php\" name=\"reg\">
			        <div id=\"pulsante\" onclick=\"document.forms.reg.submit();\">
			            <label>Registra arduindoor</label>
				    </div>
			    </form>
				<div style=\"clear:both;\"></div>
			</div>
			";
	}
	
	function go_index()
	{
		echo "<center>
			<form action=\"index.php\" name=\"fallito\">
			<h1>Login fallito</h1><br />
			<input type=\"submit\" name=\"back\" value=\"Riprova ad accedere\" aling=\"center\"><br /><br />
			<img src=\"./death_link.jpg\" alt=\"Server unreachable\" />
			</form></center>";
	}
	function error()
	{
		echo "<center><form><br><br>
			<img src=\"./error.png\" alt=\"Error accured\" />
			<h1>Errore</h1><br />
			<input type=\"button\" VALUE=\"Back\" onClick=\"history.go(-1);\"><br>
			</form></center>";
	}
/*
function login($user, $passwd, $mysqli) {
   // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
   if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM members WHERE email = ? LIMIT 1")) { 
      $stmt->bind_param('s', $user); // esegue il bind del parametro '$user'.
      $stmt->execute(); // esegue la query appena creata.
      $stmt->store_result();
      $stmt->bind_result($user_id, $username, $db_password, $salt); // recupera il risultato della query e lo memorizza nelle relative variabili.
      $stmt->fetch();
      $password = hash('sha512', $passwd.$salt); // codifica la password usando una chiave univoca.
      if($stmt->num_rows == 1) { // se l'utente esiste
         // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
         if(checkbrute($user_id, $mysqli) == true) { 
            // Account disabilitato
            // Invia un e-mail all'utente avvisandolo che il suo account è stato disabilitato.
            return false;
         } else {
         if($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
            // Password corretta!            
               $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
 
               $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
               $_SESSION['user_id'] = $user_id; 
               $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
               $_SESSION['username'] = $username;
               $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
               // Login eseguito con successo.
               return true;    
         } else {
            // Password incorretta.
            // Registriamo il tentativo fallito nel database.
            $now = time();
            $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
            return false;
         }
      }
      } else {
         // L'utente inserito non esiste.
         return false;
      }
   }
}

function checkbrute($user_id, $mysqli) {
   // Recupero il timestamp
   $now = time();
   // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
   $valid_attempts = $now - (2 * 60 * 60); 
   if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) { 
      $stmt->bind_param('i', $user_id); 
      // Eseguo la query creata.
      $stmt->execute();
      $stmt->store_result();
      // Verifico l'esistenza di più di 5 tentativi di login falliti.
      if($stmt->num_rows > 5) {
         return true;
      } else {
         return false;
      }
   }
}

function login_check($mysqli) {
   // Verifica che tutte le variabili di sessione siano impostate correttamente
   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['user_id'];
     $login_string = $_SESSION['login_string'];
     $username = $_SESSION['username'];     
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
     if ($stmt = $mysqli->prepare("SELECT password FROM members WHERE id = ? LIMIT 1")) { 
        $stmt->bind_param('i', $user_id); // esegue il bind del parametro '$user_id'.
        $stmt->execute(); // Esegue la query creata.
        $stmt->store_result();
 
        if($stmt->num_rows == 1) { // se l'utente esiste
           $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
           $stmt->fetch();
           $login_check = hash('sha512', $password.$user_browser);
           if($login_check == $login_string) {
              // Login eseguito!!!!
              return true;
           } else {
              //  Login non eseguito
              return false;
           }
        } else {
            // Login non eseguito
            return false;
        }
     } else {
        // Login non eseguito
        return false;
     }
   } else {
     // Login non eseguito
     return false;
   }
}*/
?>



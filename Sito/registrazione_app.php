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
	
	if(isset($_POST["cellulare"]))
	{
		if($_POST["cellulare"]=="yes")
		{
			$criptata=crypt($_POST["ps"]);
			$us=$_POST["us"];
			$sql="insert into members(username,password) values('$us','$criptata')";
			if($mysqli->query($sql)==true)
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
			echo "0";
		}
	}
	else
	{
		echo "0";
	}
?>

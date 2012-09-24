<?php
	
	//retreive values from cookie
	$them = $_COOKIE['chat_them'];
	$you = $_COOKIE['chat_you'];	
	
	if (isset($_POST['message']) 
		&& !empty($_POST['message']))
	{
		if (mysql_connect("hostname","user","password"))
		{
			if (mysql_select_db("db"))
			{
				//sanitize input
				$message = mysql_real_escape_string($_POST['message']);
				
				if (mysql_query("INSERT INTO `messages` (`sender`,`message`,`timestamp`,`read`,`recipient`) VALUES ('{$you}','{$message}',NOW(),0,'{$them}')"))								
					echo "sent";				
				else
					echo "INSERT error";
			}
			else
				echo "Database error";
		}		
		else		
			echo "Connection error";	
	}
	else
		echo "POST empty";
	
	
?>
<?php
		
	setcookie('chat_you','',time()-36000); 
	setcookie('chat_them','',time()-36000); 
	unset($_COOKIE['chat_you']); 
	unset($_COOKIE['chat_them']); 
	
	
	
	header("Location:index.php");
?>
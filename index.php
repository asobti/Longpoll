<?php
	if (isset($_COOKIE['chat_you']) && isset($_COOKIE['chat_them']))
	{
		header("Location:chat.php");
	}
?>
<html>
<head>
	<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">		
		<title>Long Polling Demo</title>
		<meta name="description" content="Long Polling Demo">
		<meta name="author" content="Ayush Sobti">								    	
	    <link type="text/css" rel="stylesheet" href="style.css" media="all"/>
	    <link href='http://fonts.googleapis.com/css?family=Buenard' rel='stylesheet' type='text/css'>
   	    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>				
		<script>
			$(document).ready(function(){
				$('#login').submit(function(){
					var you = $('#yourname').val();
					var them = $('#theirname').val();					
					if (!you || !them)
					{
						$('#loginstatus').slideDown('slow').delay(3000).slideUp('slow');
						return false;
					}		
					
				});
			});
		</script>
</head>
<body>
	<div id="container">		
		<form id="login" action="chat.php" method="post">	
			<p id="welcome">Sign in below</p>
			<label for="yourname">Your name: </label><div style="clear:both"></div>
			<input id="yourname" class="inputbox" name="yourname" class="logintext" type="text" placeholder="Your name"/>		
			
			<label for="theirname">Who you're chatting with: </label>
			<input id="theirname" class="inputbox" name="theirname" class="logintext" type="text" placeholder="Whom do you want to chat with"/>		
			
			<input id="startChat" type="submit" value="Start" class="submit" style="height:40px;"/>
			<p id="loginstatus" class="error">You must fill both fields</p>
		</form>		
		
	</div>
	
</body>
</html>
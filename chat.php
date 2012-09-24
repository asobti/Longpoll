<?php	
	if (isset($_POST))	
	{
		if (isset($_POST['yourname']) 
			&& isset($_POST['theirname'])
			&& !empty($_POST['theirname'])
			&& !empty($_POST['yourname']))
		{
			setcookie('chat_you',$_POST['yourname']);
			setcookie('chat_them',$_POST['theirname']);
			
		}
	}
	
	if (!isset($_COOKIE['chat_you']) || !isset($_COOKIE['chat_them']))	
		header('Location:index.php');
	
?>
<html>
<head>
	<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">		
		<title>Long Polling Demo</title>
		<meta name="description" content="Long Polling Demo">
		<meta name="author" content="Enginuity Diagnostics">								    	
	    <link type="text/css" rel="stylesheet" href="style.css" media="all"/>
   	    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				prepareForm();				
			});
		</script>
</head>
<body>
	<div id="container" style="margin-top:20px;">
		<a href="logout.php" style="margin-left:80%">Logout</a>			
		<form id="sendform" action="#" method="post">			
			<textarea id="listenmessage" class="message" readonly="readonly" style="background:ivory;padding-top:0px !important"></textarea>							
			<textarea id="sendmessage" class="message" style="height:80px;" placeholder="Message"></textarea>
			<input id="sendSubmit" type="submit" value="Send" class="submit" style="width:50%;height:40px;"/>
			<p id="sendstatus" class="status">Message sent</p>
		</form>		
	</div>
	
</body>
</html>
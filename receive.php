<?php
	
	$messageReceived = false;
	$you = $_COOKIE['chat_you'];
	$them = $_COOKIE['chat_them'];
	
	if (mysql_connect("hostname","user","password"))
	{
		if (mysql_select_db("db"))
		{
			while ($messageReceived == false)
			{
				$query = "SELECT * FROM `messages` where `messageid` IN (SELECT MAX(`messageid`) FROM `messages` WHERE `read`=0 AND `recipient`='{$you}' AND `sender` = '{$them}' ORDER BY `messageid` DESC)";
				$res = mysql_query($query);
				
				if (mysql_num_rows($res) > 0)
				{
					$msg = mysql_fetch_array($res);
					
					$jsonObj = Array(
									'sender' => $msg['sender'],
									'message' => $msg['message'],
									'timestamp' => $msg['timestamp']
									);
					
					mysql_query("UPDATE `messages` SET `read`=1 WHERE `messageid`={$msg['messageid']}");
					mysql_close();
					$messageReceived = true;
					echo json_encode($jsonObj);
				}
				sleep(1);
			}
			
		}
	}
	
?>
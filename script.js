function prepareForm()
{	
	beginListening();	
	
	$('#sendform').submit(function(){
		//disable the Send button while the message is being sent
		$('#sendSubmit').val('Sending...');
		$('#sendSubmit').attr('disabled',true); 
		
		//issue the AJAX request
		$.ajax({
		  url: "send.php",
		  type: "POST",		
		  data: {name:$('#sendname').val(),message:$('#sendmessage').val()},
		  success: function(resp){				
			if (resp != "sent")		//Some failure occured
			{						
				$('#sendstatus').text('Failed').slideDown('slow').delay(1500).slideUp('slow');					
			}
			else		//successful
			{
				//add the just sent message to the chat box
				var disp = "You:\n" + $('#sendmessage').val() + "\n";
				$('#listenmessage').val($('#listenmessage').val() + "\n" + disp);
				$('#sendmessage').val('');
			}
			
			//enable the send button
			$('#sendSubmit').val('Send');
			$('#sendSubmit').removeAttr('disabled');			
		  }
	});
			
			
		return false;
	});
	
}

function beginListening()
{
	var ajaxlisten = $.ajax({
		  url: "receive.php",
		  type: "POST",			  
		  success: function(resp){
				var msg = JSON.parse(resp);
				var display = msg['sender'] + ":\n" + msg['message'] + "\n";
				$('#listenmessage').val($('#listenmessage').val() + "\n" + display);
				beginListening();
		  }
		});	
}
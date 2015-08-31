$(function () {
	chat.getMsgs();
	$('#btnMsg').click(chat.sendMsg);
	
 });

var chat={
		
		getMsgs:function (){
			 $.ajax({  
		         type:"POST",  
		         url:_request+"index.php/chat/getMsgs",  
		         dataType:"json",  
		         //timeout:10000,  
		         data:{time:"10000"},  
		         success:function(data,textStatus){ 
		        	   if(data!=""){
		        	    var userlist=data.user;
		        	    var message=data.message;
		        	    if(userlist!=""){
		        	    	$('#user').html("");
		        	    }
		        	    for(var por in userlist){
		        	    	$('#user').append(userlist[por]+"<br>");
		        	    }
		        	    if(message!=null){
		        	 	for(var i=0;i<message.length;i++){
		        	 		$('#msgs').append(message[i]+"<br>");
		        	 	}
		        	    }
		         	}
		        	 	chat.getMsgs();  
		         },  

		         error: function(XMLHttpRequest,textStatus,errorThrown){  
		                 alert("error:"+textStatus);  
		                 if(textStatus=="timeout")  
		                     alert("timeout!");
		         }  
		     });  
		},
		sendMsg:function(){
			var msg=$('#msg').val();
			 $.ajax({  
		         type:"POST", 
		         url:_request+"index.php/chat/sendMsg",  
		         dataType:"json",  
		         data:{msg:msg},  
		         success:function(data,textStatus){
		        	 $('#msg').val('');
		        	 $('#msg').focus();
		         },
		         error: function(XMLHttpRequest,textStatus,errorThrown){   
		                 if(textStatus=="timeout")  
		                     alert("timeout!");
		         }  
		     });  
		},
		
}
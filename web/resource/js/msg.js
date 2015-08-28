$(function () {
	alert("test");
	 $.ajax({  
         type:"POST",  
         url:"/mylogin/index.php/chat/getMsgs",  
         dataType:"json",  
         timeout:10000,  
         data:{time:"10000"},  
         success:function(data,textStatus){  
                 alert("ok!");  
         },  
         complete:function(XMLHttpRequest,textStatus){  
                 if(XMLHttpRequest.readyState=="4"){  
                     alert(XMLHttpRequest.responseText);  
                 }  
         },  
         error: function(XMLHttpRequest,textStatus,errorThrown){  
                 alert("error:"+textStatus);  
                 if(textStatus=="timeout")  
                     alert("timeout!");
         }  
     });  
 });
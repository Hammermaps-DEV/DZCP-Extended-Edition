var event_data;var xmlHttpObject;self.onmessage=function(e){event_data=e.data;if(typeof XMLHttpRequest!="undefined")xmlHttpObject=new XMLHttpRequest;if(!xmlHttpObject){try{xmlHttpObject=new ActiveXObject("Msxml2.XMLHTTP")}catch(t){try{xmlHttpObject=new ActiveXObject("Microsoft.XMLHTTP")}catch(t){xmlHttpObject=null}}}xmlHttpObject.onreadystatechange=function(){if(xmlHttpObject.readyState==4&&xmlHttpObject.status==200)postMessage({data:xmlHttpObject.responseText,tag:event_data.tag,status:"200"})};xmlHttpObject.open("GET",event_data.ajax,true);xmlHttpObject.send()}
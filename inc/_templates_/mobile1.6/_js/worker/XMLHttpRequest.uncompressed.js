//For HTML5 Web Workers
var event_data; var xmlHttpObject;
self.onmessage = function(event)
{
    event_data = event.data;
    if (typeof XMLHttpRequest != 'undefined')
        xmlHttpObject = new XMLHttpRequest();

    if (!xmlHttpObject)
    {
        try
        { xmlHttpObject = new ActiveXObject("Msxml2.XMLHTTP"); }
        catch(e)
        {
            try
            { xmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP"); }
            catch(e)
            { xmlHttpObject = null; }
        }
    }

    xmlHttpObject.onreadystatechange=function()
    {
        if (xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200)
            postMessage({'data' : xmlHttpObject.responseText, 'tag' : event_data.tag, 'status' : '200'});
    }

    //xmlHttpObject.timeout = 18000;
    //xmlHttpObject.ontimeout = function () { postMessage({'data' : 'XMLHttpRequest Timeout', 'tag' : event_data.tag, 'status' : ''}); }
    xmlHttpObject.open("GET",event_data.ajax,true);
    xmlHttpObject.send();
};
var doc=document,ie4=document.all,opera=window.opera;var innerLayer,layer,x,y,doWheel=false,offsetX=15,offsetY=5;var tickerc=0,mTimer=new Array,tickerTo=new Array,tickerSpeed=new Array;var shoutInterval=15e3;var teamspeakInterval=15e3;var DZCP={init:function(){doc.body.id="dzcp-engine-1.5";$("body").append('<div id="infoDiv"></div>');layer=$("#infoDiv")[0];doc.body.onmousemove=DZCP.trackMouse;if($("#navShout")[0])window.setInterval("$('#navShout').load('../inc/ajax.php?i=shoutbox');",shoutInterval);if($("#navTeamspeakContent")[0])window.setInterval("$('#navTeamspeakContent').load('../inc/ajax.php?i=teamspeak');",teamspeakInterval);DZCP.initLightbox()},initLightbox:function(){$("a[rel^=lightbox]").lightBox({fixedNavigation:true,overlayBgColor:"#000",overlayOpacity:.8,imageLoading:"../inc/images/lightbox/loading.gif",imageBtnClose:"../inc/images/lightbox/close.gif",imageBtnPrev:"../inc/images/lightbox/prevlabel.gif",imageBtnNext:"../inc/images/lightbox/nextlabel.gif",containerResizeSpeed:350,txtImage:lng=="de"?"Bild":"Image",txtOf:lng=="de"?"von":"of",maxHeight:screen.height*.9,maxWidth:screen.width*.9})},addEvent:function(a,b,c){if(a.addEventListener){a.addEventListener(b,c,false);return true}else if(a.attachEvent){var d=a.attachEvent("on"+b,c);return d}else return false},trackMouse:function(a){innerLayer=$("#infoInnerLayer")[0];if(typeof layer=="object"){var b=doc.all;var c=doc.getElementById&&!doc.all;var d=5;var e=-15;x=c?a.pageX-d:window.event.clientX+doc.documentElement.scrollLeft-d;y=c?a.pageY-e:window.event.clientY+doc.documentElement.scrollTop-e;if(innerLayer){var f=(b?innerLayer.offsetWidth:innerLayer.clientWidth)-3;var g=b?innerLayer.offsetHeight:innerLayer.clientHeight}else{var f=(b?layer.clientWidth:layer.offsetWidth)-3;var g=b?layer.clientHeight:layer.offsetHeight}var h=c?window.innerWidth+window.pageXOffset-12:doc.documentElement.clientWidth+doc.documentElement.scrollLeft;var i=c?window.innerHeight+window.pageYOffset:doc.documentElement.clientHeight+doc.documentElement.scrollTop;layer.style.left=(x+offsetX+f>=h-offsetX?x-(f+offsetX):x+offsetX)+"px";layer.style.top=y+offsetY+"px"}return true},popup:function(a,b,c){a=a.indexOf("img=")==-1?a:"../popup.php?"+a;b=parseInt(b);c=parseInt(c)+50;popup=window.open(a,"Popup","width=1,height=1,location=0,scrollbars=0,resizable=1,status=0");popup.resizeTo(b,c);popup.moveTo((screen.width-b)/2,(screen.height-c)/2);popup.focus()},initGameServer:function(a){$(function(){$("#navGameServer_"+a).load("../inc/ajax.php?i=server&serverID="+a)})},initTeamspeakServer:function(){$(function(){$("#navTeamspeakServer").load("../inc/ajax.php?i=teamspeak")})},initXfire:function(a){$(function(){$("#infoXfire_"+a).load("../inc/ajax.php?i=xfire&username="+a)})},shoutSubmit:function(){$.post("../shout/index.php?ajax",$("#shoutForm").serialize(),function(a){if(a)alert(a.replace(/  /g," "));$("#navShout").load("../inc/ajax.php?i=shoutbox");if(!a)$("#shouteintrag").attr("value","")});return false},switchuser:function(){var a=doc.formChange.changeme.options[doc.formChange.changeme.selectedIndex].value;window.location.href=a},tempswitch:function(){var a=doc.form.tempswitch.options[doc.form.tempswitch.selectedIndex].value;if(a!="lazy")DZCP.goTo(a)},goTo:function(a,b){if(b==1)window.open(a);else window.location.href=a},maxlength:function(a,b,c){if(a.value.length>c)a.value=a.value.substring(0,c);else b.value=c-a.value.length},showInfo:function(a,b,c,d,e,f){if(typeof layer=="object"){var g="";if(b&&c){var h=b.split(";");var i=c.split(";");var j="";for(var k=0;k<h.length;++k){j=j+"<tr><td>"+h[k]+"</td><td>"+i[k]+"</td></tr>"}g='<tr><td class="infoTop" colspan="2">'+a+"</td></tr>"+j+""}else if(b&&typeof c=="undefined"){g='<tr><td class="infoTop" colspan="2">'+a+"</td></tr><tr><td>"+b+"</td></tr>"}else{g="<tr><td>"+a+"</td></tr>"}var l="";if(d){l='<tr><td colspan=2 align=center><img src="'+d+'" width="'+e+'" height="'+f+'" alt="" /></td></tr>'}else{l=""}layer.innerHTML='<div id="hDiv">'+'  <table class="hperc" cellspacing="0" style="height:100%">'+"    <tr>"+'      <td style="vertical-align:middle">'+'        <div id="infoInnerLayer">'+'          <table class="hperc" cellspacing="0">'+"              "+g+""+"              "+l+""+"          </table>"+"        </div>"+"      </td>"+"    </tr>"+"  </table>"+"</div>";if(ie4&&!opera){layer.innerHTML+='<iframe id="ieFix" frameborder="0" width="'+$("#hDiv")[0].offsetWidth+'" height="'+$("#hDiv")[0].offsetHeight+'"></iframe>';layer.style.display="block"}else layer.style.display="block"}},hideInfo:function(){if(typeof layer=="object"){layer.innerHTML="";layer.style.display="none"}},toggle:function(a){if(a==0)return;else{if($("#more"+a).css("display")=="none"){$("#more"+a).css("display","");$("#img"+a).attr("src","../inc/images/collapse.gif")}else{$("#more"+a).css("display","none");$("#img"+a).attr("src","../inc/images/expand.gif")}}},fadetoggle:function(a){$("#more_"+a).fadeToggle("slow","swing");if($("#img_"+a).attr("alt")=="hidden"){$("#img_"+a).attr({alt:"normal",src:"../inc/images/toggle_normal.png"})}else{$("#img_"+a).attr({alt:"hidden",src:"../inc/images/toggle_hidden.png"})}},resizeImages:function(){for(var a=0;a<doc.images.length;a++){var b=doc.images[a];if(b.className=="content"){var c=b.width;var d=b.height;if(maxW!=0&&c>maxW){b.width=maxW;b.height=Math.round(d*(maxW/c));if(!DZCP.linkedImage(b)){var e=doc.createElement("span");var f=doc.createElement("a");e.appendChild(doc.createElement("br"));e.setAttribute("class","resized");e.appendChild(doc.createTextNode("auto resized to "+b.width+"x"+b.height+" px"));f.setAttribute("href",b.src);f.setAttribute("rel","lightbox");f.appendChild(b.cloneNode(true));b.parentNode.appendChild(e);b.parentNode.replaceChild(f,b);DZCP.initLightbox()}}}}},linkedImage:function(a){do{a=a.parentNode;if(a.nodeName=="A")return true}while(a.nodeName!="TD"&&a.nodeName!="BODY");return false},calSwitch:function(a,b){$("#navKalender").load("../inc/ajax.php?i=kalender&month="+a+"&year="+b)},teamSwitch:function(a){clearTimeout(mTimer[1]);$("#navTeam").load("../inc/ajax.php?i=teams&tID="+a,DZCP.initTicker("teams","h",60))},ajaxVote:function(a){DZCP.submitButton("contentSubmitVote");$.post("../votes/index.php?action=do&ajax=1&what=vote&id="+a,$("#navAjaxVote").serialize(),function(a){$("#navVote").html(a)});return false},ajaxFVote:function(a){DZCP.submitButton("contentSubmitFVote");$.post("../votes/index.php?action=do&fajax=1&what=fvote&id="+a,$("#navAjaxFVote").serialize(),function(a){$("#navFVote").html(a)});return false},ajaxPreview:function(a){var b=doc.getElementsByTagName("textarea");for(var c=0;c<b.length;c++){var d=b[c].className;var e=b[c].id;if(d=="editorStyle"||d=="editorStyleWord"||d=="editorStyleNewsletter"){var f=tinyMCE.getInstanceById(e);$("#"+e).attr("value",f.getBody().innerHTML)}}$("#previewDIV").html('<div style="width:100%;text-align:center">'+' <img src="../inc/images/admin/loading.gif" alt="" />'+"</div>");var g=prevURL;var h=a=="cwForm"?"&s1="+$("#screen1").attr("value")+"&s2="+$("#screen2").attr("value")+"&s3="+$("#screen3").attr("value")+"&s4="+$("#screen4").attr("value"):"";$.post(g,$("#"+a).serialize()+h,function(a){$("#previewDIV").html(a)})},del:function(a){a=a.replace(/\+/g," ");a=a.replace(/oe/g,"�");return confirm(a+"?")},hideForumFirst:function(){$("#allkat").attr("checked",false)},hideForumAll:function(){for(var a=0;a<doc.forms["search"].elements.length;a++){var b=doc.forms["search"].elements[a];if(b.id.match(/k_/g))b.checked=false}},submitButton:function(a){submitID=a?a:"contentSubmit";$("#"+submitID).attr("disabled",true);$("#"+submitID).css("color","#909090");$("#"+submitID).css("cursor","default");return true},initTicker:function(a,b,c){tickerTo[tickerc]=b=="h"||b=="v"?b:"v";tickerSpeed[tickerc]=parseInt(c)<=10?10:parseInt(c);var d=$("#"+a).html();var e='  <div id="scrollDiv'+tickerc+'" class="scrollDiv" style="position:relative;left:0;z-index:1">';e+='    <table id="scrollTable'+tickerc+'" class="scrolltable"  cellpadding="0" cellspacing="0">';e+="      <tr>";e+='        <td onmouseover="clearTimeout(mTimer['+tickerc+'])" onmouseout="DZCP.startTickerDiv('+tickerc+')">';for(var f=0;f<10;f++)e+=d;e+="        </td>";e+="      </tr>";e+="    </table>";e+="  </div>";$("#"+a).html(e);window.setTimeout("DZCP.startTickerDiv("+tickerc+");",1500);tickerc++},startTickerDiv:function(a){tableObj=$("#scrollTable"+a)[0];obj=tableObj.parentNode;objWidth=tickerTo[a]=="h"?tableObj.offsetWidth:tableObj.offsetHeight;newWidth=Math.floor(objWidth/2)*2+2;obj.style.width=newWidth;mTimer[a]=setInterval("DZCP.moveDiv('"+obj.id+"', "+newWidth+", "+a+");",tickerSpeed[a])},moveDiv:function(a,b,c){var d=$("#"+a)[0];if(tickerTo[c]=="h")d.style.left=parseInt(d.style.left)<=0-b/2+2?0:parseInt(d.style.left)-1+"px";else d.style.top=d.style.top==""||parseInt(d.style.top)<0-b/2+6?0:parseInt(d.style.top)-1+"px"},addFlash:function(){var a=new Object;a.embedAttrs=new Object;a.params=new Object;a.objAttrs=new Object;var b=new Array("menu|false","quality|high","wmode|transparent","classid|clsid:d27cdb6e-ae6d-11cf-96b8-444553540000","type|application/x-shockwave-flash");for(var c=0;c<arguments.length;c=c+2){a.objAttrs[arguments[c]]=arguments[c+1];a.embedAttrs[arguments[c]]=a.params[arguments[c]]=arguments[c+1];a.params[arguments[c]]=arguments[c+1]}for(var c=0;c<b.length;c++){var d=b[c].split("|");if(!a.params[d[0]]){a.objAttrs[d[0]]=d[1];a.embedAttrs[d[0]]=d[1];a.params[d[0]]=d[1]}}var e="<object ";for(var c in a.objAttrs)e+=c+'="'+a.objAttrs[c]+'" ';e+=">";for(var c in a.params)e+='<param name="'+c+'" value="'+a.params[c]+'" /> ';e+="<embed ";for(var c in a.embedAttrs)e+=c+'="'+a.embedAttrs[c]+'" ';e+=" ></embed></object>";doc.write(e)},TS3Settings:function(a){if(a==3){$("#ts3settings").css("display","")}else{$("#ts3settings").css("display","none")}}};$(document).ready(function(){DZCP.init()});$(window).load(function(){DZCP.resizeImages()})
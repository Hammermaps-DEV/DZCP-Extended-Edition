// GLOBAL VARS
  var doc = document, ie4 = document.all, opera = window.opera;
  var innerLayer, layer, x, y, doWheel = false, offsetX = 15, offsetY = 5;
  var tickerc = 0, mTimer = new Array(), tickerTo = new Array(), tickerSpeed = new Array();
  var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
  var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
  var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
  var json = jQuery.parseJSON(json_from_php);

// DZCP JAVASCRIPT LIBARY FOR JQUERY >= V1.9
  var DZCP = {
  //init
    init: function()
    {
        doc.body.id = 'dzcp-ee-engine-1.6';
        $('body').append('<div id="infoDiv"></div>');
        $('body').append('<div id="dialog"></div>');
        layer = $('#infoDiv')[0];
        doc.body.onmousemove = DZCP.trackMouse;

        // init jquery-ui
        DZCP.initJQueryUI();

        // init lightbox
        DZCP.initLightbox();

        // PreLoad checkPassword Pics
        images = new Array();
        images[0]="../inc/images/lvl0.jpg";
        images[1]="../inc/images/lvl1.jpg";
        images[2]="../inc/images/lvl2.jpg";
        images[3]="../inc/images/lvl3.jpg";
        images[4]="../inc/images/lvl4.jpg";
        images[5]="../inc/images/lvl5.jpg";

        images[6]="../inc/_templates_/version1.6/images/submit.jpg";
        images[6]="../inc/_templates_/version1.6/images/submit_hover.jpg";

        imageObj = new Image();
        var i; for(i=0; i<=3; i++)
        { imageObj.src=images[i]; }
    },

    checkPassword: function(passwd)
    {
        var level = new Array(); var intScore = 0;
        level[0] = '<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl1.jpg" width="200" height="18" /></td></tr></table>';
        level[1] = '<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl2.jpg" width="200" height="18" /></td></tr></table>';
        level[2] = '<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl3.jpg" width="200" height="18" /></td></tr></table>';
        level[3] = '<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl4.jpg" width="200" height="18" /></td></tr></table>';
        level[4] = '<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl5.jpg" width="200" height="18" /></td></tr></table>';
        level[5] = '<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl0.jpg" width="200" height="18" /></td></tr></table>';

        var base = 0;
        if (passwd.match(/[a-z]/)) { base = (base+26); }
        if (passwd.match(/[A-Z]/)) { base = (base+26); }
        if (passwd.match(/\d+/)) { base = (base+7); }
        if (passwd.match(/(\d.*\d.*\d)/)) { base = (base+5); }
        if (passwd.match(/[!",@#$%^&*?_~§$%&/\()=?`´°ß\][}³²;:üäöÖÜÄ]/)) { base = (base+40); }
        if (passwd.match(/([!,@#$%^&*?_~].*[!,@#$%^&*?_~])/)) { base = (base+23); }
        if (passwd.match(/[a-z]/) && passwd.match(/[A-Z]/)) { base = (base+26); }
        if (passwd.match(/\d/) && passwd.match(/\D/)) { base = (base+5); }
        if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/)) { base = (base+5); }
        if (passwd.match(/[a-z]/) && passwd.match(/[A-Z]/) && passwd.match(/\d/) && passwd.match(/[!,@#$%^&*?_~]/)) { base = (base+5); }

        var combos=Math.pow(base,passwd.length);
        if(combos == 1) { $('#Words').html(level[5]); }
        else if(combos > 1 && combos < 1000000) { $('#Words').html(level[0]); }
        else if (combos >= 1000000 && combos < 1000000000000) { $('#Words').html(level[1]); }
        else if (combos >= 1000000000000 && combos < 1000000000000000000) { $('#Words').html(level[2]); }
        else if (combos >= 1000000000000000000 && combos < 1000000000000000000000000) { $('#Words').html(level[3]); }
        else { $('#Words').html(level[4]); }
    },

  // init lightbox
    initLightbox: function() {
      $('a[rel^=lightbox]').lightBox({
          fixedNavigation:      true,
          overlayBgColor:       '#000',
             overlayOpacity:       0.8,
            imageLoading:         '../inc/images/lightbox/loading.gif',
             imageBtnClose:        '../inc/images/lightbox/close.gif',
            imageBtnPrev:         '../inc/images/lightbox/prevlabel.gif',
             imageBtnNext:         '../inc/images/lightbox/nextlabel.gif',
            containerResizeSpeed: 350,
            txtImage:             (lng == 'de' ? 'Bild' : 'Image'),
             txtOf:                (lng == 'de' ? 'von' : 'of'),
            maxHeight: screen.height * 0.9,
            maxWidth: screen.width * 0.9
      });
    },

  // init jquery-ui
    initJQueryUI: function() {
        $(".slidetabs").tabs(".images > div", { effect: 'fade', rotate: true }).slideshow({ autoplay: true, interval: 6000 });
        $(".tabs").tabs("> .switchs", { effect: 'fade' });
        $(".nav" ).button({ text: true });
        $( "#rerun" ).button().click(function() { return false; }).next().button({ text: false, icons: { primary: "ui-icon-triangle-1-s" } }).click(function() {
            var menu = $( this ).parent().next().show().position({
              my: "left top",
              at: "left bottom",
              of: this
            });

            $( document ).one( "click", function() { menu.hide(); });
            return false;
        }).parent().buttonset().next().hide().menu();
        $( document ).tooltip({ track: true });

        $("#dialog").dialog({ modal: true, bgiframe: true, width: 'auto', height: 'auto', autoOpen: false, title: 'Info' });
        $("a.confirm").click(function(link)
        {
            link.preventDefault();
            var default_message_for_dialog = ''
            var theHREF = $(this).attr("href");
            var theREL = $(this).attr("rel");
            var theMESSAGE = (theREL == undefined || theREL == '') ? default_message_for_dialog : theREL;
            var theICON = '<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>';

            var btns = {};
            btns[decodeURIComponent(escape(json.dialog_button_00))] = function() { window.location.href = theHREF; };
            btns[decodeURIComponent(escape(json.dialog_button_01))] = function() { $(this).dialog("close"); };

            // set windows content
            $('#dialog').html('<P>' + theICON + theMESSAGE + '</P>');
            $("#dialog").dialog('option', 'buttons', btns);
            $('#dialog').dialog('option', 'position', ['center', (document.body.clientHeight / 3)]);
            $("#dialog").dialog("open");
        });
    },

  // handle events
    addEvent : function(obj, evType, fn) {
        if(obj.addEventListener)
        {
            obj.addEventListener(evType, fn, false);
            return true;
        } else if (obj.attachEvent) {
            return obj.attachEvent('on' + evType, fn);
        } else return false;
    },

    // track mouse
    trackMouse: function(e) {
        innerLayer = $('#infoInnerLayer')[0];
        if(typeof(layer) == 'object')
        {
            var ie4 = doc.all;
            var ns6 = doc.getElementById && !doc.all;
            var mLeft = 5;
            var mTop = -15;

            x = (ns6) ? e.pageX-mLeft : window.event.clientX+doc.documentElement.scrollLeft - mLeft;
            y = (ns6) ? e.pageY-mTop  : window.event.clientY+doc.documentElement.scrollTop  - mTop;
            if(innerLayer)
            {
                var layerW = ((ie4) ? innerLayer.offsetWidth : innerLayer.clientWidth) - 3;
                var layerH = (ie4) ? innerLayer.offsetHeight : innerLayer.clientHeight;
            }
            else
            {
                var layerW = ((ie4) ? layer.clientWidth : layer.offsetWidth) - 3;
                var layerH = (ie4) ? layer.clientHeight : layer.offsetHeight;
            }

            var winW   = (ns6) ? (window.innerWidth) + window.pageXOffset - 12 : doc.documentElement.clientWidth + doc.documentElement.scrollLeft;
            var winH   = (ns6) ? (window.innerHeight) + window.pageYOffset : doc.documentElement.clientHeight + doc.documentElement.scrollTop;

            layer.style.left = ((x + offsetX + layerW >= winW - offsetX) ? x - (layerW + offsetX) : x + offsetX) + 'px';
            layer.style.top  = (y + offsetY) + 'px';
        }

        return true;
    },

    // handle popups
    popup: function(url, x, y) {
        x = parseInt(x); y = parseInt(y) + 50;
        popup = window.open(url, 'Popup', "width=1,height=1,location=0,scrollbars=0,resizable=1,status=0");
        popup.resizeTo(x, y);
        popup.moveTo((screen.width - x) / 2, (screen.height-y) / 2);
        popup.focus();
    },

    // init Ajax DynLoader Sides via Ajax
    initPageDynLoader: function(tag,url) {
        var request = $.ajax({ url: url, type: "GET", data: {}, cache:true, dataType: "html", contentType: "application/x-www-form-urlencoded; charset=iso-8859-1" });
        request.done(function(msg) { $('#' + tag).html( msg ).hide().fadeIn("normal"); });
    },

    // init Ajax DynLoader
    initDynLoader: function(tag,menu,options) {
        var request = $.ajax({ url: "../inc/ajax.php?loader=menu&mod=" + menu + options, type: "GET", data: {}, cache:true, dataType: "html", contentType: "application/x-www-form-urlencoded; charset=iso-8859-1" });
        request.done(function(msg) { $('#' + tag).html( msg ).hide().fadeIn("normal"); });
    },

    // submit shoutbox
    shoutSubmit: function() {
      $.post('../shout/index.php?ajax', $('#shoutForm').serialize(),function(req) {
        if(req) alert(req.replace(/  /g, ' '));
        $('#navShout').load('../inc/ajax.php?i=shoutbox');
        if(!req) $('#shouteintrag').prop('value', '');
      });

      return false;
    },

    // switch userlist
    switchuser: function() {
        var url = doc.formChange.changeme.options[doc.formChange.changeme.selectedIndex].value;
        window.location.href = url
    },

    // Templateswitch
    tempswitch: function() {
        var url = doc.form.tempswitch.options[doc.form.tempswitch.selectedIndex].value;
        if(url != 'lazy') DZCP.goTo(url);
    },

    // go to defined url
    goTo: function(url, n) {
        if(n == 1) window.open(url);
        else window.location.href = url
    },

    // limit text lenthn
    maxlength: function(field, countfield, max) {
        if(field.value.length > max) field.value = field.value.substring(0, max);
        else                         countfield.value = max - field.value.length;
    },

  // handle info layer
    showInfo: function(info, kats, text, img, width, height)
    {
        if(typeof(layer) == 'object')
        {
        var output = '';
        if(kats && text){
            var kat=kats.split(";");
            var texts=text.split(";");
            var katout = "";
            for(var i=0; i<kat.length; ++i) {
                  katout = katout + '<tr><td>'+kat[i]+'</td><td>'+texts[i]+'</td></tr>';
            }
            output = '<tr><td class="infoTop" colspan="2">'+info+'</td></tr>'+katout+'';
        }else if(kats && typeof(text)=="undefined"){
            output = '<tr><td class="infoTop" colspan="2">'+info+'</td></tr><tr><td>'+kats+'</td></tr>';
        }else{
            output = '<tr><td>'+info+'</td></tr>';
        }

        var userimg = "";
        if(img){
            userimg = '<tr><td colspan=2 align=center><img src="'+img+'" width="'+width+'" height="'+height+'" alt="" /></td></tr>';
        }else{
            userimg = '';
        }
        layer.innerHTML =
          '<div id="hDiv">' +
          '  <table class="hperc" cellspacing="0" style="height:100%">' +
          '    <tr>' +
          '      <td style="vertical-align:middle">' +
          '        <div id="infoInnerLayer">' +
          '          <table class="hperc" cellspacing="0">' +
          '              '+output+'' +
          '              '+userimg+'' +
          '          </table>' +
          '        </div>' +
          '      </td>' +
          '    </tr>' +
          '  </table>' +
          '</div>';

      //IE Fix
        if(ie4 && !opera)
        {
          layer.innerHTML += '<iframe id="ieFix" frameborder="0" width="' + $('#hDiv')[0].offsetWidth + '" height="' + $('#hDiv')[0].offsetHeight + '"></iframe>';
          layer.style.display = 'block';
        } else layer.style.display = 'block';
      }
    },

    hideInfo: function() {
      if(typeof(layer) == 'object')
      {
        layer.innerHTML = '';
        layer.style.display = 'none';
      }
    },

    // toggle object
    toggle: function(id) {
        if(id == 0) return;

        if($('#more' + id).css('display') == 'none')
        {
            $("#more" + id).fadeIn("normal");
            $('#img' + id).prop('src', '../inc/images/collapse.gif');
        }
        else
        {
            $("#more" + id).fadeOut("normal");
            $('#img' + id).prop('src', '../inc/images/expand.gif');
        }
    },

    // toggle with effect *TS3
    fadetoggle: function(id) {
        if(id == 0) return;

        $("#more_"+id).fadeToggle("slow", "swing");

        if($('#img_'+id).prop('alt') == "hidden")
            $('#img_'+id).prop({alt: 'normal', src: '../inc/images/toggle_normal.png'});
        else
            $('#img_'+id).prop({alt: 'hidden', src: '../inc/images/toggle_hidden.png'});
    },

    // resize images
    resizeImages: function() {
        for(var i=0;i<doc.images.length;i++)
        {
            var d = doc.images[i];

            if(d.className == 'content')
            {
                var imgW = d.width;
                var imgH = d.height;

                if(maxW != 0 && imgW > maxW)
                {
                    d.width = maxW;
                    d.height = Math.round(imgH * (maxW / imgW));

                    if(!DZCP.linkedImage(d))
                    {
                        var textLink = doc.createElement("span");
                        var popupLink = doc.createElement("a");

                        textLink.appendChild(doc.createElement("br"));
                        textLink.setAttribute('class', 'resized');
                        textLink.appendChild(doc.createTextNode('auto resized to '+d.width+'x'+d.height+' px'));

                        popupLink.setAttribute('href', d.src);
                        popupLink.setAttribute('rel', 'lightbox');
                        popupLink.appendChild(d.cloneNode(true));

                        d.parentNode.appendChild(textLink);
                        d.parentNode.replaceChild(popupLink, d);

                        DZCP.initLightbox();
                    }
                }
            }
        }
    },

    linkedImage: function(node) {
        do
        {
            node = node.parentNode;
            if (node.nodeName == 'A')
                return true;
        }
        while(node.nodeName != 'TD' && node.nodeName != 'BODY');
        return false;
    },

    // ajax calendar switch
    calSwitch: function(m, y) {
        $('#navKalender').load('../inc/ajax.php?i=kalender&month=' + m + '&year=' + y);
    },

    // ajax team switch
    teamSwitch: function(obj) {
        clearTimeout(mTimer[1]);
        $('#navTeam').load('../inc/ajax.php?i=teams&tID=' + obj, DZCP.initTicker('teams', 'h', 60));
    },

    // ajax vote
    ajaxVote: function(id) {
        DZCP.submitButton('contentSubmitVote');
        $.post('../votes/index.php?action=do&ajax=1&what=vote&id=' + id, $('#navAjaxVote').serialize(), function(req) { $('#navVote').html(req); });
    },

    // ajax forum vote
    ajaxFVote: function(id) {
        DZCP.submitButton('contentSubmitFVote');
        $.post('../votes/index.php?action=do&fajax=1&what=fvote&id=' + id, $('#navAjaxFVote').serialize(), function(req) { $('#navFVote').html(req); });
    },

    // ajax preview
    ajaxPreview: function(form) {
        var tag=doc.getElementsByTagName("textarea");
        for(var i=0;i<tag.length;i++)
        {
            var thisTag = tag[i].className;
            var thisID = tag[i].id;
            if(thisTag == "editorStyle" || thisTag == "editorStyleWord" || thisTag == "editorStyleNewsletter")
                $('#' + thisID).prop('value', tinyMCE.get(thisID).getBody().innerHTML); //Update attr() to prop()
        }

        $('#previewDIV').html('<div style="width:100%;text-align:center"><img src="../inc/images/ajax-loader-bar.gif" alt="" /></div>');

        var url = prevURL;
        var addpars = (form == 'cwForm') ? '&s1=' + $('#screen1').prop('value') + '&s2=' + $('#screen2').prop('value') + '&s3=' + $('#screen3').prop('value') + '&s4=' + $('#screen4').prop('value') : '';
        $.post(url, $('#' + form).serialize() + addpars, function(req) {
        $('#previewDIV').html(req);
      });
    },

    // forum search
    hideForumFirst: function() {
        $('#allkat').prop('checked', false);
    },

    hideForumAll: function() {
        for(var i = 0; i < doc.forms['search'].elements.length; i++)
        {
            var box = doc.forms['search'].elements[i];
            if(box.id.match(/k_/g))
                box.checked = false;
        }
    },

    // disable submit button
    submitButton: function(id) {
        submitID = (id) ? id : 'contentSubmit';
        $('#' + submitID).prop("disabled", true);
        $('#' + submitID).css('color', '#909090');
        $('#' + submitID).css('cursor', 'default');
        return true;
    },

    // Newticker
    initTicker: function(objID, to, ms) {
        // set settings
        tickerTo[tickerc] = (to == 'h' || to == 'v') ? to : 'v';
        tickerSpeed[tickerc] = (parseInt(ms) <= 10) ? 10 : parseInt(ms);

        // prepare  object
        var orgData = $('#' + objID).html();
        var newData  = '  <div id="scrollDiv' + tickerc +'" class="scrollDiv" style="position:relative;left:0;z-index:1">';
          newData += '    <table id="scrollTable' + tickerc +'" class="scrolltable"  cellpadding="0" cellspacing="0">';
          newData += '      <tr>';
          newData += '        <td onmouseover="clearTimeout(mTimer[' + tickerc +'])" onmouseout="DZCP.startTickerDiv(' + tickerc +')">';
          for(var i=0;i<10;i++) newData += orgData;
          newData += '        </td>';
          newData += '      </tr>';
          newData += '    </table>';
          newData += '  </div>';

        $('#' + objID).html(newData);

        // start ticker
        window.setTimeout("DZCP.startTickerDiv("+tickerc+");",1500);
        tickerc++;
    },

    startTickerDiv: function(subID) {
        tableObj        = $('#scrollTable' + subID)[0];
        obj             = tableObj.parentNode;
        objWidth        = (tickerTo[subID] == 'h') ? tableObj.offsetWidth : tableObj.offsetHeight;
        newWidth        = (Math.floor(objWidth/2)*2)+2;
        obj.style.width = newWidth;
        mTimer[subID] = setInterval("DZCP.moveDiv('"+obj.id+"', " + newWidth + ", " + subID + ");", tickerSpeed[subID]);
    },

    moveDiv: function(obj, width, subID) {
      var thisObj = $('#' + obj)[0];
      if(tickerTo[subID] == 'h') thisObj.style.left = (parseInt(thisObj.style.left) <= (0-(width/2)+2)) ? 0 : parseInt(thisObj.style.left)-1 + 'px';
      else thisObj.style.top = (thisObj.style.top == '' || (parseInt(thisObj.style.top)<(0-(width/2)+6))) ? 0 : parseInt(thisObj.style.top)-1 + 'px';
    },

    check_all: function(name, obj) {
        if(!obj || !obj.form) return false;
        var box = obj.form.elements[name];
        if(!box) return false;
        if(!box.length) box.checked = obj.checked; else
        for(var i = 0; i < box.length; i++)  box[i].checked = obj.checked;
    },

    sendFrom: function(do_obj,do_a,formId) {
        $('input[name='+ do_obj +']').val(do_a);
        $("#" + formId).submit();
    },
}

// load global events
$(document).ready(function() { DZCP.init(); });
$(window).load(function() { DZCP.resizeImages(); });

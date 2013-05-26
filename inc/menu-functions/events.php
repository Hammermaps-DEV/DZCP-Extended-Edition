<?php
function events()
{
    $menu_xml = get_menu_xml('events');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_eventbox'))
    {
        $eventbox = '';
        $qry = db("SELECT id,datum,title,event FROM ".dba::get('events')." WHERE datum > ".time()." ORDER BY datum LIMIT ".config('m_events')."");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $events = show(_next_event_link, array("datum" => date("d.m.",$get['datum']),"timestamp" => $get['datum'],"event" => $get['title']));
                $eventbox .= show("menu/event", array("events" => $events));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_eventbox',$eventbox,$menu_xml['config']['update']);
        }
    }
    else
        $eventbox = Cache::get('nav_eventbox');

    return empty($eventbox) ? '<center style="margin:2px 0">'._no_events.'</center>' : '<table class="navContent" cellspacing="0">'.$eventbox.'</table>';;
}
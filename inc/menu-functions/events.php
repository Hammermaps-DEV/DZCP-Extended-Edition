<?php
function events()
{
  global $db;

   $qry = db("SELECT id,datum,title,event FROM ".$db['events']."
              WHERE datum > ".time()."
              ORDER BY datum
              LIMIT ".config('m_events')."");

   $eventbox = '';
   while($get = _fetch($qry))
   {
     $events = show(_next_event_link, array("datum" => date("d.m.",$get['datum']),
                                            "timestamp" => $get['datum'],
                                            "event" => $get['title']));

       $eventbox .= show("menu/event", array("events" => $events));
   }


   return empty($eventbox) ? '<center style="margin:2px 0">'._no_events.'</center>' : '<table class="navContent" cellspacing="0">'.$eventbox.'</table>';;
}
?>

<?php

/**
 * @file
 * A single location to store configuration.
 */

// Twitter API
define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('ACCESS_TOKEN', '');
define('ACCESS_TOKEN_SECRET', '');

define('CALENDAR_ID', 'ggta5dj33bohbn65r6l1k4oo70%40group.calendar.google.com'); //production
define('EVENT_ID', 'pe3v3hr75etn6uqrikq7u6fce8'); //production

// Google API Key - https://cloud.google.com/console/project
define('CALENDAR_API_KEY', '');
define('NEXT_EVENT_URL', 'https://www.googleapis.com/calendar/v3/calendars/'.CALENDAR_ID.'/events?maxResults=1&orderBy=startTime&singleEvents=true&timeMin='.urlencode(date('c')).'&fields=items(description%2Clocation%2Cstart%2Csummary,originalStartTime)&iCalUID='.EVENT_ID.'%40google.com&key='.CALENDAR_API_KEY);

define('DEFAULT_LOCATION', 'Meisenfrei, Südstraße 25, 52064 Aachen, http://www.meisenfrei-aachen.de/');
define('DEFAULT_TIME_HHMM', '1930');

function get_next_event()
{
  $json = @json_decode(@file_get_contents(NEXT_EVENT_URL), true);

  if ($json === false || !is_array($json) || !array_key_exists('items', $json) || !is_array($json['items']) || count($json['items']) < 1) {
    return false;
  }

  return $json['items'][0];
}

function auto_link_text($text)
{
   $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
   return preg_replace_callback($pattern, function($matches) {
       $url       = array_shift($matches);
       $url_parts = parse_url($url);

       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
       $text = preg_replace("/^www./", "", $text);

       $last = -(strlen(strrchr($text, "/"))) + 1;
       if ($last < 0) {
           $text = substr($text, 0, $last) . "&hellip;";
       }
       $text = preg_replace("=/$=", '', $text);

       return sprintf('<a rel="nofollow" href="%s">%s</a>', $url, $text);
    }, $text);
}

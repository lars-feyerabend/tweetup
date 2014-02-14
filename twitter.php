<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth.php');
require_once('config.php');

setlocale(LC_ALL, 'de_DE.UTF-8');

/* check oauth access token */
if (!ACCESS_TOKEN || !ACCESS_TOKEN_SECRET) {
  die("Access token not configured.\n");
}

$e = get_next_event();

if (false === $e) {
  die('Could not retrieve event.');
}


/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

if (401 == $connection->lastStatusCode()) {
  die("Access token verification failed.\n");
}

echo "Sucessfully connected to Twitter...\n";


$next = strtotime($e['start']['dateTime']);
$next_orig = isset($e['originalStartTime']) ? strtotime($e['originalStartTime']['dateTime']) : false;

$location_changed = ($e['location'] != DEFAULT_LOCATION);

$time_changed = ($next_orig !== false && date('Hi', $next) != DEFAULT_TIME_HHMM);
$day_changed = ($next_orig !== false && date('Ymd', $next) != date('Ymd', $next_orig));

$dt = new DateTime($e['start']['dateTime']);
$now = new DateTime();
$diff = $dt->diff($now);

$daydiff = $diff->format('%a');

if ($daydiff != 14 && $daydiff != 7 && $daydiff != 1) {
  //exit('No critical date, nothing to do. Exiting.');
}

foreach (array(14,7,1) as $daydiff) {
  foreach (range(0,7) as $changes) {
    $t = make_tweet($daydiff, $changes, $next);

    echo str_pad(strlen($t), 3, ' ' ,STR_PAD_LEFT).(strlen($t) > 140 ? '!!! ' : '    ').$t."\n";
  }
}

//   URLURLURLURLURLURLUR

// (Di 11.09. 19:30)
// URLURLURLURLURLURLUR — Achtung, Ort/Termin abweichend!

// Zur Erinnerung: #tweetupAC! Heute! Um XX:HH Uhr! Neue Gesichter wie im
// mer gern willkommen. URLURLURLURLURLURLUR
//  — Achtung, abweichender Ort! (29)
//  — Achtung, abweichende Uhrzeit! (32)
//  — Achtung, abweichender Termin! (32)
//  — Achtung, Ort/Termin abweichend! (34)
//
// 1234567890123456789012345678901234567890123456789012345678901234567890
//0         1         2         3         4         5         6         7

function make_tweet($daydiff, $changes, $date) {
  // O U T
  $change_msg = array(
    0 => '', // 000
    1 => 'Achtung, abweichender Termin!', // 001
    2 => 'Achtung, abweichende Uhrzeit!', // 010
    3 => 'Achtung, abweichender Termin!', // 011
    4 => 'Achtung, abweichender Ort!', // 100
    5 => 'Achtung, Ort und Termin abweichend!', // 101
    6 => 'Achtung, Ort und Termin abweichend!', // 110
    7 => 'Achtung, Ort und Termin abweichend!', // 111
  );

  $sd = strftime('%a', $date).date(' j.n. H:i ', $date).'Uhr';

  $tweet = "";

  switch ($daydiff) {
    case 14:
      if ($changes) {
        $tweet .= "In zwei Wochen ist es wieder soweit: #tweetupAC ($sd) — {$change_msg[$changes]}";
      } else {
        $tweet .= "In zwei Wochen ist es wieder soweit: #tweetupAC, das monatliche Twitter-Treffen! ($sd)";
      }
      break;
    case 7:
      if ($changes) {
        $tweet .= "Zur Erinnerung: Heute in einer Woche ist #tweetupAC ($sd) — {$change_msg[$changes]}";
      } else {
        $tweet .= "Zur Erinnerung: Heute in einer Woche ist #tweetupAC, das monatliche Twitter-Treffen! ($sd)";
      }
      break;
    case 1:
      $tweet .= sprintf('Zur Erinnerung: #tweetupAC! Heute! Um %s Uhr', date('H:i', $date));
      if ($changes & 2) {
        $tweet .= (' (abweichende Uhrzeit)!');
        if ($changes & 4) {
          $tweet .= " Achtung, diesmal andere Location!";
          break;
        }
      } elseif ($changes & 4) {
        $tweet .= "! Achtung, diesmal andere Location!";
        break;
      } else {
        $tweet .= '! Und wie immer sind auch neue Gesichter gern gesehen!';
      }
      break;
  }

//  $tweet .= ' http://www.wannistdasnächstetweetupac.de/';
  $tweet .= ' http://t.co/12345678';

  return $tweet;
}


/* Some example calls */
//$connection->get('users/show', array('screen_name' => 'abraham'));
//$connection->post('statuses/update', array('status' => date(DATE_RFC822)));
//$connection->post('statuses/destroy', array('id' => 5437877770));
//$connection->post('friendships/create', array('id' => 9436992));
//$connection->post('friendships/destroy', array('id' => 9436992));


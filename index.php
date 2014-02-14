<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title>Wann ist das nächste tweetupAC?</title>
<script type="text/javascript" src="//use.typekit.net/kyj7vjn.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
    <h1>Wann ist das nächste tweetupAC?</h1>
    <?php
      setlocale(LC_ALL, 'de_DE.UTF-8');
      require('config.php');
      $e = get_next_event();
      if ($e !== false):
      $next = strtotime($e['start']['dateTime']);
      $location_changed = ($e['location'] != DEFAULT_LOCATION);
      $time_changed = (date('Hi', $next) != DEFAULT_TIME_HHMM);
      $day_changed = (date('Ymd', $next) != date('Ymd', strtotime($e['originalStartTime']['dateTime'])));
    ?>
    <div id="next">
      <?php if ($location_changed || $time_changed || $day_changed): ?>
      <p class="changed">Diesmal abweichend:</p>
      <?php endif; ?>
      <p class="date">
        <?php echo $day_changed ? '<span>'.strftime('%A, %e. %B %Y', $next).'</span>' : strftime('%A, %e. %B %Y', $next); ?>,
        <?php echo $time_changed ? '<span>'.strftime('%H:%M Uhr', $next).'</span>' : strftime('%H:%M Uhr', $next); ?>
      </p>
      <p class="location"><strong>Wo?</strong>
        <?php if ($location_changed) echo '<span>'; ?>
        <?php echo auto_link_text($e['location']); ?>
        <?php if ($location_changed) echo '</span>'; ?>
        (<a href="http://maps.google.de/?q=<?php echo urlencode($e['location']); ?>" target="_blank">Karte</a>)
      </p>

      <p><a id="all-trigger" href="#all" onclick="$('#next').slideUp(150);$('#all').slideDown(150);return false;">Alle Termine</a></p>
    </div>
    <?php endif; ?>
    <div id="all"<?php if ($e !== false):?> style="display: none;"<?php endif; ?>>
      <iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=200&amp;wkst=2&amp;hl=de&amp;bgcolor=%23FFFFFF&amp;src=ggta5dj33bohbn65r6l1k4oo70%40group.calendar.google.com&amp;color=%232F6309&amp;ctz=Europe%2FBerlin" style=" border-width:0 " width="650" height="200" frameborder="0" scrolling="no"></iframe>

      <div>
        <a href="https://www.google.com/calendar/feeds/ggta5dj33bohbn65r6l1k4oo70%40group.calendar.google.com/public/basic"><img src="https://www.google.com/calendar/images/xml.gif" alt="XML"></a>
        <a href="webcal://www.google.com/calendar/ical/ggta5dj33bohbn65r6l1k4oo70%40group.calendar.google.com/public/basic.ics"><img src="https://www.google.com/calendar/images/ical.gif" alt="iCal"></a>
      </div>

      <div id="note">
        (Regelmäßig am zweiten Dienstag im Monat.)
      </div>
    </div>

    <div class="buttons">
      <a href="https://twitter.com/tweetupAC" class="twitter-follow-button" data-show-count="false" data-lang="de" data-size="large" data-dnt="true">@tweetupAC folgen</a>
      <a href="https://twitter.com/intent/tweet?button_hashtag=tweetupAC" class="twitter-hashtag-button" data-lang="de" data-size="large" data-related="tweetupAC" data-dnt="true">#tweetupAC twittern</a>
    </div>

    <footer>
      <a href="http://lars-feyerabend.de/" title="Verantwortlich für diese Webseite: Lars Feyerabend">&pi;</a></p>
      </div>
    </footer>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</body>
</html>

<?php

function kgel_send_login_event () {
  if ( ! isset($_SESSION['sent_ga_login']) ) {
    $html = "<script>gtag('event', 'login');</script>";
    $_SESSION['sent_ga_login'] = true;
  }
  return $html;
}
add_shortcode('kgel_send_login_log', 'kgel_send_login_event');

function kgel_send_vimeo_event () {

  $code = <<< EOD
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var vimeoIframes = document.querySelectorAll('iframe[src*="vimeo"]');
  //console.log(vimeoIframes);
  vimeoIframes.forEach(function (vimeoIframe) {
    //console.log(vimeoIframe);
    var src = vimeoIframe.getAttribute('src');
    var searchWords = 'video/';
    var videoNumber = src.slice(src.lastIndexOf(searchWords) + searchWords.length);
    var videoId = 'id-' + videoNumber;
    vimeoIframe.setAttribute('id', videoId);
    var player = new Vimeo.Player(videoId);

    var videoTitle = null;
    player.getVideoTitle().then(function(title) {
      videoTitle = title;
    });
    var send_gtag = function (eventName, eventObj, param = {}) {
      var _param = {
        'video_provider': 'Vimeo',
          'video_id': videoNumber,
          'video_title': videoTitle,
          'video_url': 'https://vimeo.com/' + videoNumber,
          'visible': 1,
        }

        if ( eventObj.percent !== undefined ) {
          _param.video_duration = eventObj.duration;
        }
        if ( eventObj.percent !== undefined ) {
          _param.video_current_time = eventObj.seconds;
        }
        if ( param.video_percent !== undefined ) {
          _param.video_percent = param.video_percent;
        } else if ( eventObj.percent !== undefined ) {
          var percent = eventObj.percent;
          percent = String(percent.toFixed(2));
          percent = percent * 100;
          _param.video_percent = percent;
        }
        //console.log(['event', eventName, _param]);
        gtag('event', eventName, _param);
      };

      player.on('loaded', function (e) {
        send_gtag('video_loaded', e);
      });
      player.on('play', function (e) {
        if ( e.percent === 0 ) {
          // This event is when user start Video the first time in the page.
          // If user play again after pausing, It's "video_play".
          send_gtag('video_start', e);
        }
      });
      player.on('playing', function (e) {
        send_gtag('video_play', e);
      });
      player.on('pause', function (e) {
        send_gtag('video_pause', e);
      });
      player.on('seeking', function (e) {
        send_gtag('video_seeking', e);
      });
      player.on('seeked', function (e) {
        send_gtag('video_seeked', e);
      });
      player.on('ended', function (e) {
        send_gtag('video_complete', e);
      });

      var timeUpdateFlag = {}
      player.on('timeupdate', function(e) {
        if ( e.percent > 0.95 ) {
          if ( ! timeUpdateFlag.t95 ) {
            timeUpdateFlag.t95 = true;
            send_gtag('video_progress' , e, { 'video_percent': 95 });
          }
        } else if ( e.percent > 0.90 ) {
          if ( ! timeUpdateFlag.t90 ) {
            timeUpdateFlag.t90 = true;
            send_gtag('video_progress', e, { 'video_percent': 90 });
          }
        } else if ( e.percent > 0.85 ) {
          if ( ! timeUpdateFlag.t85 ) {
            timeUpdateFlag.t85 = true;
            send_gtag('video_progress', e, { 'video_percent': 85 });
          }
        } else if ( e.percent > 0.80 ) {
          if ( ! timeUpdateFlag.t80 ) {
            timeUpdateFlag.t80 = true;
            send_gtag('video_progress', e, { 'video_percent': 80 });
          }
        } else if ( e.percent > 0.75 ) {
          if ( ! timeUpdateFlag.t75 ) {
            timeUpdateFlag.t75 = true;
            send_gtag('video_progress', e, { 'video_percent': 75 });
          }
        } else if ( e.percent > 0.5 ) {
          if ( ! timeUpdateFlag.t50 ) {
            timeUpdateFlag.t50 = true;
            send_gtag('video_progress', e, { 'video_percent': 50 });
          }
        } else if ( e.percent > 0.25 ) {
          if ( ! timeUpdateFlag.t25 ) {
            timeUpdateFlag.t25 = true;
            send_gtag('video_progress', e, { 'video_percent': 25 });
          }
        } else if ( e.percent > 0.1 ) {
          if ( ! timeUpdateFlag.t10 ) {
            timeUpdateFlag.t10 = true;
            send_gtag('video_progress', e, { 'video_percent': 10 });
          }
        }
      });
    });
  });
</script>
EOD;

  return $code;
}
add_shortcode('kgel_send_vimeo_log', 'kgel_send_vimeo_event');
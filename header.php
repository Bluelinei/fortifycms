<!DOCTYPE html>
<?php require 'framework/securesession.php'; ?>
<?php require 'framework/requireSSL.php'; ?>
<html>
  <head>
    <title>Fortify</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

    <link href="css/style.css?v=<?php echo sha1_file("css/style.css");?>" rel="stylesheet" type="text/css" />
    <script src="https://use.fontawesome.com/47f9a4e330.js"></script>

    <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="./framework/toolkit.js?v=<?php echo sha1_file("framework/toolkit.js");?>"></script>
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <script src='./framework/notify.js?v=<?php echo sha1_file("framework/notify.js");?>'></script>

  </head>
  <body>
    <?php require 'evidence-overlay.php'; ?>
    <?php require 'search-overlay.php'; ?>

<!--START quick-notify-->
  <div class="quick-notify hidden">
    <div class="left quick-notify-close point-cursor"><i class="fa fa-times-circle vertical-middle" aria-hidden="true"></i></div>
    <div class="left quick-notify-content no-event"><p class="vertical-middle"></p></div>
  </div>
<!--END quick-notify-->

<?php include 'notification-bar.php'; ?>

<!--START Main Content Area wrapper (side-bar + content pane)-->
<div class="content-wrapper">
<!--START Nav/Logo side-bar-->
    <div class="nav-side-bar hidden">

<!--LOGO BOX is 30% 0f the nav-side-bar -->
          <div class="logo-box no-select">
            <div id="expand-nav-bar" class="expand-container">
              <div class="expand-button">
                <i class="fa fa-bars" aria-hidden="true"></i>
              </div>
              <div class="clear"></div>
            </div>
            <div class="fortify-header-box small">
              <div class="fortify-header-logo">
                <img src="img/logo.png" class="logo"/>
              </div>
            </div>
          </div>

<!-- CURRENT USER data box -->
          <div class="current-user-data-wrapper">
            <div class="current-user-data nav-side-bar-content">
                <!--<ul>
                  <li><div class="inner">Focused Case<br>17-000001234</div></li>
                  <li><div class="inner">Start Time</div></li>
                  <li><div class="inner">PreLink ON/Off</div></li>
                </ul>-->
            </div>
            <div class="clear"></div>
          </div>


<!-- LINK BOX-->

          <div class="nav-button-box no-select">
            <div class="nav-buttons left">
                <ul>
                  <li id="nav-evidence">
                    <div class="vertical-middle text-center">
                      <i class="fa fa-folder-open" aria-hidden="true"></i>
                      <label>Evidence</label>
                    </div>
                  </li>
                  <li id="">
                    <div class="vertical-middle text-center">
                      <i class="fa fa-reply-all" aria-hidden="true"></i>
                      <label>Recent</label>
                    </div>
                  </li>
                  <li id="to-casebuilder">
                    <div class="vertical-middle text-center">
                      <i class="fa fa-pencil" aria-hidden="true"></i>
                      <label>Builder</label>
                    </div>
                  </li>
                  <li id="logout">
                    <div class="vertical-middle text-center">
                      <i class="fa fa-power-off" aria-hidden="true"></i>
                      <label>Log-off</label>
                    </div>
                  </li>
                  <li>
                    <div class="vertical-middle text-center">
                      <i class="fa fa-paper-plane" aria-hidden="true"></i>
                      <label>Notification</label>
                    </div>
                  </li>
                  <li id="search-button">
                    <div class="vertical-middle text-center">
                      <i class="fa fa-search" aria-hidden="true"></i>
                      <label>Search</label>
                    </div>
                  </li>
                </ul>
            </div>
          </div><!--END LINK BOX-->
    </div><!--END Nav/Logo side-bar-->

<script>
  var idle_timer;
  var idleTime = 0;

  function toggleNotifications()
  {
    if($('.notification-button-container').hasClass('hidden'))
    {
      $('#note-arrow').removeClass('fa-chevron-circle-up');
      $('#note-arrow').addClass('fa-chevron-circle-down');
      $('.notification-button-container').removeClass('hidden');
    }
    else
    {
      $('#note-arrow').removeClass('fa-chevron-circle-down');
      $('#note-arrow').addClass('fa-chevron-circle-up');
      $('.notification-button-container').addClass('hidden');
    }
  }

  $('#expand-nav-bar').on('click', function(e){
    if($('.nav-side-bar').hasClass('hidden'))
    {
      $('.nav-side-bar').removeClass('hidden');
      $('.fortify-header-box').removeClass('small');
    }
    else
    {
      $('.nav-side-bar').addClass('hidden');
      $('.fortify-header-box').addClass('small');
    }
  });

  $('#to-casebuilder').on('click', function(e){
    Tool.href('casebuilder.php');
  });

  $('#logout').on('click', function(e) {
    logout();
  });

  $('#search-button').on('click', function(){
    if($('.search-box').hasClass('hidden'))
    {
      $('.search-box').removeClass('hidden');
      if($('#media-browser').hasClass('show')) $('#media-browser').removeClass('show');
    }
    else $('.search-box').addClass('hidden');
  });
  $('.search-close').on('click', function(e){
    if(e.target!=this) return;
    $('.search-box').addClass('hidden');
  });
  $('.note-header').on('click', toggleNotifications);
  $('#nav-casebuilder').on('click', function() {
    Tool.href('casebuilder.php');
  });
  /*
  $(document).ready(function() {
    idle_timer = setInterval(timerIncrement, 60000);
    $(this).mousemove(function() {idleTime = 0;});
    $(this).keypress(function() {idleTime = 0;});
  });
  function timerIncrement()
  {
    idleTime++;
    if(idleTime>=10) logout();
  }
  */
</script>

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
<!--  <div class="content-wrapper"> -->
<!--START Nav/Logo side-bar-->
    <div class="nav-side-bar">

      <img src="img/logo.png" class="logo"/>

      <nav>
        <ul class="no-select">
          <li id="search-button"><div>Search<i class="fa fa-search fa-lg right ten-margin-right"></i></div></li>
          <li><div>Recent<i class="fa fa-reply fa-lg right ten-margin-right"></i></div></li>
          <li id="nav-casebuilder"><div>Builder<i class="fa fa-pencil fa-lg right ten-margin-right"></i></div></li>
          <li id="nav-evidence"><div>Evidence<i class="fa fa-folder-open fa-lg right ten-margin-right"></i></div></li>
        </ul>
      </nav>

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

  $('#search-button').on('click', function(){$('.search-box').removeClass('hidden');});
  $('.search-close').on('click', function(e){
    if(e.target!=this) return;
    $('.search-box').addClass('hidden');
  });
  $('.note-header').on('click', toggleNotifications);
  $('#nav-casebuilder').on('click', clickHandler(href, 'casebuilder.php'))
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

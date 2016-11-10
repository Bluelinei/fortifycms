<!DOCTYPE html>
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

    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <script src="https://use.fontawesome.com/47f9a4e330.js"></script>

    <script src="./framework/jquery3.1.1.js"></script>
    <script src="./framework/toolkit.js"></script>
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <!--<script src='./framework/notify.js'></script>-->

  </head>
  <body>
    <?php
      if(!isset($_SESSION)) session_start();
      if(!isset($_SESSION['user']))
      {
        header("Location: login.php");
        die();
      }
    ?>
    <?php require 'evidence-overlay.php'; ?>

<!--START quick-notify-->
  <div class="quick-notify case-fortified hidden">
    <div class="left quick-notify-close point-cursor"><i class="fa fa-times-circle vertical-middle" aria-hidden="true"></i></div>
    <div class="left quick-notify-content" style="pointer-events:none;"><p class="vertical-middle">Your files have been uploaded</p></div>
  </div>
<!--END quick-notify-->

<?php include 'search.php'; ?>
<?php include 'notification-bar.php'; ?>

<!--START Main Content Area wrapper (side-bar + content pane)-->
<!--  <div class="content-wrapper"> -->
<!--START Nav/Logo side-bar-->
    <div class="nav-side-bar">

      <img src="img/logo.png" class="logo"/>

      <nav>
        <ul>
          <li id="search-button"><div>Search</div></li>
          <li><div>Recent</div></li>
          <li id="nav-casebuilder"><div>Builder</div></li>
          <li id="nav-evidence"><div>Evidence</div></li>
        </ul>
      </nav>

    </div><!--END Nav/Logo side-bar-->

<script>
  var notification_timer;
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

  function notify(msg)
  {
    clearTimeout(notification_timer);
    $('.quick-notify-content>p').html(msg);
    $('.quick-notify').removeClass('hidden');
    notification_timer = setTimeout(function(){
      $('.quick-notify').addClass('hidden');
    }, 2000);
  }
  $(document).on('click', '.quick-notify-close', function() {
    $('.quick-notify').addClass('hidden');
  });
  $(document).on('mouseenter', '.quick-notify', function (){
    clearTimeout(notification_timer);
  });
  $(document).on('mouseleave', '.quick-notify', function (){
    notification_timer = setTimeout(function(){
      $('.quick-notify').addClass('hidden');
    }, 2000);
  });
  $('#search-button').on('click', function(){$('.search-box').removeClass('hidden');});
  $('.search-close').on('click', function(e){
    if(e.target!=this) return;
    $('.search-box').addClass('hidden');
  });
  $('.note-header').on('click', toggleNotifications);
  $(document).ready(function() {
    idle_timer = setInterval(timerIncrement, 60000);
    $(this).mousemove(function() {idleTime = 0;});
    $(this).keypress(function() {idleTime = 0;});
  });
  $('#nav-casebuilder').on('click', clickHandler(href, 'casebuilder.php'));
  function timerIncrement()
  {
    idleTime++;
    if(idleTime>=10) logout();
  }
</script>
<!DOCTYPE html>
<html>
  <?php require 'framework/requireSSL.php'; ?>
  <?php
    if(!isset($_SESSION)) session_start();
    if(!isset($_SESSION['user']))
    {
      header("Location: login.php");
      die();
    }
  ?>
  <head>
    <meta charset="utf-8">
    <title>Login Authentication</title>
    <link href="css/style.css" rel="stylesheet"/>
    <link href="img/favicon.png" type="image/png" rel="icon"/>
    <script src="./framework/jquery3.1.1.js"></script>
    <script src="./framework/toolkit.js?v=<?php echo sha1_file("framework/toolkit.js");?>"></script>
  </head>
  <body style= "background: linear-gradient(#bfbfbf, #fff);">
  
      <div class="vertical-middle login-page" style="width:500px;margin: 0px auto;">
        <img src="img/Fortify-Logo-Brushed.png" style="width: 100%; pointer-events:none;" />
        <div style="text-align: center; margin: 30px;">
          <p>For your security, an authentication code has been sent to the authorized device attached to this account.</p>
          <p>When you receive your code, please enter it below.</p>
          <p>(Code expires after 10 minutes)</p>
        </div>
        <input id="2fa" type="text" placeholder="Authentication Code" autocomplete="off" style="width:100%;"/>
        <div class="clear"></div>
        <input id="submit" type="submit" value="Verify" />
        <p id="resend" class="link" style="text-align: center; margin-top: 20px;">Renew and Resend Code</p>
        <div class="login-message hidden">
          
        </div>
      </div>
      <script>
        var USER;
        ajax('framework/getsession.php', null, function(response) {
          USER = JSON.parse(response);
        });

        $('#2fa').on('input', function() {
          $('#2fa').val($('#2fa').val().toUpperCase());
          $('.login-message').addClass('hidden');
        });
        $('#submit').on('click', function() {
          var f = new FormData();
          f.append('code', $('#2fa').val());
          ajax('framework/2fa.php', f, function(response) {
            log(response);
            if(response) href('casebuilder.php');
            else
            {
              $('.login-message').html('Invalid Authentication Code');
              $('.login-message').removeClass('hidden');
            }
          });
        });
        $('#resend').on('click', function() {
          log('framework/api/renewcode.php?user='+USER.user);
          ajax('framework/api/renewcode.php?user='+USER.user, null, function(response){
            $('.login-message').html('Authentication Code Resend!');
            $('.login-message').removeClass('hidden');
            setTimeout(function(){
              $('.login-message').addClass('hidden');
            }, 3000);
          });
        });
        $(document).on('load', function() {
          $('#2fa').focus();
        });
        $(document).on('keydown', function(e) {
          //log(e.keyCode);
          if(e.keyCode==13||e.which==13) {$('#submit').trigger('click');}
        });
      </script>
  </body>
</html>

<!DOCTYPE html>
<?php //require 'framework/requireSSL.php'; ?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Please Login</title>
    <link href="css/style.css" rel="stylesheet"/>
    <link href="img/favicon.png" type="image/png" rel="icon"/>
    <script src="./framework/jquery3.1.1.js"></script>
    <script src="./framework/toolkit.js?v=<?php echo sha1_file("framework/toolkit.js");?>"></script>
  </head>
  <body style= "background: linear-gradient(#bfbfbf, #fff);">

      <div class="vertical-middle login-page" style="width:500px;margin: 0px auto;">
        <img src="img/Fortify-Logo-Brushed.png" style="width: 100%; pointer-events:none;" />
        <input id="agencyid" type="text" placeholder="Agency ID" autocomplete="on" />
        <input id="user" type="text" placeholder="User Name" autocomplete="off" />
        <input id="pass" type="password" placeholder="Password" autocomplete="off" />
        <div class="clear"></div>
        <input id="login" type="submit" value="Login" style="width:50%; left:50%; transform: translateX(-50%)" />
        <div class="login-message hidden">

        </div>
      </div>
      <script>
        var loginnote;
        function loginNotify(msg)
        {
          clearTimeout(loginnote);
          $('.login-message').html(msg);
          $('.login-message').removeClass('hidden');
          loginnote = setTimeout(function(){
            $('.login-message').addClass('hidden');
          }, 2000);
        }
        function doLogin()
        {
          if($('#user').val()==''||$('#pass').val()=='')
          {
            loginNotify('Please enter login credentials.');
            return;
          }
          login($('#user').val(), $('#pass').val(), $('#agencyid').val());
        }
        $(document).on('click', '#login', doLogin);
        $(document).on('keypress', function(event){
          if(event.keyCode==13||event.which==13) doLogin();
        });
      </script>
  </body>
</html>

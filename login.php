<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Please Login</title>
    <link href="css/style.css" rel="stylesheet"/>
    <script src="./framework/jquery3.1.1.js"></script>
    <script src="./framework/toolkit.js"></script>
  </head>
  <body style= "background: linear-gradient(#bfbfbf, #fff);">

      <div class="vertical-middle login-page" style="width:500px;margin: 0px auto;">
        <img src="img/Fortify-Logo-Brushed.png" style="width: 100%; pointer-events:none;" />
        <input id="user" type="text" placeholder="User Name" class="left"/>
        <input id="pass" type="password" placeholder="Password" class="right"/>
        <div class="clear"></div>
        <input id="login" type="submit" value="Login" />
        <div class="login-message hidden">
          
        </div>
      </div>
      <script>
        var login;
        function loginNotify(msg)
        {
          clearTimeout(login);
          $('.login-message').html(msg);
          $('.login-message').removeClass('hidden');
          login = setTimeout(function(){
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
          href('index.php?user='+$('#user').val());
        }
        $(document).on('click', '#login', doLogin);
      </script>
  </body>
</html>

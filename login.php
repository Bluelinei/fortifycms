<!DOCTYPE html>
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
        <input id="user" type="text" placeholder="User Name" autocomplete="off" class="left"/>
        <input id="pass" type="password" placeholder="Password" autocomplete="off" class="right"/>
        <div class="clear"></div>
        <input id="login" type="submit" value="Login" />
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
          if($('#user').val()==''&&$('#pass').val()=='') {login('dev', 'devpass'); return;} //DO NOT FORGET TO DELETE THIS LINE WHEN FINISHING OUT THE CODE!!!
          else if($('#user').val()==''||$('#pass').val()=='')
          {
            loginNotify('Please enter login credentials.');
            return;
          }
          login($('#user').val(), $('#pass').val());
        }
        $(document).on('click', '#login', doLogin);
        $(document).on('keypress', function(event){
          if(event.keyCode==13||event.which==13) doLogin();
        });
      </script>
  </body>
</html>

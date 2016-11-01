<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Please Login</title>
    <link href="css/style.css" rel="stylesheet"/>
  </head>
  <body style= "background: linear-gradient(#bfbfbf, #fff);">

      <div class="vertical-middle login-page" style="width:500px;margin: 0px auto;">
        <img src="img/Fortify-Logo-Brushed.png" style="width: 100%;" />
        <input type="text" placeholder="User Name" class="left"/>
        <input type="text" placeholder="Password" class="right"/>
        <div class="clear"></div>
        <input id="login" type="submit" value="Login">
        <div class="msg">
          Please Login First
        </div>
      </div>
      <script>
        function doLogin()
        {
          
        }
        $(document).on('click', '#login', doLogin);
      </script>
  </body>
</html>

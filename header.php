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

  </head>
  <body>
    <?php require 'evidence-overlay.php'; ?>

<!--START quick-notify-->
  <div class="quick-notify upload-complete">
    <div class="left quick-notify-close"><i class="fa fa-times-circle vertical-middle" aria-hidden="true"></i>
</div>
    <div class="left quick-notify-content"><p class="vertical-middle">Your files have been uploaded</p></div>
  </div>
<!--END quick-notify-->

<!--START search box-->

  <div class="search-box">
    <div class="exit-overlay"></div>
    <div class="vertical-middle">
      <input type="text" placeholder="Enter Search Criteria" />
      <input type="submit" value="Search Database" />
      <div class="clear"></div>
    </div>
  </div>

<!--END search box-->

<!--START Main Content Area wrapper (side-bar + content pane)-->
<!--  <div class="content-wrapper"> -->
<!--START Nav/Logo side-bar-->
    <div class="nav-side-bar">

      <img src="img/logo.png" class="logo"/>

      <nav>
        <ul>
          <li style="display: none;"><div>Upload</div></li>
          <li><div id="video-player">Search</div></li>
          <li><div>Recent</div></li>
          <li><div id="nav-evidence">Evidence</div></li>
        </ul>
      </nav>

    </div><!--END Nav/Logo side-bar-->

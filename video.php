<?php require 'header.php'; ?>

<script>
tinymce.init({
  selector: '#commentBox'
});
</script>

<?php if(!isset($_GET['view'])) header('Location: http://localhost/index.php'); ?>

  <script src="framework/videoplayer/video.js"></script>

<!--START Content-pane-->
  <div style="display:none;"><div id="getViewFromURL"><?php echo $_GET['view']?></div></div>
  <div class="content-pane" id="page-body" style="overflow: auto;">
<!--START video container main-->
    <div class="video-main-container">
<!--START video container inner-->
      <div class="video-container-inner">

<!--START video box-->
        <div class="video-overlay-area">
        <div style="margin-bottom: -2px;">
          <video id="video" style="width: 100%;" class="point-cursor" autoplay>
          </video>

<!--START video control container-->
            <div class="video-control-container">

              <div class="ten-per-wide hundred-tall left">
<!--START play button-->
                <div class="vertical-middle">
                  <img id="video-toggle" src="img/pause-button.png" class="play-button point-cursor"/>
                </div>
              </div><!--END play button-->

<!--START time code box left-->
              <div class="fifteen-per-wide hundred-tall left">
                <div class="vertical-middle">
                  <div id="video-curtime" class="time-code-box push-right">00:00:00</div>
                </div>
              </div><!--END time code box left-->

<!--START progress bar-->
                <div class="fifty-per-wide hundred-tall left">
                  <div class="one-wide hundred-tall ten-padding">
                    <div class="progress-bar-base">
                      <div class="progress-bar"></div>
                      <div class="play-head"></div>
                    </div>
                  </div>
                </div><!--END progress bar-->

<!--START time code box right-->
                <div class="fifteen-per-wide hundred-tall left">
                  <div class="vertical-middle">
                    <div id="video-duration" class="time-code-box">00:00:00</div>
                  </div>
                </div><!--END time code box-right-->

<!--START advanced control buttons-->
                <div class="ten-per-wide hundred-tall left">
                  <div class="vertical-middle">
<!--GPS button to display only if GPS data is available-->
                    <img src="img/gps-icon.png" class="point-cursor video-player-button"/>
<!--Gear button to reveal the additional tool bar-->
                    <img id="advanced-menu-button" src="img/gear-button.png" class="point-cursor video-player-button five-margin-left"/>
                    <div class="clear"></div>
                  </div>
                </div><!--END advanced control buttons-->

            </div><!--END video control container-->
        </div><!--END video box-->

<!--START advanced tool bar (hidden until revealed by gear button)-->
        <div class="advanced-toolbar-container hidden no-select">
          <div class="advanced-toolbar-inner">
            <div class="advanced-tool-spacer">
              <div class="advanced-tool text-bold">Trim Tool</div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool-img"><img src="img/speaker-l.png" /></div>
              <div class="advanced-tool-img"><img src="img/speaker-r.png" /></div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool-img"><img src="img/zoom-plus.png" /></div>
              <div class="advanced-tool-img"><img src="img/zoom-minus.png" /></div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool-img video-screenshot"><img src="img/camera-icon.png" /></div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool text-bold playback-speed 8-slower">.8</div>
              <div class="advanced-tool text-bold playback-speed 4-slower">.4</div>
              <div class="advanced-tool text-bold playback-speed 2-slower">.2</div>
              <div class="advanced-tool text-bold playback-speed normal-speed">Default</div>
              <div class="advanced-tool text-bold playback-speed 2-faster">x2</div>
              <div class="advanced-tool text-bold playback-speed 4-faster">x4</div>
              <div class="advanced-tool text-bold playback-speed 8-faster">x8</div>
            </div>
            <div class="advanced-tool-spacer" style="margin-right:0;">
              <div class="advanced-tool text-bold step-back-frame">< Step</div>
              <div class="advanced-tool text-bold step-forward-frame">Step ></div>
            </div>
            <div class="clear"></div>
          </div>
        </div><!--END advanced tool bar-->
        </div>

<ul class="video-comment-container">
  <li class="video-comment-focused">
    <div class="fifteen-per-wide hundred-tall left text-center">
      <div class="hundred-tall">
        <p class="vertical-middle">00:05:09</p>
      </div>
    </div>
    <div class="hundred-tall eighty-five-per-wide left twenty-padding-right">
      <p class="vertical-middle">
        Initial infraction detected - vehicle is seen swerving multiple times from one lane to the next multiple times. Upon turning on lights the vehicle in question begins to travel at speeds exceeding 90mph.
      </p>
    </div>
    <div class="clear"></div>
    <!--END TIMELINE COMMENT-->
  </li>
  <li>
    <div class="fifteen-per-wide hundred-tall left text-center">
      <div class="hundred-tall">
        <p class="vertical-middle">00:06:15</p>
      </div>
    </div>
    <div class="hundred-tall eighty-five-per-wide left twenty-padding-right">
      <p class="vertical-middle">
        Vehicle is flagged down and pulled to a stop.</p>
    </div>
    <div class="clear"></div>
   <!--END TIMELINE COMMENT-->
  </li>
</ul>

<!--START Add Video Comment Container-->
        <div class="video-comment-control-container">
<!--START add video commment button -->
          <div class="left point-cursor" style="color: #fff;">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> <span style="color: #fff;">New Comment</span>
          </div><!--END add video comment button-->
<!--START count video comments-->
          <div class="right" style="color:#fff;">
            <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> 8 Comments available <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>


          </div>

          <div class="clear"></div><!--Clear the add video comment buttons-->
        </div><!--END add video comment container-->



        <div class="clear"></div><!--CLEAR the floated add comment containers (three in total)-->

<!--Footer Pusher (makes up for the space overlap caused by the auto scroll on video-main-container)-->
      <div class="video-footer-pusher"></div>

      </div><!--END video container inner-->
    </div><!--END video container main-->

<!--START video side bar container-->
    <div class="video-sidebar-container">
<!--START video sidebar inner-content -->
      <div class="video-sidebar-inner">
        <h2 class="video-sidebar-header">Highway 24 Speed Citation</h2>
        <div class="video-sidebar-date">Incident: Jan. 4, 2017</div>

        <ul class="video-meta-container">
          <li>
            <div class="fourty-per-wide ten-padding left video-meta-header">Status</div>
            <div class="sixty-per-wide ten-padding left video-meta-body">Fortified</div>
            <div class="clear"></div>
          </li>
          <li>
            <div class="fourty-per-wide ten-padding left video-meta-header">Purging</div>
            <div class="sixty-per-wide ten-padding left video-meta-body">4/25/2017</div>
            <div class="clear"></div>
          </li>
        </ul>

        <div style="position: absolute; bottom: 0;width: 100%;">
          <div class="video-taglist-header">Tag list</div>
            <ul id="tag-list" class="video-taglist-body">
              <li style="background: #cc3333;">
                <p class="left">Speeding</p>
                <div class="fa fa-minus-circle right link-button point-cursor"></div>
                <div class="clear"></div>
              </li>
                <li>
                  <p class="left">Improper Maneuver</p>
                  <div class="fa fa-minus-circle right link-button point-cursor"></div>
                  <div class="clear"></div>
                </li>
            </ul>
          <div style="text-align: center; padding: 20px;font-weight: bold; color: #fff;background: #333;">Associations</div>
          <ul class="video-associate-list">
            <li>
              <div class="fourty-per-wide ten-padding left video-meta-header">Case</div>
              <div class="sixty-per-wide ten-padding left video-meta-body">17-000987</div>
              <div class="clear"></div>
            </li>
            <li>
              <div class="fourty-per-wide ten-padding left video-meta-header">Intel</div>
              <div class="sixty-per-wide ten-padding left video-meta-body">Highway 24 Traffic Citations</div>
              <div class="clear"></div>
            </li>
          </ul>
          <div class="video-associate-add-button"><i class="fa fa-plus-circle" aria-hidden="false"></i></div>

        </div>
      </div><!--END video sidebar inner -->
    </div><!--END video sidebar container-->
    <div class="clear"></div>
    <script>
      var url = decodeURIComponent($('#getViewFromURL').html());
      log(url);
      $('#video').html('<source src="framework/'+url+'" type="video/mp4"/>');
    </script>
<?php require 'footer.php'; ?>

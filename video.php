<?php require 'header.php'; ?>

<?php /*if(!isset($_GET['view'])||!isset($_GET['type'])) header('Location: http://68.169.178.232/')*/ ?>

  <script src="framework/videoplayer/video.js"></script>

<!--START Content-pane-->
  <div class="content-pane" id="page-body" style="overflow: auto;">
<!--START video container main-->
    <div class="video-main-container">
<!--START video container inner-->
      <div class="video-container-inner">

<!--START video box-->
        <div style="margin-bottom: -2px;">
          <video id="video" style="width: 100%;" class="point-cursor" autoplay>
            <source src="framework/uploads/<?php echo $_GET['view'].".".$_GET['type'] ?>" type="video/<?php echo $_GET['type'] ?>">
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
        <div class="advanced-toolbar-container hidden">
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
              <div class="advanced-tool-img"><img src="img/camera-icon.png" /></div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool text-bold">x2</div>
              <div class="advanced-tool text-bold">x4</div>
              <div class="advanced-tool text-bold">x8</div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool text-bold">.2</div>
              <div class="advanced-tool text-bold">.4</div>
              <div class="advanced-tool text-bold">.8</div>
            </div>
            <div class="advanced-tool-spacer">
              <div class="advanced-tool text-bold">< Step</div>
              <div class="advanced-tool text-bold">Step ></div>
            </div>
            <div class="clear"></div>
          </div>
        </div><!--END advanced tool bar-->


<!--START video comment box-->
        <div class="video-comment-container video-comment-focused">
          <div class="fifteen-per-wide hundred-tall left text-center">
            <div class="hundred-tall">
              <p class="vertical-middle">10:20:15</p>
            </div>
          </div>
          <div class="hundred-tall eighty-five-per-wide left twenty-padding-right">
            <p class="vertical-middle">
              This is just a test to see how long the comment will be before it spills over, i have a feeling it might be a whilte but then again i don't really know untill i type out a bunch of text znd just see, an because i hate lorum ipsum i just type it out myself what eve rcomes to my mind as fast as i can type it but what ever
            </p>
          </div>
          <div class="clear"></div>
        </div><!--END video comment box-->

<!--START video comment box-->
        <div class="video-comment-container">
          <div class="fifteen-per-wide hundred-tall left text-center">
            <div class="hundred-tall">
              <p class="vertical-middle">10:20:15</p>
            </div>
          </div>
          <div class="hundred-tall eighty-five-per-wide left twenty-padding-right">
            <p class="vertical-middle">
              This is just a test to see how long the comment will be before it spills over, i have a feeling it might be a whilte but then again i don't really know untill i type out a bunch of text znd just see, an because i hate lorum ipsum i just type it out myself what eve rcomes to my mind as fast as i can type it but what ever
            </p>
          </div>
          <div class="clear"></div>
        </div><!--END video comment box-->


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
        <div style="background: #333; color: #fff; font-size: 18px; padding: 40px 0 40px 0; text-align: center;">Roadside drug bust</div>
        <div style="background: #666; color: #fff; font-size: 10px; padding: 20px; text-align: center;">Oct. 25, 2016</div>

        <ul class="video-meta-container">
          <li>
            <div class="fourty-per-wide ten-padding left video-meta-header">Status</div>
            <div class="sixty-per-wide ten-padding left video-meta-body">Fortified</div>
            <div class="clear"></div>
          </li>
          <li>
            <div class="fourty-per-wide ten-padding left video-meta-header">Purging</div>
            <div class="sixty-per-wide ten-padding left video-meta-body">10/25/2016</div>
            <div class="clear"></div>
          </li>
        </ul>

        <div style="position: absolute; bottom: 0; background: #509ace; padding: 20px;width: 100%;">test</div>

      </div><!--END video sidebar inner -->
    </div><!--END video sidebar container-->
    <div class="clear"></div>
<?php require 'footer.php'; ?>

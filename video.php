<?php require 'header.php'; ?>
<!--START Content-pane-->
  <div class="content-pane" id="page-body" style="overflow: auto;">
<!--START video container main-->
    <div class="video-main-container">
<!--START video container inner-->
      <div class="video-container-inner">

<!--START video box-->
        <div style="margin-bottom: -2px;">
          <video style="width: 100%;">
            <source src="video/test-video.mp4" type="video/mp4">
          </video>

<!--START video control container-->
            <div class="video-control-container">

              <div class="ten-per-wide hundred-tall left">
<!--START play button-->
                <div class="vertical-middle">
                  <img src="img/play-button.png" class="play-button"/>
                </div>
              </div><!--END play button-->

<!--START time code box left-->
              <div class="fifteen-per-wide hundred-tall left">
                <div class="vertical-middle">
                  <div class="time-code-box push-right">10:22:15</div>
                </div>
              </div><!--END time code box left-->

<!--START progress bar-->
                <div class="fifty-per-wide hundred-tall left">
                  <div class="one-wide hundred-tall ten-padding">
                    <div class="progress-bar"></div>
                    <div class="play-head"></div>
                    <div class="progress-bar-base"></div>
                  </div>
                </div><!--END progress bar-->

<!--START time code box right-->
                <div class="fifteen-per-wide hundred-tall left">
                  <div class="vertical-middle">
                    <div class="time-code-box">-03:40:18</div>
                  </div>
                </div><!--END time code box-right-->

<!--START advanced control buttons-->
                <div class="ten-per-wide hundred-tall left">
                  <div class="vertical-middle">
<!--GPS button to display only if GPS data is available-->
                    <img src="img/gps-icon.png" class="point-cursor video-player-button"/>
<!--Gear button to reveal the additional tool bar-->
                    <img src="img/gear-button.png" class="point-cursor video-player-button five-margin-left"/>
                    <div class="clear"></div>
                  </div>
                </div><!--END advanced control buttons-->

            </div><!--END video control container-->
        </div><!--END video box-->

<!--START advanced tool bar (hidden until revealed by gear button)-->
        <div class="advanced-toolbar-container">
          Advanced Tool Bar
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

<!--START add/skip comment container-->
        <div class="add-video-comment-button point-cursor">
          Add Comment
        </div>

        <div class="add-video-comment-spacer">
          <div class="add-video-comment-spacer-inner">&nbsp</div>
        </div>

        <div class="video-comment-skip-button">
          <
        </div>
        <div class="video-comment-count-container">
          <div class="video-comment-count-inner">
            8 comments total
          </div>
        </div>

        <div class="video-comment-skip-button">
          >
        </div>

        <div class="clear"></div><!--CLEAR the floated add comment containers (three in total)-->

<!--Footer Pusher (makes up for the space overlap caused by the auto scroll on video-main-container)-->
      <div class="video-footer-pusher"></div>

      </div><!--END video container inner-->
    </div><!--END video container main-->

<!--START video side bar container-->
    <div class="video-sidebar-container">
<!--START video sidebar inner-content -->
      <div class="video-sidebar-inner">


      </div><!--END video sidebar inner -->
    </div><!--END video sidebar container-->
    <div class="clear"></div>
<?php require 'footer.php'; ?>

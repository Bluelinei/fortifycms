<?php require 'header.php'; ?>

    <script src="./framework/casemanager.js?v=<?php echo sha1_file("framework/casemanager.js");?>"></script>
    <script src="./framework/BLFramework.js?v=<?php echo sha1_file("framework/BLFramework.js");?>"></script>

<!--START Content-pane-->
    <div class="content-pane" id="page-body">


<!--START  Builder wrapper-->
      <div class="builder-container">


<!--START Builder Header-->
        <div class="builder-block">
          <div class="header">
            <p class="vertical-middle fourty-padding-left left"><i class="fa fa-pencil-square" aria-hidden="true"></i>
 Case Builder</p>

            <div class="clear"></div>
          </div><!--END Builder Header-->

<!--START BUILDER BLOCK CONTENT WRAPPER-->
          <div class="builder-block-content">

<!--START QUESTION/ INVENTORY CONTAINER-->
          <div class="question-inventory-container">
  <!--REPORT QUESTION CONTAINER-->
            <div class="one-wide left report-question-block" style="overflow-x: auto;">
              <div class="thirty-padding vertical-middle">

    <!--REPORT QUESTION-->
                <div class="report-question twenty-margin-bottom">
                  <p class="one-wide report-question-header">What is the report number?</p>
                  <input id="report-number" type="text" placeholder="Report Number" class="one-wide"/>
                </div><!--END QUESTION-->

<!--REPORT QUESTION-->
            <div class="report-question twenty-margin-bottom">
              <p class="one-wide report-question-header"> What is the report nickname?</i>
</p>
              <input id="report-nickname" type="text" placeholder="Report Nickname" class="one-wide">
            </div><!--END QUESTION-->


  <!--REPORT QUESTION-->
              <div class="report-question twenty-margin-bottom">
                <p class="one-wide report-question-header"><i class="fa fa-map-marker" aria-hidden="true"></i>
 Report Location?</p>
                <input id="report-location" type="text" placeholder="Report Location" class="one-wide">
              </div><!--END QUESTION-->

    <!--REPORT QUESTION-->
                <div class="report-question twenty-margin-bottom">
                  <p class="one-wide report-question-header">What is the report type?</p>
                  <select class="one-wide" id="report-type">
                    <option>No Report</option>
                  </select>
                </div><!--END QUESTION-->
    <!--REPORT QUESTION-->
                <div class="report-question twenty-margin-bottom">
                  <p class="one-wide report-question-header">Assign Report Tags</p>
                  <select class="one-wide grey-text" id="report-tag">
                    <option style="color:#777;" selected>SELECT TAG</option>
                  </select>
                </div><!--END QUESTION-->
              </div>
            </div><!--END Repport Question Block-->
          </div><!--END question block-->




<!--START div that contains the evidence inventory and the follow up column -- this is how we get the proper spacing and stack the date/ time selector for pre-linking -->
          <div class="evidence-followup-wrapper">
            <div class="evidence-followup-block"><!--SETS the height of the top block, containing the evidence inventory and the follow up block-->

      <!--INVENTORY LIST CONTAINER-->
                <div class="left evidence-inventory-block">
                  <div class="fifteen-padding text-center">
                    <h3>Evidence Inventory</h3>
                    <div id="add-evidence" class="link-button point-cursor"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Evidence</div>
                  </div>
      <!--INVENTORY LIST-->
                  <ul id="evidence-inventory" class="evidence-inventory-list">
                  </ul>
                </div><!--END inventory list container-->

      <!--Further Admin Block-->
                <div class="fourty-per-wide left tag-block">
      <!--START tag-column-->
                  <div class="tag-column">
                    <div class="twenty-padding">
                    <h3>Follow Up?</h3>
        <!--Toggle Switch-->
                    <div class="onoffswitch center">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch">
                        <label class="onoffswitch-label" for="myonoffswitch">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div><!--END toggle switch-->

                    <div class="thirty-margin-top">
                      <h3>Tag List</h3>
                      <ul id="tag-list">
                      </ul>
                    </div>
                  </div>
                  <div id="fortify-notification" class="hidden">Cases Saved</div>
                </div><!--END tag-column-->
                </div><!--END further admin block // this is the 100% block that contains the tag list and the button, it's how we get the 5px spacing between the evidence inventory block and then the 5px spacing between the fortify case button and the tag list-->
              </div><!--END INVENTORY/follow up CONTAINER-->

    <!--START date/ time selector for pre-linking -->
            <div class="prelink-wrapper">

              <div class="timeset-wrapper">
                <div class="one-wide" style="display: block;background: rgba(255,187,187,0.8);color:#fff;height: 100%;font-size: 11px;text-align: center;">
                  <div class="clock-container">
                    <div class="increment"><p class="vertical-middle">+</p></div>
                    <div class="time"><p class="vertical-middle">11</p></div>
                    <div class="increment"><p class="vertical-middle">-</p></div>
                  </div>
                  <div class="clock-container">
                    <div class="increment"><p class="vertical-middle">+</p></div>
                    <div class="time"><p class="vertical-middle">23</p></div>
                    <div class="increment"><p class="vertical-middle">-</p></div>
                  </div>
                  <div class="clock-container">
                    <div class="increment"><p class="vertical-middle">+</p></div>
                    <div class="time"><p class="vertical-middle">pm</p></div>
                    <div class="increment"><p class="vertical-middle">-</p></div>
                  </div>
                  <div class="clock-container" style="background: #550000;">
                    <div class="clock-month"><p style="position: absolute; bottom: 0;width: 100%; text-align: center;">Nov</p></div>
                    <div class="clock-day">16</div>
                  </div>
                  <div class="clock-container" style="background: rgba(000,255,000,0.3);">
                    <div class="vertical-middle" style="font-size: 35px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i>
                  </div>
                      </div>
                    <div class="clear"></div>
                  </div>
              </div>

              <div class="prelink-block">

                <div class="three-wide" style="height: 100%; background: #55ff55;">
                  <div class="vertical-middle">pre-linking on</div>
                  </div>
                <div class="three-wide" style="height: 100%;background: #ff8888;">
                  <div class="vertical-middle">Start<br>11:52am</div>
                </div>
                <div class="three-wide" style="height: 100%; background: #ff8888;">
                  <div class="vertical-middle">End<br>12:25pm</div>
                </div>
              </div>
            </div>
          </div><!--END evidence inventory/follow up wrapper that houses evidence inventory and follupw up blocks as well as the date/time selector for prelinking stack-->
          </div><!--END builder-block content wrapper-->
          </div><!--END builder-block-->
        </div><!--END builder wrapper-->











<!--START manager wrapper-->
      <div class="manager-container">
        <h2><p class="vertical-middle"><i class="fa fa-archive" aria-hidden="true"></i>Case Manager</p></h2>
        <div style="height: 5px; background: #fff; width: 100%;"></div>
        <h3 class="seventy-per-wide left ten-padding">Reference ID</h3>
        <h3 class="twenty-per-wide left ten-padding">Files</h3>
        <h3 class="ten-per-wide left new-case" style="padding: 8px" id="new-case"><i class="fa fa-plus-circle" style="font-size: 19px; color: #fff" aria-hidden="true"></i></h3>
        <div class="clear"></div>
<!--CASE LIST-->
        <ul id="case-list">
        </ul>
        <div id="fortify-button" class="fortify-case-button" style="height:auto;padding: 15px;margin-top:5px;border: 1px solid #440000;"><p class="no-event"><span class="fa-stack"><i class="fa fa-shield fa-stack-2x"></i><i class="fa fa-unlock-alt fa-stack-1x fa-inverse"></i></span>  Fortify Cases</p></div>
      </div><!--END builder wrapper-->

    </div><!--END Content Pane-->
    <div class="clear"></div>
    <input method="post" type="file" id="openfilebrowser" name="files[]" enctype="multipart/form-data" style="display:none;" multiple />

<?php require 'footer.php'; ?>

<div id="pageload-overlay">
  <img class="loading-gif" style="width:300px; height:auto;" src="../img/fortifyloading.gif"/>
</div>

<div class="media-preview-overlay hidden no-event"></div>

<script>
  $('.media-preview-overlay-bg').on('click', function(e){
    $('.media-preview-overlay').addClass('hidden');
    $('#overlay-video').empty();
    $('#overlay-video').remove();
    $('#overlay-image').attr('src','');
  });
</script>
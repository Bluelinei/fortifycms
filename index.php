<?php require 'header.php'; ?>

<!--START Content-pane-->
    <div class="content-pane" id="page-body">
<!--START  Builder wrapper-->
      <div class="builder-container">


<!--START Builder Header-->
        <div class="builder-block">
          <div class="header">
            <p class="vertical-middle fourty-padding-left left">Case Builder</p>

            <div class="clear"></div>
          </div><!--END Builder Header-->


<!--START QUESTION/ INVENTORY CONTAINER-->
          <div class="question-inventory-container">
  <!--REPORT QUESTION CONTAINER-->
            <div class="two-wide left report-question-block">
              <div class="thirty-padding vertical-middle">

    <!--REPORT QUESTION-->
                <div class="report-question thirty-margin-bottom">
                  <p class="one-wide report-question-header">What is the report number?</p>
                  <input id="report-number" type="text" placeholder="Report Number" class="one-wide">
                </div><!--END QUESTION-->

  <!--REPORT QUESTION-->
              <div class="report-question thirty-margin-bottom">
                <p class="one-wide report-question-header">Report Location?</p>
                <input id="report-location" type="text" placeholder="Report Location" class="one-wide">
              </div><!--END QUESTION-->

  <!--REPORT QUESTION-->
              <div class="report-question thirty-margin-bottom">
                <p class="one-wide report-question-header">What is the report nickname?</p>
                <input id="report-nickname" type="text" placeholder="Report nickname" class="one-wide">
              </div><!--END QUESTION-->


    <!--REPORT QUESTION-->
                <div class="report-question thirty-margin-bottom">
                  <p class="one-wide report-question-header">What is the report type?</p>
                  <select class="one-wide">
                    <option>Arrest Report</option>
                    <option>CAD Report</option>
                  </select>
                </div><!--END QUESTION-->
    <!--REPORT QUESTION-->
                <div class="report-question thirty-margin-bottom">
                  <p class="one-wide report-question-header">Assign Report Tags</p>
                  <select class="one-wide" id="report-tag">
                    <option>Domestic Abuse</option>
                    <option>Child Endangerment</option>
                    <option>Sexual Assault</option>
                    <option>DUI</option>
                    <option>Speeding</option>
                    <option>Drug Possesion</option>
                  </select>
                </div><!--END QUESTION-->
              </div>
            </div><!--END Repport Question Block-->



            <!--INVENTORY LIST CONTAINER-->
                      <div class="two-wide left evidence-inventory-block">
                        <div class="fifteen-padding text-center">
                          <h3>Evidence Inventory</h3>
                          <div id="add-evidence" class="link-button point-cursor"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Evidence</div>
                        </div>
            <!--INVENTORY LIST-->
                        <ul id="evidence-inventory" class="evidence-inventory-list">
                        </ul>
                      </div><!--END inventory list container-->
                  </div><!--END QUESTION/ INVENTORY CONTAINER-->

<!--Further Admin Block-->
          <div class="twenty-per-wide left tag-block">
              <div class="twenty-padding">
              <h3>Further Admin</h3>
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
            <div class="fortify-case-button-container">
              <div style="height: 5px; width: 100%; background: #fff;border-top: 1px solid #bbb;"></div>
              <div id="fortify-button" class="one-wide fortify-case-button point-cursor text-bold twenty-padding">Fortify Case</div>
            </div>
          </div><!--END further admin block-->



        </div>
      </div><!--END builder wrapper-->
<!--START manager wrapper-->
      <div class="manager-container">
        <h2><p class="vertical-middle">Case Manager</p></h2>
        <div style="height: 5px; background: #fff; width: 100%;"></div>
        <h3 class="seventy-per-wide left ten-padding">Reference ID</h3>
        <h3 class="twenty-per-wide left ten-padding">Files</h3>
        <h3 class="ten-per-wide left ten-padding new-case" id="new-case"><i class="fa fa-plus-circle" style="font-size: 14px; color: #fff" aria-hidden="true"></i></h3>
        <div class="clear"></div>
<!--CASE LIST-->
        <ul id="case-list">
        </ul>
      </div><!--END builder wrapper-->

    </div><!--END Content Pane-->
    <div class="clear"></div>
    <input method="post" type="file" id="openfilebrowser" name="files[]" enctype="multipart/form-data" style="display:none;" multiple />
  </div><!--END Main Content Area Wrapper-->

<?php require 'footer.php'; ?>

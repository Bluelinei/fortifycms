  <!--EVIDENCE MANAGER OVERLAY-->
  <div id="media-browser" class="overlay-container">
      <div class="media-block-evidence transition-ease" style="height:100%;">
        <div style="width: 100%; background: rgba(255,255,255,0.1); padding: 20px 40px;">
          <div class="vertical-middle">
            <div id="close-media-browser" class="close left point-cursor text-center"><i class="vertical-middle fa fa-close" style="font-size: 20px;"></i></div>

            <div id="fileupload" class="upload left ten-margin-left point-cursor no-select">
              <p class=" vertical-middle text-center"><i class="fa fa-cloud-upload fa-lg five-margin-right"></i>Upload Evidence</p>
            </div>
            <div id="shelf-tag-button" class="upload left ten-margin-left point-cursor no-select">
              <p class=" vertical-middle text-center"><i class="fa fa-barcode fa-lg five-margin-right"></i>Shelf Tag</p>
            </div>
          </div>
          <div class="clear"></div>
        </div><!--END evidence-browser header-->

        <div class="media-block-wrapper">
          <ul id="mediabrowser" class="media-block">
          </ul>
        </div>
      </div>



        <div class="media-block-shelf-tag transition-ease">
          <h2>
            <div id="close-media-browser" class="close left point-cursor text-center"><i class="vertical-middle fa fa-close" style="font-size: 20px;"></i></div>
            <label>Shelf Tag</label>
            <div class="clear"></div>
          </h2>

          <div class="two-wide shelf-tag-questions">
            <input type="text" placeholder="Item Name">
            <input type="text" placeholder="Item Color">
            <input type="text" placeholder="Number of Items">
          </div>
          <div class="two-wide shelf-tag-photo">
            <i class="fa fa-picture-o photo-placeholder" aria-hidden="true"></i>
            <div class="point-cursor upload text-center"><p class="vertical-middle">Add Photo</p></div>
          </div>
          <div class="clear"></div>
      </div>
  </div><!--END Evidence Manager Overlay-->

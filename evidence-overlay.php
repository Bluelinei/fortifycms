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

<!--SHELF TAG BLOCK-->
        <div class="media-block-shelf-tag transition-ease">
          <h2>
            <div id="return-to-evidence" class="close left point-cursor text-center"><i class="vertical-middle fa fa-chevron-up" style="font-size: 20px;"></i></div>
            <label>Shelf Tag</label>
            <div class="clear"></div>
          </h2>
          <div class="two-wide shelf-tag-questions">
            <input id="shelf-item-name" type="text" placeholder="Item Name">
            <input id="shelf-item-color" type="text" placeholder="Item Color">
            <input id="shelf-item-num" type="text" placeholder="Number of Items">
            <input id="shelf-to-manager" type="submit" value="Manager"><input id="shelf-to-case" type="submit" value="Active Case">
          </div>
          <div class="two-wide shelf-tag-photo">
            <i class="fa fa-picture-o photo-placeholder" aria-hidden="true"></i>
            <div id="shelf-item-image" class="point-cursor upload text-center"><p class="vertical-middle">Add Photo</p></div>
          </div>
          <div class="clear"></div>
          <textarea id="shelf-item-desc" name="description" class="shelf-tag-description"></textarea>
      </div><!--END shelftag block-->
  </div><!--END Evidence Manager Overlay-->

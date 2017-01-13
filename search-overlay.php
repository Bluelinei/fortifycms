
<!--START search box-->

  <div class="search-box search-close hidden" style="z-index: 3000;">


<!--START click-to-close container -->
    <div class="search-close">
      <form class="search-field">
        <input id="search-query" type="text" placeholder="Enter search criteria" />
        <input type="submit" value="Run Search" />
        <div class="clear"></div>
      </form>

      <!--START show search critera box-->
      <div class="search-critera">
        <div id="query-output"></div>
      </div><!--END search critera box-->




<!--START SEARCH RESULT BLOCK
      <div class="search-result">
        <div class="search-result-meta">
          <div class="search-meta-header">
            <h2>16-00001234</h2>
            <h3>Domestic Dispute at Ridgewood Estates</h3>
          </div><!--END search meta header

          <div class="search-meta">
            Create Date: 1-5-17<br>
            Report Officer: Zach Watts<br>
            Report Type: Arrest Report<br><br>
            <i class="fa fa-map-marker" aria-hidden="true"></i> 169 Ridgewood Pkwy, 31909
            <br>
          </div>
        </div><!--END result meta

<!--START search result content-
        <div class="search-result-content">
          <div class="search-result-col ten-padding twenty-padding-left twenty-padding-right">
            <ul class="tag"> <!-- THIS ELEMENT REQUIRES A UID 
              <li>DUI</li>
              <li>Domestic Abuse</li>
              <li>Public Endangerment</li>
            </ul>
          </div>
          <div class="search-result-col">
            <ul class="search-result-evidence-count hundred-tall">
              <li>
                <div class="left">Video</div>
                <div class="right">12</div>
                <div class="clear"></div>
              </li>
              <li>
                <div class="left">Photo</div>
                <div class="right">2</div>
                <div class="clear"></div>
              </li>
              <li>
                <div class="left">Physical</div>
                <div class="right">0</div>
                <div class="clear"></div>
              </li>
              <li>
                <div class="left">Document</div>
                <div class="right">1</div>
                <div class="clear"></div>
              </li>
              <li>
                <div class="left">Audio</div>
                <div class="right">0</div>
                <div class="clear"></div>
              </li>
            </ul>
          </div>
          <div class="search-result-col">
            <h2 class="suspect-search-header">Suspect List</h2>
            <ul class="tag ten-padding twenty-padding-left twenty-padding-right"> <!-- THIS ELEMENT REQUIRES A UID 
              <li>Ronald McDonald</li>
              <li>Jared from Subway</li>
              <li>The BurgerKing</li>
            </ul>
            <div class="search-result-more-button">10 more suspects</div>
          </div>
          <div class="clear"></div>
        </div><!--END search result content-
        <div class="clear"></div>
      </div><!--END SEARCH RESULT BLOCK-





      <!--START SEARCH RESULT BLOCK
            <div class="search-result">
              <div class="search-result-meta">
                <div class="search-meta-header">
                  <h2>16-00001234</h2>
                  <h3>Domestic Dispute at Ridgewood Estates</h3>
                </div><!--END search meta header 

                <div class="search-meta">
                  Create Date: 1-5-17<br>
                  Report Officer: Zach Watts<br>
                  Report Type: Arrest Report<br><br>
                  <i class="fa fa-map-marker" aria-hidden="true"></i> 169 Ridgewood Pkwy, 31909
                  <br>
                </div>
              </div><!--END result meta

      <!--START search result content
              <div class="search-result-content">
                <div class="search-result-col ten-padding twenty-padding-left twenty-padding-right">
                  <ul class="tag"> <!-- THIS ELEMENT REQUIRES A UID 
                    <li>DUI</li>
                    <li>Domestic Abuse</li>
                    <li>Public Endangerment</li>
                  </ul>
                </div>
                <div class="search-result-col">
                  <ul class="search-result-evidence-count hundred-tall">
                    <li>
                      <div class="left">Video</div>
                      <div class="right">12</div>
                      <div class="clear"></div>
                    </li>
                    <li>
                      <div class="left">Photo</div>
                      <div class="right">2</div>
                      <div class="clear"></div>
                    </li>
                    <li>
                      <div class="left">Physical</div>
                      <div class="right">0</div>
                      <div class="clear"></div>
                    </li>
                    <li>
                      <div class="left">Document</div>
                      <div class="right">1</div>
                      <div class="clear"></div>
                    </li>
                    <li>
                      <div class="left">Audio</div>
                      <div class="right">0</div>
                      <div class="clear"></div>
                    </li>
                  </ul>
                </div>
                <div class="search-result-col">
                  <h2 class="suspect-search-header">Suspect List</h2>
                  <ul class="tag ten-padding twenty-padding-left twenty-padding-right"> <!-- THIS ELEMENT REQUIRES A UID 
                    <li>Ronald McDonald</li>

                    <li>Dave Thomas</li>
                  </ul>
                </div>
                <div class="clear"></div>
              </div><!--END search result content
              <div class="clear"></div>
            </div><!--END SEARCH RESULT BLOCK-->
      <div id="results-block"></div>
      <!--LOAD MORE SEARCH RESULTS BUTTON-->
      <div class="load-more-searches text-center">Load More Results</div>

    </div><!--END div container that allows for click-to-close -->
  </div>
  <script>
    function setButtonFunctions()
    {
      $('#search-query').on('input', function(){
        if(!$('#search-query').val().trim().length) {$('#query-output').html(''); $('#results-block').html(''); return;}
        var f = new FormData();
        f.append('query', $('#search-query').val().trim());
        f.append('searchtype', 'auto');
        ajax('framework/search.php',f,function(response){
          if(!response) {$('#query-output').html(''); $('#results-block').html(''); return;}
          log(response);
          var results = JSON.parse(response);
          $('#results-block').html('');
          $('#query-output').html('<span>'+results.length+' possible result'+(results.length>1?'s':'')+' for </span> "'+$('#search-query').val().trim()+'"<br>');
          for(var i=0; i<results.length; i++)
          {
            genElement(results[i]);
          }
        });
      });
    }

    function genElement(c)
    {
      c.data = JSON.parse(c.data);
      c.tags = JSON.parse(c.tags);
      //REQUIRED DATA
      //data
      //  casedate
      //  vidcount
      //  imgcount
      //  phycount
      //  doccount
      //  audcount
      //  suspects (list)
      var element = '<div class="search-result">\
                      <div id="'+c.uid+'_addtoworkspace" class="add-to-workspace"></div>\
                      <div class="search-result-meta">\
                        <div class="search-meta-header">\
                          <h2>'+c.ref+'</h2>\
                          <h3>'+c.nickname+'</h3>\
                        </div>\
                        <div class="search-meta">\
                          Create Date: '+c.data.casedate+'<br>\
                          Report Officer: Zach Watts<br>\
                          Report Type: '+c.type+'<br><br>\
                          <i class="fa fa-map-marker" aria-hidden="true"></i> '+c.data.location+'<br>\
                        </div>\
                      </div>\
                      <div class="search-result-content">\
                        <div class="search-result-col ten-padding twenty-padding-left twenty-padding-right">\
                          <ul class="tag">';
      for(var i=0; i<c.tags.length; i++) {element+='<li>'+c.tags[i]+'</li>';}
      element+='          </ul>\
                        </div>\
                      <div class="search-result-col">\
                        <ul class="search-result-evidence-count hundred-tall">\
                          <li>\
                            <div class="left">Video</div>\
                            <div class="right">'+c.data.vidcount+'</div>\
                            <div class="clear"></div>\
                          </li>\
                          <li>\
                            <div class="left">Photo</div>\
                            <div class="right">'+c.data.imgcount+'</div>\
                            <div class="clear"></div>\
                          </li>\
                          <li>\
                            <div class="left">Physical</div>\
                            <div class="right">'+c.data.phycount+'</div>\
                            <div class="clear"></div>\
                          </li>\
                          <li>\
                            <div class="left">Document</div>\
                            <div class="right">'+c.data.doccount+'</div>\
                            <div class="clear"></div>\
                          </li>\
                          <li>\
                            <div class="left">Audio</div>\
                            <div class="right">'+c.data.audcount+'</div>\
                            <div class="clear"></div>\
                          </li>\
                        </ul>\
                      </div>\
                      <div class="search-result-col">\
                        <h2 class="suspect-search-header">Suspect List</h2>\
                        <ul class="tag ten-padding twenty-padding-left twenty-padding-right">';
      //for(var i=0; i<c.data.suspects.length; i++) {element.append('<li>'+c.data.suspects[i]+'</li>')}
      element+='        </ul>\
                      </div>\
                      <div class="clear"></div>\
                    </div>\
                    <div class="clear"></div>\
                  </div>';
      $('#results-block').append($(element));
      $(document).on('click', c.uid+'_addtoworkspace', function(){
        var cs = c.uid;
      });
    }

    $(window).on('load', function(){
      setButtonFunctions();
    });
  </script>

<!--END search box-->

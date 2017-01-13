
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
      <div id="results-block"></div>
      <!--LOAD MORE SEARCH RESULTS BUTTON-->
      <div class="load-more-searches text-center">Load More Results</div>

    </div><!--END div container that allows for click-to-close -->
  </div>
  <script>
    var eventlisteners = [];
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
      $(document).on('click', '.add-to-workspace', function(e){
        e.stopPropagation()
        var cs = $(e.target).children('div').html();
        if(getCaseById(cs)) return;
        var f = new FormData();
        f.append('uid', cs);
        f.append('get', 'case');
        ajax('framework/search.php',f,function(response){
          log(response);
          var o = JSON.parse(response);
          var c = new Case(o.case.uid);
          var data = JSON.parse(o.case.data);
          c.nickname = o.case.nickname;
          c.casenum = o.case.ref;
          c.location = data.location;
          if(o.case.tags) c.tags = JSON.parse(o.case.tags);
          c.admin = (o.case.admin=="1"?true:false);
          c.officer = o.case.users;
          c.type = o.case.type;
          c.filelist = tokenizeUID(o.case.evidence);
          c.prelinkstart = (data.prelinkstart?Number(data.prelinkstart):getUnixTime());
          c.prelinkend = (data.prelinkend?Number(data.prelinkend):getUnixTime());
          c.prelinkenable = (data.prelinkenable?true:false);
          c.creationdate = (data.casedate?data.casedate:getUnixTime());
          var dbevidence = o.evidence;
          for(var i=0; i<dbevidence.length; i++)
          {
            var loaded = getCasefileById(dbevidence[i].uid);
            if(loaded)
            {
              c.addFile(loaded);
              continue;
            }
            var data = JSON.parse(dbevidence[i].data);
            var cf = new Casefile(null, dbevidence[i].uid);
            cf.type = dbevidence[i].type;
            cf.caseindex = tokenizeUID(dbevidence[i].caseindex);
            cf.filepath = data.file_path;
            cf.filetype = data.file_type;
            cf.name = dbevidence[i].nickname;
            cf.uploaddate = Number(data.upload_date);
            cf.filedate = Number(data.lastmodified);
            cf.thumbnail = data.thumbnail;
            cf.init();
            //Populate cases with evidence
            c.addFile(cf);
          }
          setAsActiveCase(c);
          updateReport();
          $('.search-box').addClass('hidden');
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
      var element= '<div class="search-result">\
                      <div class="add-to-workspace no-select"><div style="display:none;">'+c.uid+'</div></div>\
                      <div class="search-result-meta">\
                        <div class="search-meta-header">\
                          <h2>'+c.ref+'</h2>\
                          <h3>'+c.nickname+'</h3>\
                        </div>\
                        <div class="search-meta">\
                          Creation Date: '+Date().toLocaleString(c.data.casedate*1000)+'<br>\
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
    }

    $(window).on('load', function(){
      setButtonFunctions();
    });
  </script>

<!--END search box-->

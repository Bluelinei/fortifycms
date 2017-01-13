//GLOBAL VARIABLES (MAINLY FOR DEBUGGING)

var databaseload = 0;

function loading(v)
{
	databaseload+=v;
	if(!databaseload) {$('#pageload-overlay').addClass('hidden'); log('Page Finished Loading');}
}

var workingcase;

var prelink = new Prelink();

const caselistElementID = '#case-list';
const filelistElementID = '#evidence-inventory';
const fileuploadElementID = '#fileupload';

var session;


//MISC Functions

function getAPISupport() //Test whether or not the browser can use the html5 functionality.
{
	if(!window.File || !window.FileList)
	{
		alert('Your internet browser does not support the necessary dependencies for this application.\nFortify™ is recommended for use with the latest versions of Firefox and Chrome browsers.');
		return false;
	}
	return true;
}

function formatSize(fileSizeInBytes) //Formats the file size into human readable format.
{
    var i = -1;
    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
    do {
        fileSizeInBytes = fileSizeInBytes / 1000;
        i++;
    } while (fileSizeInBytes > 1000);

    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
};

function fileOutput(files) //Outputs the information for the uploaded file onto the test webpage. This should be altered to proper formatting for the final release. Currently formatted for test page.
{
	var cf;
	for(var i=0,f; f=files[i]; i++)
	{
		newCasefile(f);
	}
}

//File Upload functions

function postFiles(files) //Sends file and file data to PHP for processing and uploading.
{
	pushStack('postFiles');
	var len = files.length;
	for(var i=0; i<len; i++)
	{
		newCaseFile(files[i]);
	}
	popStack();
}

function closeAllBrowsers()
{
	$('#media-browser').removeClass('show');
	$('.notification-button-container').addClass('hidden');
	$('.search-box').addClass('hidden');
	$('.media-block-evidence').removeClass('trans-y-up');
	$('.media-block-shelf-tag').removeClass('trans-y-up');
}

function closeMediaBrowser()
{
	$('#media-browser').removeClass('show');
	$('.media-block-evidence').removeClass('trans-y-up');
	$('.media-block-shelf-tag').removeClass('trans-y-up');
}

function toggleMediaBrowser(event)
{
	event.stopPropagation();
	if($('#media-browser').hasClass('show')) $('#media-browser').removeClass('show');
	else $('#media-browser').addClass('show');
}

function openFileBrowser() {document.getElementById('openfilebrowser').click();} //Calls the file upload input which is hidden by default. This if to allow clicking of drop area to function as browse button.

function handleFileSelect(evt) //Is called when the browser is used for uploading files
{
	var files = evt.target.files;
	postFiles(files);
}

function handleFileDrop(evt) //Is called when files are dropped onto the drop area for upload.
{
	evt.stopPropagation();
	evt.preventDefault();
	$('#dropZone').removeClass('visible');
	var files = evt.dataTransfer.files;
	var fpath = postFiles(files);
	fileOutput(files, fpath);
}

function handleDragover(evt) //Sets drag over cursor elements just to make it look pretty.
{
	evt.stopPropagation();
	evt.preventDefault();
	evt.dataTransfer.dropEffect = 'copy';
}

function changeCase()
{
	workingcase.changeCase(true);
}

function updateDate(date)
{
	var darray = tokenize(date, '/');
	$('.clock-year').html(darray[2]);
	$('.clock-month').html(readMonth(Number(darray[0]-1)));
	$('.clock-day').html(darray[1]);
}

function readMonth(m)
{
	switch(m)
	{
		case 0:
			return 'Jan';
			break;
		case 1:
			return 'Feb';
			break;
		case 2:
			return 'Mar';
			break;
		case 3:
			return 'Apr';
			break;
		case 4:
			return 'May';
			break;
		case 5:
			return 'Jun';
			break;
		case 6:
			return 'Jul';
			break;
		case 7:
			return 'Aug';
			break;
		case 8:
			return 'Sep';
			break;
		case 9:
			return 'Oct';
			break;
		case 10:
			return 'Nov';
			break;
		case 11:
			return 'Dec';
			break;
	}
}

function setEventListeners()
{
	$('#openfilebrowser').on('change', handleFileSelect);
	$(fileuploadElementID).on('click', openFileBrowser);
	$('#fortify-button').on('click', fortifyActiveCase);
	$('#new-case').on('click', newCase);
	$('#report-number').on('input', updateInfo);
	$('#report-location').on('input', updateInfo);
	$('#report-tag').on('change', setTag);
	$('#report-tag').val('SELECT TAG');
	$('#myonoffswitch').checked = false;
	$('#myonoffswitch').on('click', toggleAdmin);
	$('#report-type').on('change', setCaseType);
	$('#nav-evidence').on('click', toggleMediaBrowser);
	$('#add-evidence').on('click', toggleMediaBrowser);
	$('#close-media-browser').on('click', closeMediaBrowser);
	$('#page-body').on('click', closeAllBrowsers);
	$('#shelf-tag-button').on('click', function(e) {
		$('.media-block-evidence').addClass('trans-y-up');
		$('.media-block-shelf-tag').addClass('trans-y-up');
	});
	$('#return-to-evidence').on('click', function(e) {
		$('.media-block-evidence').removeClass('trans-y-up');
		$('.media-block-shelf-tag').removeClass('trans-y-up');
	})

	//On Case change
	$('#report-number').on('input', changeCase);
	$('#report-location').on('input', changeCase);
	$('#report-tag').on('change', changeCase);
	$('#report-type').on('change', changeCase);
	$('#myonoffswitch').on('click', changeCase);

	//Timeset
	$('.time-start-button').on('click', function(e) {
		if(prelink.editing) return;
		prelink.edit('start');
		$('.timeset-wrapper').removeClass('hidden');
	});
	$('.time-end-button').on('click', function(e) {
		if(prelink.editing) return;
		prelink.edit('end');
		$('.timeset-wrapper').removeClass('hidden');
	});
	$('.prelink-toggle').on('click', function(e) {
		prelink.enable();
	});

	$('.timeset-confirm').on('click', function(e) {
		$('.timeset-wrapper').addClass('hidden');
		$('.dateobj').addClass('hidden');
		prelink.setTime();
		prelink.editing = undefined;
	});

	$('.time-input').on('change', function(e) {
		var src = $(e.target);
		if(src.hasClass('hour-num'))
		{
			if(src.val()>23) src.val(0);
			else if(src.val()<0) src.val(23);
		}
		else if(src.hasClass('minute-num'))
		{
			if(src.val()>59) src.val(0);
			else if(src.val()<0) src.val(59);
		}
	});
	$('.time-input').on('focus', function(e) {
		$(e.target).select();
	});
	$('.set-calendar').on('click', function(e) {
		if($('.dateobj').hasClass('hidden')) $('.dateobj').removeClass('hidden');
		else $('.dateobj').addClass('hidden');
	});

	//KEYBOARD SHORTCUTS
	$(document).on('keydown', function(e) {
		//log(e.keyCode);
		if(e.keyCode==13||e.which==13) {$(':focus').blur();}
	});

	//TEMP SETTINGS STUFF
	var d = new Date();
	$('.clock-year').html(d.getFullYear());
	$('.clock-month').html(readMonth(d.getMonth()));
	$('.clock-day').html(d.getDate());
	$('.hour-num').val(d.getHours());
	$('.minute-num').val(d.getMinutes());
}


function getDatabase()
{
	//request data from server by session
	var f = new FormData();
	f.append('function','get');
	loading(1);
	ajax(SERVER_ADDRESS+'framework/functions.php',f,function(response){
		log(response);
		var obj = JSON.parse(response);
		//Will return assoc array of 'cases' and 'evidence' with all of their data
		var dbcases = obj.cases;
		var dbevidence = obj.evidence;
		//Generate case objects
		for(var i=0; i<dbcases.length; i++)
		{
			var c = new Case(dbcases[i].uid);
			var data = JSON.parse(dbcases[i].data);
			c.nickname = dbcases[i].nickname;
			c.casenum = dbcases[i].ref;
			c.location = data.location;
			if(dbcases[i].tags) c.tags = JSON.parse(dbcases[i].tags);
			c.admin = (dbcases[i].admin=="1"?true:false);
			c.officer = dbcases[i].users;
			c.type = dbcases[i].type;
			c.filelist = tokenizeUID(dbcases[i].evidence);
			c.prelinkstart = (data.prelinkstart?Number(data.prelinkstart):getUnixTime());
			c.prelinkend = (data.prelinkend?Number(data.prelinkend):getUnixTime());
			c.prelinkenable = (data.prelinkenable?true:false);
		}
		//Generate evidence objects
		if(dbcases.length)
		{
			for(var i=0; i<dbevidence.length; i++)
			{
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
				for(var j=0; j<cases.length; j++)
				{
					if(!cases[j].filelist.length) continue;
					if(inList(cases[j].filelist, cf.uid)) {cases[j].addFile(cf);}
				}
			}
			setAsActiveCase(cases[0]);
		}
		else
		{
			newCase();
		}
		if(workingcase) updateReport();
		loading(-1);
	}, function(response){
		log(response);
		$('html').html('');
		$('html').append(response.responseText);
		loading(-1);
	});
}

function loadINI()
{
	loading(1);
	ajax('framework/getsession.php', null, function(response) {
		var session = JSON.parse(response);
		if(!session.agency)
		{
			log('No session agency defined...');
			loading(-1);
			return;
		}
		$.ajax({
			method:'POST', url:'conf/'+session.agency+'.conf', dataType:'text', mimeType:'text/plain',
			success: function(content) {
				var tokens = content.split(/\r\n|\r|\n/g);
				var setfor;
				for(var i=0; i<tokens.length; i++)
				{
					if(tokens[i]=='[TAGS]') {setfor = 'tags'; continue;}
					else if(tokens[i]=='[TYPE]') {setfor = 'type'; continue;}
					else
					{
						if(!setfor) {log('WARNING: Malformed or corrupt CONF file on line: '+(i+1)); continue;}
						if(setfor=='tags')
						{
							$('#report-tag').append('<option>'+tokens[i]+'</option>');
							continue;
						}
						else if(setfor=='type')
						{
							$('#report-type').prepend('<option>'+tokens[i]+'</option>');
							continue;
						}
					}
				}
				generateTags();
				loading(-1);
			},
			error: function(err) {
				log('ERROR LOADING AGENCY CONFIGURATION: '+session.agency+'.conf');
				loading(-1);
				return;
			}
		});
	});
}

window.onload = function()
{
	loading(1);
	getAPISupport();
	loadINI();
	//getDatabase();
	newCase();
	setEventListeners();
	loading(-1);
}
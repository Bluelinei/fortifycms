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
}

function closeMediaBrowser()
{
	$('#media-browser').removeClass('show');
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
	$('.meridiem').on('click', function(e) {
		var src = $(e.target);
		if(src.html()=='AM') src.html('PM');
		else src.html('AM');
	});

	$('.time-input').on('change', function(e) {
		var src = $(e.target);
		if(src.hasClass('hour-num'))
		{
			if(src.val()>12) src.val(1);
			else if(src.val()<1) src.val(12);
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

	$('#shelf-item-image').on('click', function(e) {
		
	});
	
	//TEMP SETTINGS STUFF
	var d = new Date();
	$('.clock-year').html(d.getFullYear());
	$('.clock-month').html(readMonth(d.getMonth()));
	$('.clock-day').html(d.getDate());
	$('.minute-num').val(d.getMinutes());
	var hour = d.getHours();
	if(hour>12)
	{
		$('.hour-num').val(hour-12);
		$('.meridiem').html('PM');
	}
	else
	{
		$('.hour-num').val(hour);
		$('.meridiem').html('AM');
	}
}

function getDatabase()
{
	var f = new FormData();
	f.append('table', 'quickreport');
	f.append('function', 'get');
	loading(1);
	var compiled = [];
	ajax('framework/functions.php', f, function(response) {
		log(response);
		var o = JSON.parse(response);
		if(o.length)
		{
			//LOAD CASES
			var len = o.length;
			for(var i=0; i<len; i++)
			{
				var x = o[i];
				var c = new Case(x.uid);
				c.nickname = x.nickname;
				c.casenum = (x.casenum?x.casenum:'[No Report Number]');
				c.location = x.location;
				c.type = (x.type=='undefined'?'':x.type);
				c.tags = tokenize(x.tags, '<#>');
				c.admin = (x.admin=="1"?true:false);
				c.officer = x.officer;
				var evidence = tokenizeUID(x.evidence);

				//FROM CASES COMPILE LIST OF ALL FILES THAT NEED TO BE LOADED
				compiled = concatLists(compiled, evidence);
			}
		}
		setAsActiveCase(cases[0]);
		//GET FILES FROM DATABASE THAT ARE NOT FORTIFIED
		f = new FormData();
		f.append('function', 'unfort');
		ajax('framework/functions.php', f, function(response){
			if(!response) return;
			var files = JSON.parse(response);
			for(var i=0; i<files.length; i++)
			{
				var obj = files[i];
				var cf = new Casefile(null, obj.uid);
				cf.filepath = obj.filepath;
				cf.filetype = obj.type;
				cf.name = obj.nickname;
				cf.officer = obj.user;
				cf.uploaddate = Number(obj.uploaddate);
				cf.filedate = Number(obj.lastmodified);
				cf.caseindex = tokenizeUID(obj.caseindex);
				cf.init();
				switch(cf.filetype)
				{
					case 'VIDEO':
						cf.thumbnail = 'framework/uploads/'+session.agency+'/'+session.user+'/thumbs/'+cf.uid+'.png';
						updateMedia();
						break;
					case 'IMAGE':
						cf.thumbnail = 'framework/'+cf.filepath;
						break;
					case 'AUDIO':
						cf.thumbnail = 'img/audioicon.png';
						break;
					case 'TEXT':
						cf.thumbnail = 'img/texticon.png';
						break;
					case 'DOCUMENT':
						cf.thumbnail = 'img/docicon.png';
						break;
					default: break;
				}
				updateMedia();
			}
		});
		//ONCE WE'RE DONE COMPILING THE EVIDENCE LIST, BEGIN LOADING ALL EVIDENCE
		len = compiled.length;
		for(var i=0; i<len; i++)
		{
			loading(1);
			f = new FormData();
			f.append('table', 'evidence');
			f.append('function', 'get');
			f.append('uid', compiled[i]);
			ajax('framework/functions.php', f, function(response) {
				var obj;
				try {
					obj = JSON.parse(response);
				} catch(e) {
					log('Could not retrieve Casefile data from server: '+response);
					loading(-1);
					return;
				}
				var cf = new Casefile(null, obj.uid);
				cf.filepath = obj.filepath;
				cf.filetype = obj.type;
				cf.name = obj.nickname;
				cf.officer = obj.user;
				cf.uploaddate = Number(obj.uploaddate);
				cf.filedate = Number(obj.lastmodified);
				cf.caseindex = tokenizeUID(obj.caseindex);
				cf.init();
				switch(cf.filetype)
				{
					case 'VIDEO':
						cf.thumbnail = 'framework/uploads/'+session.agency+'/'+session.user+'/thumbs/'+cf.uid+'.png';
						updateMedia();
						break;
					case 'IMAGE':
						cf.thumbnail = 'framework/'+cf.filepath;
						break;
					case 'AUDIO':
						cf.thumbnail = 'img/audioicon.png';
						break;
					case 'TEXT':
						cf.thumbnail = 'img/texticon.png';
						break;
					case 'DOCUMENT':
						cf.thumbnail = 'img/docicon.png';
						break;
					default: break;
				}

				var caselen = cf.caseindex.length;
				for(var j=0; j<caselen; j++)
				{
					var indx = getCaseById(cf.caseindex[j]);
					if(!indx)
					{
						log('Indx returned null: '+cf.caseindex[j]);
						continue;
					}
					indx.addFile(cf);
				}
				updateCases();
				updateMedia();
				updateReport();
				loading(-1);
			});
		}
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
	ajax('framework/getsession.php', null, function(response) {
		session = JSON.parse(response);
		getAPISupport();
		loadINI();
		getDatabase();
		setEventListeners();
		loading(-1);
	});
}
//GLOBAL VARIABLES (MAINLY FOR DEBUGGING)

const address = 'http://192.168.1.25/';

var USER;
$.ajax({
	url:'framework/getsession.php',
	method:'POST',
	processData:false,
	contentType:false,
	success: function(response){USER = JSON.parse(response);}
});

var databaseload = 0;

function loading(v)
{
	databaseload+=v;
	if(!databaseload) {$('#pageload-overlay').addClass('hidden'); log('Page Finished Loading')}
}

var workingcase;

var drag_enter_target;
var drag_leave_target;

const caselistElementID = '#case-list';
const filelistElementID = '#evidence-inventory';
const fileuploadElementID = '#fileupload';


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

function setEventListeners()
{
	$('#openfilebrowser').on('change', handleFileSelect);
	$(fileuploadElementID).on('click', openFileBrowser);
	$('#fortify-button').on('click', fortifyActiveCase);
	$('#new-case').on('click', newCase);
	$('#report-number').on('input', updateInfo);
	$('#report-nickname').on('input', updateInfo);
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
	$('#start-active').on('click', function(){
		workingcase.activetime = getUnixTime();
		notify('Case Active: '+workingcase.activetime);
	});

	//On Case change
	$('#report-number').on('input', changeCase);
	$('#report-nickname').on('input', changeCase);
	$('#report-location').on('input', changeCase);
	$('#report-tag').on('change', changeCase);
	$('#report-type').on('change', changeCase);
	$('#myonoffswitch').on('click', changeCase);
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

			});
			//ONCE WE'RE DONE COMPILING THE EVIDENCE LIST, BEGIN LOADING ALL EVIDENCE
			len = compiled.length;
			for(var i=0; i<len; i++)
			{
				f = new FormData();
				f.append('table', 'evidence');
				f.append('function', 'get');
				f.append('uid', compiled[i]);
				loading(1);
				ajax('framework/functions.php', f, function(response) {
					var file;
					try {
						file = JSON.parse(response);
					} catch(e) {
						log(response+' failed to load.');
						loading(-1);
						return;
					}
					var cf = new Casefile(file, file.uid);
					cf.caseindex = tokenizeUID(file.caseindex);
					cf.uploaddate = Number(file.uploaddate);
					cf.name = file.nickname;
					cf.officer = file.officer;
					switch(cf.filetype)
					{
						case 'VIDEO':
							cf.thumbnail = 'framework/thumbs/'+cf.uid+'.png';
							break;
						case 'IMAGE':
							cf.thumbnail = 'framework/uploads/'+cf.filepath;
							break;
						case 'DOCUMENT':
							cf.thumbnail = 'img/docicon.png';
							break;
						case 'TEXT':
							cf.thumbnail = 'img/texticon.png';
							break;
						case 'AUDIO':
							cf.thumbnail = 'img/audioicon.png';
							break;
					}

					var caselen = cf.caseindex.length;
					for(var j=0; j<caselen; j++)
					{
						var indx = getCaseById(cf.caseindex[j]);
						indx.addFile(cf);
					}
					updateCases();
					updateCaseFiles();
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
	$.ajax({
		method:'POST', url:'conf/agency.conf', dataType:'text', mimeType:'text/plain',
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
		}
	});
}

window.onload = function()
{
	loading(1);
	getAPISupport();
	getDatabase();
	setEventListeners();
	loadINI();
	loading(-1);
}
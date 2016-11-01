//GLOBAL VARIABLES (MAINLY FOR DEBUGGING)

const address = 'http://68.169.178.232/';
var USER = $_GET['user'];

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
		alert('Your internet browser does not support the necessary dependencies for this application.\nFortify CMS™ is recommended for use with the latest versions of Firefox and Chrome browsers.');
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
	$('#page-body').on('click', closeMediaBrowser);
	$('#video-player').on('click', clickHandler(href, 'video.php?view=test-video.mp4'));
}

function getDatabase()
{
	var f = new FormData();
	f.append('table', 'quickreport');
	f.append('function', 'get');
	f.append('officer', USER);
	loading(1);
	var compiled = [];
	$.ajax({
		url: 'framework/functions.php',
		method: 'POST',
		data: f,
		processData: false,
		contentType: false,
		success: function(response) {
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
				setAsActiveCase(cases[0]);
				//ONCE WE'RE DONE COMPILING THE EVIDENCE LIST, BEGIN LOADING ALL EVIDENCE
				len = compiled.length;
				for(var i=0; i<len; i++)
				{
					f = new FormData();
					f.append('table', 'evidence');
					f.append('function', 'get');
					f.append('uid', compiled[i]);
					loading(1);
					$.ajax({
						url: 'framework/functions.php',
						method: 'POST',
						data: f,
						processData: false,
						contentType: false,
						success: function(response) {
							var file = JSON.parse(response);
							var cf = new Casefile(file.filepath, file.type, file.uid);
							cf.caseindex = tokenizeUID(file.caseindex);
							cf.uploaddate = Number(file.uploaddate);
							cf.name = (file.nickname?file.filename:'');
							cf.officer = file.officer;
							switch(cf.filetype)
							{
								case 'VIDEO':
									cf.thumbnail = 'framework/thumbs/'+cf.uid+'.png';
									break;
								case 'IMAGE':
									cf.thumbnail = 'framework/uploads/'+cf.uid+'.'+getExtension(cf.filepath);
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
								indx.files.push(cf);
							}
							updateCases();
							updateCaseFiles();
							updateReport();
							loading(-1);
						}
					});
				}
			}
			else {newCase();}
			loading(-1);
		}
	});
}

window.onload = function()
{
	getAPISupport();
	getDatabase();
	generateTags();
	setEventListeners();
}
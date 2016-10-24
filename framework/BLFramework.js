//GLOBAL VARIABLES (MAINLY FOR DEBUGGING)

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
		cf = new Casefile(f);
		workingcase.addFile(cf);
	}
}

function log(msg) {console.log(msg);} //Outputs a message to the browser console

//File Upload functions

function postSQL(cf)
{
	var fdata = new FormData();
	fdata.append('uid', cf.uid);
	fdata.append('nickname', cf.name);
	fdata.append('file_path', cf.filepath);
	fdata.append('file_type', getFileType(cf.file.type));
	fdata.append('upload_date', cf.uploaddate);
	fdata.append('case_index', cf.caseindex.join('<#>'));

	$.ajax({
		url: 'framework/filepost.php',
		method: 'POST',
		data: fdata,
		processData: false,
		contentType: false,
		success: function(response) {
			log(response);
		}
	});
}

function postFiles(files) //Sends file and file data to PHP for processing and uploading.
{
	pushStack('postFiles');
	var len = files.length
	var formData = new FormData();
	for(var i=0; i<len; i++)
	{
		var cf = new Casefile(files[i])
		formData.append('file', files[i]);
		formData.append('ext', getExtension(files[i].name));
		formData.append('uid', cf.uid);
		
		$.ajax({
			url: 'framework/uploads.php',
			method: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				cf.filepath = response;
				getThumbnail(cf.uid, getExtension(cf.filepath), function(response){
					log("Trying to set thumbnail...");
					cf.thumbnail = cf.uid+'.png';
					cf.updateMediaElement();
					updateMedia();
				});
				/*var f = new FormData();
				f.append('function', 'capture');
				f.append('source', '../../uploads/'+cf.filepath);
				f.append('time', '00:00:00');
				f.append('output', '../../thumbs/'+cf.uid+'.png');

				$.ajax({
					url:'ffmpeg',
					method:'POST',
					data: f,
					processData: false,
					contentType: false,
					success: function(response) {
						log(response);
						cf.thumbnail = cf.uid+'.png';
						cf.updateMediaElement();
						updateMedia();
					}
				});*/
				postSQL(cf);
			}
		});
		addMedia(cf);
		//workingcase.addFile(cf);
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

function openFileBrowser() {
	document.getElementById('openfilebrowser').click();
} //Calls the file upload input which is hidden by default. This if to allow clicking of drop area to function as browse button.

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
	$('#nav-evidence').on('click', toggleMediaBrowser);
	$('#add-evidence').on('click', toggleMediaBrowser);
	$('#close-media-browser').on('click', closeMediaBrowser);
	$('#page-body').on('click', closeMediaBrowser);
}

window.onload = function()
{
	getAPISupport();
	newCase();
	generateTags();
	setEventListeners();
	if(SHOW_CALLSTACK) log('******* END OF INIT *******');
}
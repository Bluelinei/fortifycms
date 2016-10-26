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
		newCasefile(f);
	}
}

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
	fdata.append('state', cf.state);
	fdata.append('officer', 'Hue G. Tool');

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
	$('#report-tag').val('SELECT TAG');
	$('#myonoffswitch').checked = false;
	$('#myonoffswitch').on('click', toggleAdmin);
	$('#report-type').on('change', setCaseType);
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
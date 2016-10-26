//GLOBAL VARS
var cases = [];
var casefiles = [];
var reporttags = [];

const CASE = "case";
const CASEFILE = "casefile";

const UNFORT = "unfortified"; //File has not been assigned to a case
const INUSE = "inuse"; //File is assigned to the current working case
const UNUSED = "unused"; //File is assigned to a case, but not the current one

const CHANGE_STATE = true;

// *** GLOBAL FUNCTIONS ********************************************************************************************************

function postCases()
{
	var len = cases.length;
	for(var i=0; i<len; i++) {cases[i].postCase();}
}

function getCaseById(id)
{
	var c;
	for(c in cases) {if(c.uid == id) return c;}
	return null;
}

function getCasefileById(id)
{
	var file;
	for(file in casefiles) {if(file.uid == id) return file;}
	return null;
}

function onClick(file)
{
	pushStack('onClick');
	if(!workingcase) {popStack(); return;}
	if(file.state == INUSE) workingcase.removeFile(file);
	else workingcase.addFile(file);
	updateCases();
	updateCaseFiles();
	popStack();
}

function updateCases()
{
	pushStack('updateCases');
	var len = cases.length;
	for(var i=0; i<len; i++) {
		$(caselistElementID).append(cases[i].element);
	}
	popStack();
}

function updateCaseFiles()
{
	pushStack('updateCaseFiles');
	var len = casefiles.length;
	for(var i=0; i<len; i++) {casefiles[i].updateMediaElement();}
	popStack();
}

function display(node, element) {node.append(element);}

function newCase()
{
	pushStack('newCase');
	var f = new FormData();
	f.append('function', 'getuid');
	f.append('table', 'quickreport');
	$.ajax({
		url: 'framework/functions.php',
		method: 'POST',
		data: f,
		processData: false,
		contentType: false,
		success: function(uid) {
			var c = new Case(uid);
			setAsActiveCase(c);
		}
	});
	popStack();
}

function newCaseFile(uploadedfile)
{
	pushStack('newCase');
	var f = new FormData();
	f.append('function', 'getuid');
	f.append('table', 'evidence');
	$.ajax({
		url: 'framework/functions.php',
		method: 'POST',
		data: f,
		processData: false,
		contentType: false,
		success: function(uid) {
			var cf = new Casefile(uploadedfile, uid);
			var type = getFileType(uploadedfile.type);
			var ext = getExtension(uploadedfile.name);
			var formData = new FormData();
			formData.append('file', uploadedfile);
			formData.append('ext', ext);
			formData.append('uid', cf.uid);
			$.ajax({
				url: 'framework/uploads.php',
				method: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					cf.filepath = response;
					switch(type)
					{
						case 'VIDEO':
							getVideoThumbnail(cf.uid, getExtension(cf.filepath), function(response){
								cf.thumbnail = './framework/thumbs/'+cf.uid+'.png';
								cf.updateMediaElement();
								updateMedia();
							});
							break;
						case 'IMAGE':
							log('./framework/uploads/'+cf.uid+'.'+ext);
							cf.thumbnail = './framework/uploads/'+cf.uid+'.'+ext;
							cf.updateMediaElement();
							updateMedia();
							break;
						case 'AUDIO':
							cf.thumbnail = './img/audioicon.png';
							cf.updateMediaElement();
							updateMedia();
							break;
						case 'TEXT':
							cf.thumbnail = './img/texticon.png';
							cf.updateMediaElement();
							updateMedia();
							break;
						case 'DOCUMENT':
							cf.thumbnail = './img/docicon.png';
							cf.updateMediaElement();
							updateMedia();
							break;
						default: break;
					}
					postSQL(cf);
				}
			});
			addMedia(cf);
		}
	});
	popStack();
}

function setAsActiveCase(activecase)
{
	pushStack('setAsActiveCase');
	if(!activecase)
	{
		newCase();
		popStack();
		return;
	}
	if(workingcase == activecase || activecase.DELETED) {popStack(); return;}
	if(workingcase)
	{
		if(!$('#report-number').val()) workingcase.casenum = "[No Report Number]";
		var oldcase = workingcase;
		workingcase = null;
		oldcase.updateElement();
	}
	workingcase = activecase;
	workingcase.updateElement();
	updateCases();
	updateCaseFiles();
	updateReport();
	popStack();
}

function deleteCase(c)
{
	pushStack('deleteCase');
	var len = cases.length;
	for(var i=0; i<len; i++) {
		if(cases[i].uid == c.uid) 
		{
			c.element.detach();
			c.deleteCase();
			cases.splice(i,1);
			break;
		}
	}
	if(c.uid==workingcase.uid) {setAsActiveCase(cases[0]);}
	updateCases();
	updateCaseFiles();
	updateReport();
	popStack();
}

function fortifyActiveCase()
{
	pushStack('fortifyActiveCase');
	postCases();
	updateInfo();
	popStack();
}

function updateReport()
{
	pushStack('updateReport');
	$('#report-number').val('');
	$('#report-nickname').val('');
	$('#report-location').val('');
	if(workingcase.casenum == '[New Case]' || workingcase.casenum == '[No Report Number]') $('#report-number').val('');
	else $('#report-number').val(workingcase.casenum);
	if(workingcase.nickname) $('#report-nickname').val(workingcase.nickname);
	if(workingcase.location) $('#report-location').val(workingcase.location);
	document.getElementById('myonoffswitch').checked = workingcase.admin;
	$('#report-type').val(workingcase.type);
	updateFileList();
	updateTags();
	popStack();
}

function updateFileList()
{
	pushStack('updateFileList');
	var len;
	if(!workingcase) len=0;
	else len = workingcase.files.length;
	clearElement($(filelistElementID));
	for(var i=0; i<len; i++) {$(filelistElementID).append(workingcase.files[i].element);}
	popStack();
}

function updateInfo()
{
	pushStack('Case.updateInfo');
	if(!$('#report-number').val()) workingcase.casenum = "[No Report Number]";
	else workingcase.casenum = $('#report-number').val();
	if($('#report-nickname').val()) workingcase.nickname = $('#report-nickname').val();
	else workingcase.nickname = '';
	if($('#report-location').val()) workingcase.location = $('#report-location').val();
	else workingcase.location = '';
	workingcase.updateElement();
	updateCases();
	popStack();
}

function updateTags()
{
	pushStack('updateTags');
	hideTags();
	var len = workingcase.tags.length;
	var tlistlen = reporttags.length;
	for(var i=0; i<len; i++)
	{
		for(var x=0; x<tlistlen; x++)
		{
			if(workingcase.tags[i] == reporttags[x].name) 
			{
				reporttags[x].element.css('display','block');
				disableTag(reporttags[x].value);
			}
		}
	}
	popStack();
}

function resetTags()
{
	pushStack('resetTags');
	var rt = document.getElementById('report-tag');
	var len = rt.options.length;
	for(var i=0; i<len; i++)
	{
		if(rt.options[i].value=='SELECT TAG') continue;
		rt.options[i].disabled = false;
	}
	popStack();
}

function disableTag(tag)
{
	pushStack('disableTag');
	var rt = document.getElementById('report-tag');
	var len = rt.options.length;
	for(var i=0; i<len; i++)
	{
		if(rt.options[i].value == tag)
		{
			rt.options[i].disabled = true;
		}
	}
	popStack();
}

function generateTags()
{
	pushStack('generateTags');
	var rt = document.getElementById('report-tag');
	var len = rt.options.length;
	for(var i=0; i<len; i++)
	{
		var tag = new ReportTag(rt.options[i].text);
		tag.value = rt.options[i].value;
		$('#tag-list').append(tag.element);
	}
	popStack();
}

function setTag()
{
	pushStack('setTag');
	if($('#report-tag').val() == 'SELECT TAG')
	{
		popStack();
		return;
	}
	var len = workingcase.tags.length;
	for(var i=0; i<len; i++) //Check to see if the tag already exists in the workingcase. If so, just return.
	{
		if(workingcase.tags[i] == $('#report-tag').val())
		{
			$('#report-tag').val('SELECT TAG');
			popStack();
			return;
		}
	}
	workingcase.tags.push($('#report-tag').val())
	$('#report-tag').val('SELECT TAG');
	updateTags();
	popStack(); 
}

function removeTag(t)
{
	pushStack('removeTag');
	var len = workingcase.tags.length;
	for(var i=0; i<len; i++) {if(workingcase.tags[i] == t.name) {workingcase.tags.splice(i,1);}}
	updateTags();
	popStack();
}

function hideTags()
{
	pushStack('hideTags');
	var len = reporttags.length;
	resetTags();
	for(var i=0; i<len; i++)
	{
		reporttags[i].element.css('display','none');
	}
	popStack();
}

function setCaseType()
{
	pushStack('setCaseType');
	workingcase.type = $('#report-type').val();
	popStack();
}

function removeFileFromCase(file)
{
	pushStack('removeFileFromCase');
	workingcase.removeFile(file);
	updateCases();
	updateCaseFiles();
	updateReport();
	popStack();
}

function addMedia(file)
{
	pushStack('addMedia');
	$('#mediabrowser').append(file.mediaelement);
	updateMedia();
	popStack();
}

function updateMedia()
{
	pushStack('updateMedia');
	var len = casefiles.length;
	clearElement($('#mediabrowser'));
	for(var i=0; i<len; i++)
	{
		$('#mediabrowser').append(casefiles[i].mediaelement);
	}
	popStack();
}

function toggleAdmin()
{
	pushStack('toggleAdmin');
	if(workingcase.admin) workingcase.admin = false;
	else workingcase.admin = true;
	updateReport();
	popStack();
}






//*************************************************************************************************************************
//** CASE OBJECT **********************************************************************************************************
//*************************************************************************************************************************

function Case(uid)
{
	pushStack('Case');
	this.uid = uid;
	this.casenum = '[New Case]';
	this.nickname = '';
	this.location;
	this.files = [];
	this.tags = [];
	this.element;
	this.admin = false;
	this.type;
	this.DELETED = false;

	cases.push(this);
	this.newElement();
	updateCases();
	popStack();
}

Case.prototype.postCase = function() {
	var f = new FormData();
	f.append('uid',this.uid);
	f.append('nickname',this.nickname);
	f.append('reportnum',(this.casenum=='[No Report Number]'||this.casenum=='[New Case]'?'':this.casenum));
	f.append('reportloc',this.location);
	f.append('reporttype',this.type);
	f.append('reporttags',this.tags.join('<#>'));
	f.append('evidence',this.files.join('<#>'));
	f.append('admin',(this.admin?1:0));
	f.append('officer', 'Hue G. Tool')
	$.ajax({
		url:'framework/casepost.php',
		method: 'POST',
		data: f,
		processData: false,
		contentType: false,
		success: function(response) {
			log(response);
		}
	});
}

Case.prototype.newElement = function() {
	pushStack('Case.newElement');
	this.element = $('<li>');
	this.element.append('<div id="_case_text" class="case-ref-id seventy-per-wide ten-padding left point-cursor">'+ this.casenum + (this.nickname?' ('+truncateText(this.nickname, 13, '...', 0)+')':'')+'</div>');
	this.element.append('<div id="_case_filelen" class="case-file-count twenty-per-wide ten-padding left">'+ this.files.length +'</div>');
	this.element.append(this.newButton());
	this.element.append('<div class="clear"></div>');
	var c = this;
	this.element.on('click', function(event) {
		if(event.target.className.indexOf('case-delete-button-reference-class')==-1) setAsActiveCase(c);
	});
	popStack();
};

Case.prototype.updateElement = function() {
	pushStack('Case.updateElement');
	if(workingcase == this) this.element.addClass('active');
	else this.element.removeClass('active');
	this.updateHTML();
	popStack();
};

Case.prototype.updateHTML = function() {
	pushStack('Case.updateHTML');
	this.element.find('#_case_text').html(this.casenum + (this.nickname?' ('+truncateText(this.nickname, 13, '...')+')':''));
	this.element.find('#_case_filelen').html(this.files.length);
	popStack();
};

Case.prototype.addFile = function(file) {
	pushStack('Case.addFile');
	var len = this.files.length;
	for(var i=0; i<len; i++) {if(this.files[i].uid==file.uid) {popStack(); return 0;}}
	this.files.push(file);
	file.caseindex.push(this.uid);
	file.updateMediaElement();
	this.updateElement();
	updateFileList();
	popStack()
	return 1;
};

Case.prototype.removeFile = function(file) {
	pushStack('Case.removeFile');
	var len = this.files.length;
	for(var i=0; i<len; i++) {if(this.files[i]==file) {this.files.splice(i,1);}}
	len = file.caseindex.length;
	for(var i=0; i<len; i++) {if(file.caseindex[i]==this.uid) {file.caseindex.splice(i,1);}}
	file.updateMediaElement();
	this.updateElement();
	popStack()
};

Case.prototype.getFileList = function() {
	pushStack('getFileList');
	var l = [];
	var len = this.files.length;
	for(var i=0; i<len; i++)
	{
		l.push(this.files[i].file.name);
	}
	popStack();
	return l.join('<br>');
};

Case.prototype.newButton = function() {
	pushStack('Case.newButton');
	var src = this;
	var button = $('<div>');
	button.addClass(src.uid);
	button.addClass('pointer-mouse ten-per-wide ten-padding left red-light text-center link-button case-delete-button-reference-class case-delete-button');
	button.html('<i class="fa fa-times-circle-o case-delete-button-reference-class" style="font-size:14px;" aria-hidden="true"></i>');
	$(document).on('click', '.'+src.uid, function(event) {
		event.stopPropagation();
		deleteCase(src);
	});
	popStack()
	return button;
};

Case.prototype.deleteCase = function() {
	pushStack('Case.deleteCase');
	while(this.files.length)
	{
		this.removeFile(this.files[0]);
	}
	this.DELETED = true;
	popStack();
};






//*****************************************************************************************************************************
//** CASEFILE OBJECT **********************************************************************************************************
//*****************************************************************************************************************************

function Casefile(f, uid)
{
	pushStack('CaseFile');
	this.uid = uid;
	this.name;
	this.file = f;
	this.filepath;
	var date = new Date();
	this.uploaddate = date.getTime();
	this.element;
	this.mediaelement;
	this.thumbnail;
	this.state;
	this.caseindex = [];

	casefiles.push(this);
	this.newMediaElement();
	this.newElement();
	popStack();
}

Casefile.prototype.newElement = function() {
	pushStack('CaseFile.newElement');
	var d = new Date(this.uploaddate);
	this.element = $('<li>');
	this.element.addClass('casefile-element');
	this.element.append('<p class="left ten-padding bold">(' + getFileType(this.file.type) + ')</p>');
	this.element.append(this.addRemoveButton());
	this.element.append('<a href="view" class="view-icon link-button"><i class="fa fa-eye" aria-hidden="true"></i></a>');
	this.element.append('<p class="right ten-padding">'+ d.toLocaleDateString() + ' ' + d.toLocaleTimeString() +'</p>');
	this.element.append('<div class="clear"></div>');
	this.element.id = this.uid+"_case";
	this.element.click(clickHandler(onClick, this));
	popStack();
};

Casefile.prototype.newMediaElement = function() {
	pushStack('Casefile.newMediaElement');
	var d = new Date(this.uploaddate);
	this.mediaelement = $('<li>');
	var inner = $('<div class="block">');
	var e = [];
	e.push('<div class="ev-curtain" style="border:'+this.updateElement()+';"><div class="vertical-middle">');
	e.push('<h3>'+truncateText(this.file.name, 10, '...', 3)+'</h3>');
	e.push('<p>'+d.toLocaleDateString()+'</p><br>');
	e.push('<div style="display: inline;"><i class="fa fa-play-circle-o point-cursor" aria-hidden="true" style="margin-right: 10px;"></i></div>');
	e.push('<div style="display: inline;"><i class="fa fa-plus point-cursor" aria-hidden="true"></i></div>');
	e.push('</div></div>');
	inner.append(e.join(''));
	inner.css({'background-image': 'url("../img/loading.gif")',
		'background-repeat': 'no-repeat',
		'background-size': '50px 50px',
		'background-position': 'center'
	});
	this.mediaelement.append(inner);
	popStack();
}

Casefile.prototype.updateMediaElement = function(thumb) {
	pushStack('Casefile.updateMediaElement');
	var d = new Date(this.uploaddate);
	this.mediaelement = $('<li>');
	var inner = $('<div class="block" style="border:'+this.checkState()+';">');
	var e = [];
	e.push('<div class="ev-curtain"><div class="vertical-middle">');
	e.push('<h3>'+truncateText(this.file.name, 10, '...', 3)+'</h3>');
	e.push('<p>'+d.toLocaleDateString()+'</p><br>');
	e.push('<div style="display: inline;"><i class="fa fa-play-circle-o point-cursor" aria-hidden="true" style="margin-right: 10px;"></i></div>');
	e.push('<div style="display: inline;"><i class="fa fa-plus point-cursor" aria-hidden="true"></i></div>');
	e.push('</div></div>');
	inner.append(e.join(''));
	inner.css({'background-image': (this.thumbnail?'url('+this.thumbnail+')':'url("../img/docfile.png")'),
		'background-repeat': 'no-repeat',
		'background-size': (this.thumbnail?'cover':'100px 100px'),
		'background-position': 'center'
	});
	this.mediaelement.append(inner);
	popStack();
}

Casefile.prototype.display = function(node) {
	node.append(this.element);
};

Casefile.prototype.remove = function() {
	this.element.remove();
};

Casefile.prototype.checkState = function() {
	pushStack('CaseFile.checkState');
	var len = this.caseindex.length;
	this.state = UNUSED;
	if(!len) this.state = UNFORT;
	else
	{
		if(workingcase)
		{
			for(var i=0; i<len; i++)
			{
				if(this.caseindex[i] == workingcase.uid)
				{
					this.state = INUSE;
					break;
				}
			}
		}
		if(this.state != INUSE) this.state = UNUSED;
	};
	popStack();
	return this.updateElement();
};

Casefile.prototype.updateElement = function() {
	pushStack('CaseFile.updateElement');
	switch(this.state)
	{
		case UNFORT:
			return '5px solid red';
			break;
		case UNUSED:
			return 'none';
			break;
		case INUSE:
			return '5px solid green';
			break;
		default: break;
	}
	popStack();
};

Casefile.prototype.addRemoveButton = function() {
	pushStack('Casefile.addDeleteButton')
	var button = $('<div>');
	button.addClass('delete-icon link-button point-cursor');
	button.addClass(this.uid+"_removebutton");
	button.html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');
	$(document).on('click', '.'+this.uid+"_removebutton", clickHandler(removeFileFromCase, this));
	popStack();
	return button;
}




//*****************************************************************************************************************************
//** TAG OBJECT ***************************************************************************************************************
//*****************************************************************************************************************************

function ReportTag(n)
{
	pushStack('ReportTag');
	this.name = n;
	this.value;
	this.element;
	this.newElement();
	this.element.css('display', 'none');
	reporttags.push(this);
	popStack();
}

ReportTag.prototype.newElement = function() {
	pushStack('ReportTag.updateElement');
	this.element = $('<li>');
	this.element.append('<p class="left">'+this.name+'</p>');
	this.element.append(this.newButton());
	this.element.append('<div class="clear"></div>');
	popStack();
}

ReportTag.prototype.newButton = function() {
	pushStack('ReportTag.newButton');
	var button = $('<div>');
	button.addClass('fa fa-minus-circle right link-button point-cursor');
	button.on('click', clickHandler(removeTag, this));
	popStack();
	return button;
}
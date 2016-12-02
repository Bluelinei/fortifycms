//GLOBAL VARS
var cases = [];
var casefiles = [];
var reporttags = [];

const CASE = "case";
const CASEFILE = "casefile";

const UNFORT = "unfortified"; //File has not been assigned to a case
const INUSE = "inuse"; //File is assigned to the current working case
const UNUSED = "unused"; //File is assigned to a case, but not the current one

// *** GLOBAL FUNCTIONS ********************************************************************************************************

function postCases()
{
	var len = cases.length;
	var failed = 0;
	var noev = 0;
	for(var i=0; i<len; i++)
	{
		if(!cases[i].postCase()) {failed++; continue;}
		if(!cases[i].files.length) noev++;
	}
	if(cases.length-failed) notify((cases.length-failed)+((cases.length-failed)==1?' case':' cases')+' fortified');
	if(failed) notify(failed+(failed==1?' case':' cases')+' could not be fortified', WARN_NOTE);
	if(noev) notify(noev+(noev==1?' case':' cases')+' fortified without evidence', WARN_NOTE);
}

function getCaseById(id)
{
	var len = cases.length
	for(var i=0; i<len; i++) {if(cases[i].uid == id) return cases[i];}
	return null;
}

function getCasefileById(id)
{
	var len = casefiles.length;
	for(var i=0; i<len; i++) {if(casefiles[i].uid == id) return casefiles[i];}
	return null;
}

function updateCases()
{
	pushStack('updateCases');
	var len = cases.length;
	for(var i=0; i<len; i++) {
		cases[i].updateElement();
		$(caselistElementID).append(cases[i].element);
	}
	popStack();
}

function display(node, element) {node.append(element);}

function newCase()
{
	pushStack('newCase');
	var f = new FormData();
	f.append('function', 'caseuid');
	ajax('framework/functions.php', f, function(uid) {
		var c = new Case(uid);
		setAsActiveCase(c);
	});
	popStack();
}

var placeholderQueue = 0;

function Placeholder()
{
	this.queue = placeholderQueue++;
	this.element = $('<li>');
	this.element.attr({'id':'li_'+this.queue});
	this.element.addClass('block');
	var loadbar = $('<div class="block placeholder"><div class="loading-bar-wrapper"><div id="loadbar_'+this.queue+'" class="loading-bar"></div></div></div>');
	this.element.append(loadbar);
	$('#mediabrowser').append(this.element);
}

Placeholder.prototype.update = function(percent) {
	$('#loadbar_'+this.queue).css({'width':percent+'%'});
}

function newCaseFile(uploadedfile)
{
	pushStack('newCaseFile');
	var f = new FormData();
	f.append('file', uploadedfile);
	f.append('filetype', getFileType(uploadedfile.type));
	f.append('lastModified', getUnixTime(uploadedfile.lastModified));

	var loadingPlace;
	
	//Check if the file already exists server side, if so, give it a UID and upload a new file. If not, return the uid of the object on the server.
	$.ajax({
		xhr: function() {
			var xhr = new window.XMLHttpRequest();
			//xhr loadstart and load functions
			xhr.upload.addEventListener('loadstart', function(e) {
				loadingPlace = new Placeholder();
			}, false);
			xhr.upload.addEventListener('progress', function(e) {
				if(e.lengthComputable) {
					var percent = Math.floor((e.loaded/e.total)*100);
					loadingPlace.update(percent);
				}
			}, false);
			return xhr;
		},
		url: 'framework/fileupload.php', method: 'POST', data: f, processData: false, contentType: false,
		success: function(response){
			//log('New Upload: '+response);
			var obj = JSON.parse(response);
			for(var i=0; i<casefiles.length; i++)
			{
				if(casefiles[i].uid == obj.uid)
				{
					$('#li_'+loadingPlace.queue).remove();
					notify('File '+uploadedfile.name+' is already open.', WARN_NOTE);
					return;
				}
			}
			//uid filepath uploaddate lastmodified nickname type user checksum
			var cf = new Casefile(uploadedfile, obj.uid);
			cf.filepath = obj.filepath;
			cf.nickname = uploadedfile.name;
			cf.officer = obj.user;
			cf.uploaddate = Number(obj.uploaddate);
			cf.init();
			$('#li_'+loadingPlace.queue).replaceWith(cf.mediaelement);
			$('#li_'+loadingPlace.queue).remove();
			switch(cf.filetype)
			{
				case 'VIDEO':
					getVideoThumbnail(cf, function(response){
						cf.thumbnail = 'framework/'+response;
						cf.updateMediaElement();
						updateMedia();
					});
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
			//Check for prelinks
			for(var i=0; i<cases.length; i++)
			{
				if(!cases[i].prelinkenable) continue;
				if(cf.filedate>cases[i].prelinkstart&&cf.filedate<cases[i].prelinkend)
				{
					cases[i].addFile(cf);
				}
			}
			cf.updateMediaElement();
			updateMedia();
			cf.postFile();
		}
	});

	popStack();
}

function setAsActiveCase(activecase)
{
	pushStack('setAsActiveCase');
	if(!activecase||activecase==undefined)
	{
		newCase();
		popStack();
		return;
	}
	if(workingcase == activecase || activecase.DELETED) {popStack(); return;}
	if(workingcase)
	{
		var oldcase = workingcase;
		workingcase = null;
		oldcase.updateElement();
	}
	workingcase = activecase;
	workingcase.updateElement();
	updateCases();
	updateMedia();
	updateReport();
	popStack();
}

function addFileToCase(file)
{
	pushStack('addFile');
	if(file.state == INUSE) workingcase.removeFile(file)
	else workingcase.addFile(file);
	updateCases();
	updateMedia();
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
	updateMedia();
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
	if(workingcase.casenum) $('#report-number').val(workingcase.casenum);
	else $('#report-number').val('');
	if(workingcase.location) $('#report-location').val(workingcase.location);
	else $('#report-location').val('');
	document.getElementById('myonoffswitch').checked = workingcase.admin;
	$('#report-type').val(workingcase.type);
	if(workingcase.prelinkenable) $('.prelink-toggle-text').html('Disable Pre-Link');
	else $('.prelink-toggle-text').html('Enable Pre-Link');
	updateFileList();
	updateTags();
	updateMedia();
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
	if($('#report-number').val()) workingcase.casenum = $('#report-number').val();
	else workingcase.casenum = '';
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
	workingcase.changed = true;
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
	updateMedia();
	updateReport();
	popStack();
}

function addMedia(file)
{
	pushStack('addMedia');
	if(file.state != UNUSED) $('#mediabrowser').append(file.mediaelement);
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
		casefiles[i].checkState();
		if(casefiles[i].state == UNUSED) continue;
		$('#mediabrowser').append(casefiles[i].mediaelement);
		casefiles[i].updateMediaElement();
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

function clearPreview()
{
	$('.media-preview-overlay').addClass('hidden');
	$('video').trigger('pause');
	$('.media-preview-overlay').empty();
}






//*************************************************************************************************************************
//** CASE OBJECT **********************************************************************************************************
//*************************************************************************************************************************

function Case(uid)
{
	pushStack('Case');
	this.uid = uid;
	this.casenum = '';
	this.nickname = '';
	this.location = '';
	this.files = [];
	this.tags = [];
	this.element;
	this.admin = false;
	this.type = 'No Report';
	this.officer;
	this.changed = false;
	this.DELETED = false;
	this.editnick = false;
	this.prelinkstart;
	this.prelinkend;
	this.prelinkenable = false;

	cases.push(this);
	this.newElement();
	updateCases();
	popStack();
}

Case.prototype.changeCase = function(x) {
	this.changed = x;
	if(this.changed) this.element.addClass('changed');
	else this.element.removeClass('changed');
}

Case.prototype.postCase = function() {
	pushStack('Case.postCase');
	if(this.casenum==''||!this.type) {popStack(); return false;}
	var f = new FormData();
	f.append('uid',this.uid);
	f.append('nickname',this.nickname);
	f.append('reportnum',this.casenum);
	f.append('reportloc', this.location);
	f.append('reporttype',this.type);
	f.append('reporttags',this.tags.join('<#>'));
	var filelist = []
	var len = this.files.length;
	for(var i=0; i<len; i++) {filelist.push(this.files[i].uid);}
	for(var i=0; i<len; i++) {this.files[i].postFile();}
	f.append('evidence',filelist.join(''));
	f.append('admin',(this.admin?1:0));
	ajax('framework/casepost.php', f, function(response) {
			//log(response);
	});
	this.changeCase(false);
	popStack();
	return true;
}

Case.prototype.newElement = function() {
	pushStack('Case.newElement');
	var src = this;
	this.element = $('<li>');
	this.element.append('<div class="case-ref-id-wrapper seventy-per-wide ten-padding left point-cursor '+this.uid+'_case_text"><div class="case-ref-id">[No Case ID]</div></div>');
	this.element.append('<div class="case-file-count twenty-per-wide ten-padding left _case_filelen">'+ this.files.length +'</div>');
	this.element.append('<div class="pointer-mouse ten-per-wide left red-light text-center link-button case-delete-button-reference-class case-delete-button '+this.uid+'" style="padding: 8px"><i class="fa fa-minus-circle case-delete-button-reference-class" style="font-size:19px; color:#fff;" aria-hidden="true"></i></div>');
	this.element.append('<div class="clear"></div>');
	var c = this;
	this.element.on('click', function(event) {
		if(event.target.className.indexOf('case-delete-button-reference-class')==-1) setAsActiveCase(c);
	});
	$(document).on('click', ('.'+c.uid), function(event) {
		event.stopPropagation();
		deleteCase(src);
	});
	this.element.find('.'+c.uid+'_case_text').on('click', function(e) {
		if(c.editnick) return;
		if(workingcase == c)
		{
			e.stopPropagation();
			c.editnick = true;
			var ct = c.element.find('.'+c.uid+'_case_text');
			ct.html('<input id="'+c.uid+'_nickin" type="text" class="case-nickname-input" value="'+c.nickname+'"/>');
			$('#'+c.uid+'_nickin').focus();
			$('#'+c.uid+'_nickin').select();
			$('#'+c.uid+'_nickin').on('blur', function(e) {
				c.nickname = $('#'+c.uid+'_nickin').val();
				c.editnick = false;
				updateCases();
			});
		}
	});
	popStack();
};

Case.prototype.updateElement = function() {
	pushStack('Case.updateElement');
	var casetext = this.element.find('.'+this.uid+'_case_text');
	if(workingcase == this)
	{
		this.element.addClass('active');
		casetext.css({'cursor':'text'});
	}
	else
	{
		this.element.removeClass('active');
		casetext.css({'cursor':'inherit'});
	}
	this.updateHTML();
	popStack();
};

Case.prototype.updateHTML = function() {
	pushStack('Case.updateHTML');
	if(!this.editnick)
	{
		if(this.nickname) this.element.find('.'+this.uid+'_case_text').html('<div class="case-ref-id">'+this.nickname+'</div>');
		else if(this.casenum) this.element.find('.'+this.uid+'_case_text').html('<div class="case-ref-id">'+this.casenum+'</div>');
		else this.element.find('.'+this.uid+'_case_text').html('<div class="case-ref-id">[No Case ID]</div>');
	}
	this.element.find('._case_filelen').html(this.files.length);
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
	popStack();
	return 1;
};

Case.prototype.removeFile = function(file) {
	pushStack('Case.removeFile');
	var len = this.files.length;
	for(var i=0; i<len; i++) {if(this.files[i]==file) {this.files.splice(i,1); i--;}}
	len = file.caseindex.length;
	for(var i=0; i<len; i++) {if(file.caseindex[i]==this.uid) {file.caseindex.splice(i,1); i--;}}
	file.updateMediaElement();
	this.updateElement();
	updateFileList();
	popStack();
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

Case.prototype.deleteCase = function() {
	pushStack('Case.deleteCase');
	while(this.files.length) {this.removeFile(this.files[0]);}
	this.DELETED = true;
	popStack();
};






//*****************************************************************************************************************************
//** CASEFILE OBJECT **********************************************************************************************************
//*****************************************************************************************************************************

function Casefile(file, uid)
{
	pushStack('CaseFile');
	this.uid = uid;
	this.name = (file?file.name:'');
	this.filetype = (file?getFileType(file.type):'');
	this.filepath = '';
	this.filedate = getUnixTime((file?file.lastModified:''));
	this.uploaddate = 0;
	this.lastmodified = 0;
	this.element;
	this.mediaelement;
	this.thumbnail;
	this.state;
	this.officer;
	this.caseindex = [];
	popStack();
}

Casefile.prototype.init = function()
{
	casefiles.push(this);
	this.newMediaElement();
	this.newElement();
	this.setButtonFunction();
}

Casefile.prototype.postFile = function() {
	pushStack('Casefile.postFile');
	var fdata = new FormData();
	fdata.append('uid', this.uid);
	fdata.append('nickname', (this.name?this.name:''));
	fdata.append('file_path', this.filepath);
	fdata.append('file_type', this.filetype);
	fdata.append('upload_date', this.uploaddate);
	fdata.append('lastmodified', this.filedate);
	fdata.append('case_index', removeDuplicates(this.caseindex).join(''));
	fdata.append('fortified', (this.caseindex.length?1:0));
	log(this.name+' | '+this.caseindex.length);

	ajax('framework/filepost.php', fdata, function(response) {
			//log(response);
	});
	popStack()
}

Casefile.prototype.truncName = function(y, n){
	return (this.name?truncateText(this.name, y, '...'):truncateText(this.filepath, n, '...', getExtension(this.filepath).length));
}

Casefile.prototype.newElement = function() {
	pushStack('CaseFile.newElement');
	this.element = $('<li>');
	this.element.addClass('casefile-element');
	this.element.append('<p class="left ten-padding bold">'+this.filetype+'</p>');
	this.element.append('<div class="delete-icon link-button point-cursor '+this.uid+'_removebutton"><i class="fa fa-minus-circle" aria-hidden="true"></i></div>');
	this.element.append('<div class="view-icon link-button point-cursor '+this.uid+'_view-button"><i class="fa fa-eye" aria-hidden="true"></i></div>');
	this.element.append('<p class="right ten-padding">'+ new Date(this.filedate*1000).toLocaleDateString() + '</p>');
	this.element.append('<div class="clear"></div>');
	this.element.id = this.uid+"_case";
	popStack();
};

Casefile.prototype.newMediaElement = function() {
	pushStack('Casefile.newMediaElement');
	var d = new Date(this.filedate*1000);
	this.mediaelement = $('<li>');
	var inner = $('<div id="'+this.uid+'_blockelement" class="block">');
	this.checkState();
	var e = [];
	e.push('<div class="ev-curtain"><div class="vertical-middle">');
	e.push('<h3 id="'+this.uid+'_name">'+this.truncName(15, 12)+'</h3>');
	e.push('<p>'+d.toLocaleDateString()+'</p><br>');
	e.push('<div class="'+this.uid+'_view-button" style="display: inline;"><i class="fa fa-eye point-cursor" aria-hidden="true" style="margin-right: 30px;"></i></div>');
	e.push('<div id="'+this.uid+'_addremove" style="display: inline;"><i class="fa '+this.isInclude()+' point-cursor '+this.uid+'_addfilebutton" aria-hidden="true"></i></div>');
	e.push('</div></div>');
	inner.append(e.join(''));
	inner.css({'background-image': 'url("'+address+'/img/loading.gif")',
		'background-repeat': 'no-repeat',
		'background-size': '50px 50px',
		'background-position': 'center'
	});
	this.mediaelement.append(inner);
	popStack();
}

Casefile.prototype.updateMediaElement = function(thumb) {
	pushStack('Casefile.updateMediaElement');
	this.checkState();
	$('#'+this.uid+"_name").html(this.truncName(15,12));
	$('#'+this.uid+"_addremove").html('<i class="fa '+this.isInclude()+' point-cursor '+this.uid+'_addfilebutton" aria-hidden="true"></i>');
	$('#'+this.uid+"_blockelement").removeClass('green-border');
	if(this.state==INUSE) $('#'+this.uid+"_blockelement").addClass('green-border');
	this.updateThumb();
	popStack();
}

Casefile.prototype.display = function(node) {
	node.append(this.element);
};

Casefile.prototype.remove = function() {
	this.element.remove();
};

Casefile.prototype.isInclude = function() {
	if(this.state==INUSE) return 'fa-minus-circle media-remove-icon';
	else return 'fa-plus-circle media-add-icon';
}

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
	return;
};

Casefile.prototype.doHide = function() {
	if(this.state == UNUSED) return 'display: none;';
	else return '';
}

Casefile.prototype.setButtonFunction = function() {
	var ref = this;
	$(document).on('click', '.'+this.uid+'_addfilebutton', clickHandler(addFileToCase, this));
	$(document).on('click', '.'+this.uid+"_removebutton", clickHandler(removeFileFromCase, this));
	if(this.filetype == 'VIDEO')
	{
		$(document).on('mouseenter', '.'+ref.uid+'_view-button', function(e){
			clearPreview();
			$('.media-preview-overlay').append('<video id="overlay-video" style="position: absolute; top:0%; right:0%; max-width: 100%; max-height: 100%;" autoplay></video>');
			$('#overlay-video').html('<source src="framework/'+ref.filepath+'" type="video/mp4"/>');
			$('#overlay-video').get(0).oncanplay = function(){
				var pos = getOverlayPosition(e);
				$('.media-preview-overlay').css({top: pos.y, left: pos.x});
				$('.media-preview-overlay').removeClass('hidden');
			};
		});
	}
	else if(this.filetype == 'IMAGE')
	{
		$(document).on('mouseenter', '.'+this.uid+'_view-button', function(e){
			clearPreview();
			$('.media-preview-overlay').append('<img id="overlay-image" src="'+ref.thumbnail+'" style="position: absolute; top:0%; right:0%; max-width: 100%; max-height: 100%;" />');
			var pos = getOverlayPosition(e);
			$('.media-preview-overlay').css({top: pos.y, left: pos.x});
			$('.media-preview-overlay').removeClass('hidden');
		});
	}
	$(document).on('mouseleave', '.'+this.uid+'_view-button', clearPreview);
	$(document).on('mousemove', '.'+this.uid+'_view-button', function(e){
		var pos = getOverlayPosition(e);
		$('.media-preview-overlay').css({top: pos.y, left: pos.x});
	});
}

Casefile.prototype.updateThumb = function() {
	$("#"+this.uid+"_blockelement").css({'background-image': (this.thumbnail ? ('url('+address+this.thumbnail+')') : ('url("'+address+'/img/loading.gif")')),
		'background-repeat': 'no-repeat',
		'background-size': (this.thumbnail?'cover':'50px 50px'),
		'background-position': 'center'
	});
}

function getOverlayPosition(e)
{
	var pos = {'x':'','y':''};
	var o = $('.media-preview-overlay');
	var w = $(window);
	if(e.clientX < (w.width()/2)) pos.x = e.clientX+10;
	else pos.x = e.clientX-(o.width()+10);
	if(e.clientY < (w.height()/2)) pos.y = e.clientY+10;
	else pos.y = e.clientY-(o.height()+10);

	if(pos.x > (w.width()-o.width())) pos.x = w.width()-(o.width()-10);
	if(pos.x < 0) pos.x = 10;
	if(pos.y > (w.height()-o.height())) pos.x = w.height()-(o.height()-10);
	if(pos.y < 0) pos.y = 10;

	return pos;
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
	this.element.addClass('point-cursor');
	this.element.append('<p class="left">'+this.name+'</p>');
	this.element.append('<div class="fa fa-minus-circle right link-button"></div>');
	this.element.append('<div class="clear"></div>');
	this.element.on('click', clickHandler(removeTag, this));
	popStack();
}

ReportTag.prototype.newButton = function() {
	pushStack('ReportTag.newButton');
	var button = $('<div>');
	button.addClass('<div class="fa fa-minus-circle right link-button point-cursor"></div>');
	button.on('click', clickHandler(removeTag, this));
	popStack();
	return button;
}







//*********************************************************************************************************************************
//** PRELINK OBJECT ***************************************************************************************************************
//*********************************************************************************************************************************

function Prelink()
{
	this.editing;
	this.year;
	this.month;
	this.day;
	this.hour;
	this.minute;
	this.meridiem;
}

Prelink.prototype.setTime = function() {
	var d = new Date();
	this.year = d.getFullYear();
	this.month = d.getMonth();
	this.day = d.getDate();
	this.hour = Number($('.hour-num').val());
	this.minute	= Number($('.minute-num').val());
	this.meridiem = $('.meridiem').html();
	if(this.meridiem=='AM'&&this.hour==12) {this.hour = 0;}
	else if(this.meridiem=='PM'&&this.hour!=12) {this.hour += 12;}

	var time = new Date(this.year, this.month, this.day, this.hour, this.minute);

	if(this.editing=='start')
	{
		workingcase.prelinkstart = getUnixTime(time.valueOf());
		$('.prelink-start').html('<p>Start Time<br>'+time.toLocaleString()+'</p>');
	}
	else if(this.editing=='end')
	{
		workingcase.prelinkend = getUnixTime(time.valueOf());
		$('.prelink-end').html('<p>End Time<br>'+time.toLocaleString()+'</p>');
	}
}

Prelink.prototype.edit = function(time) {
	this.editing = time;
}

Prelink.prototype.enable = function() {
	if(workingcase.prelinkenable)
	{
		workingcase.prelinkenable = false;
		$('.prelink-toggle-text').html('Enable Pre-Link');
		notify('Pre-Link Disabled');
	}
	else
	{
		workingcase.prelinkenable = true;
		$('.prelink-toggle-text').html('Disable Pre-Link');
		notify('Pre-Link Enabled');
	}
}
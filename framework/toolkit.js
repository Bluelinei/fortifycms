
var SHOW_CALLSTACK = false;

function log(msg) {console.log(msg);} //Outputs a message to the browser console

function rand(n, add=1) {return Math.floor((Math.random()*n)+add);}

function idExists(sel)
{
	var status = false;
	if($('#'+sel).length) status = true;
	return status;
}

function getHex(n=4)
{
	var hextable = ["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F"];
	var hex = [];
	while(n)
	{
		hex.push(hextable[rand(16)-1]);
		n--;
	}
	return hex.join('');
}

function getCaseNum(n=10)
{
	var cn = [];
	while(n)
	{
		cn.push(rand(10,0));
		n--;
	}
	return cn.join('');
}

function deleteElement(e) {e.parentNode.removeChild(e);}

function moveElement(id, nodeid, append=true)
{
	var e = document.getElementById(id);
	var node = document.getElementById(nodeid);
	if(append) node.appendChild(e);
	else node.insertBefore(e);
}

function copyElement(element) {return element.clone(true);}

function copyElementById(id)
{
	var element = $('#'+id);
	return element.clone(true);
}

function clickHandler(func, arg) {
	return function() {func(arg);};
}

function classFunctionHandler(obj, func, arg) {return function() {obj.func(arg);};}

var callstack = [];

function pushStack(func)
{
	callstack.push(func);
	if(SHOW_CALLSTACK) log(callstack.join(' >> '));
}

function popStack()
{
	callstack.pop();
	if(SHOW_CALLSTACK) log(callstack.join(' >> '));
}

function getFileType(file)
{
	if(!file.indexOf('video')) return 'VIDEO';
	else if(!file.indexOf('audio')) return 'AUDIO';
	else if(!file.indexOf('text')) return 'TEXT';
	else if(!file.indexOf('image')) return 'IMAGE';
	else return 'DOCUMENT';
}

function getExtension(string) {return string.substring(string.lastIndexOf('.')+1);}

function clearElement(node) {node.html('');}

function tokenize(string, delim)
{
	var tokens = [];
	var pos;
	while(string)
	{
		pos = string.indexOf(delim);
		if(pos==-1)
		{
			tokens.push(string);
			break;
		}
		tokens.push(string.substr(0, pos));
		string = string.substr(pos+delim.length);
	}
	return tokens;
}

function tokenizeUID(string)
{
	var uids = [];
	while(string)
	{
		uids.push(string.substr(0,16));
		string = string.substr(16);
	}
	return uids;
}

function truncateText(str, len, filler, end=0)
{
	if(str.length > len+filler.length+end)
	{
		var sub = '';
		if(end) sub = str.substring(str.length-end);
		str = str.substring(0, len);
		str += filler;
		str += sub;
	}
	return str;
}

function getVideoThumbnail(filename, ext, callback)
{
	pushStack('getThumbnail');
	var f = new FormData();
	var output;
	f.append('function', 'capture');
	f.append('source', '.\\uploads\\'+filename+'.'+ext);
	f.append('time','00:00:00');
	f.append('output','.\\thumbs\\'+filename+'.png');
	f.append('dir', '.\\thumbs\\');

	$.ajax({
		url:'./framework/ffmpeg.php',
		method:'POST',
		data: f,
		processData: false,
		contentType: false,
		success: function(response) {
			log(response);
			callback(response);
		}
	});
	popStack();
}

function inList(list, item)
{
	var len = list.length;
	for(var i=0; i<len; i++) {if(list[i]==item) return true;}
	return false;
}

function concatLists(list, items)
{
	var concat = list;
	var len = items.length;
	for(var i=0; i<len; i++)
	{
		if(!inList(list, items[i])) concat.push(items[i]);
	}
	return concat;
}

function href(url) {window.location.href = url;}

function redirect(url) {window.location.replace(url);}
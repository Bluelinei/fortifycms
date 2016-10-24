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
var SHOW_CALLSTACK = false;

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
	if(!file.indexOf('video')) return 'VIDEO FILE';
	else if(!file.indexOf('audio')) return 'AUDIO FILE';
	else if(!file.indexOf('text')) return 'TEXT FILE';
	else if(!file.indexOf('image')) return 'IMAGE FILE';
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
		tokens.push(string.substr(1, pos));
		string = string.substr(pos+delim.length);
	}
	return tokens;
}

function truncateText(str, len, filler)
{
	if(str.length > len)
	{
		str = str.substr(0, len);
		str += filler;
	}
	return str;
}
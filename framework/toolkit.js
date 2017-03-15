
const SHOW_LOGS = true;
const SERVER_ADDRESS = 'https://68.169.178.232/fortify/';

function log(msg) {if(SHOW_LOGS) console.log(msg);} //Outputs a message to the browser console

class Toolkit {
	ajax(phpurl, f=null, func=null, errfunc=null) {
		$.ajax({
			url: phpurl, data: f, method: 'POST', processData: false, contentType: false,
			success: function(response) {
				if(func) func(response);
			},
			error: function(response) {
				if(errfunc) errfunc(response);
			}
		});
	}

	clearElement(node) {node.html('');} //Removes all HTML from a given element

	clickHandler(func, arg) //Allowed the use of 'this' reference when using lambda functions
	{
		return function() {func(arg);};
	}

	concatLists(list, items) //concatenates two lists into one, removing duplicates
	{
		var concat = list;
		var len = items.length;
		for(var i=0; i<len; i++)
		{
			if(!this.inList(list, items[i])) concat.push(items[i]);
		}
		return concat;
	}

	excludeLists(list, exclude)
	{
		var exclusives = list;
		var len = exclude.length;
		for(var i=0; i<len; i++)
		{
			if(this.inList(exclusives, exclude[i])) removeFromList(exclusives, excludep[i]);
		}
		return exclusives;
	}

	getExtension(string) //Get the extension of the supplied file path
	{
		if(!string) return false;
		return string.substring(string.lastIndexOf('.')+1).toLowerCase();
	}

	getFileType(file) //Gets the file type based on supplied MIME type.
	{
		if(!file.indexOf('video')) return 'VIDEO';
		else if(!file.indexOf('audio')) return 'AUDIO';
		else if(!file.indexOf('text')) return 'TEXT';
		else if(!file.indexOf('image')) return 'IMAGE';
		else return 'DOCUMENT';
	}

	getUnixTime(time) //Return the number of seconds since the Unix Epoch
	{
		if(time) return Math.floor(time/1000);
		else return Math.floor(Date.now()/1000);
	}

	getURIVar(variable) //Split up and serialize all variables in the page URI
	{
		var query = window.location.search.substring(1);
		var vars = query.split('&');

		for(var i=0; i<vars.length; i++)
		{
			var pair = vars[i].split('=');
			if(pair[0]==variable) return pair[1];
		}
		return false;
	}

	getVideoThumbnail(file, callback) //Extract the first frame of a video as a PNG and return the response as a filepath.
	{
		pushStack('getThumbnail');
		var f = new FormData();
		var output;
		f.append('function', 'capture');
		f.append('source', file.filepath);
		f.append('time','00:00:00');
		f.append('output','thumbs/'+file.uid+'.png');
		f.append('dir', 'thumbs/');

		$.ajax({
			url:'./framework/ffmpeg.php',
			method:'POST',
			data: f,
			processData: false,
			contentType: false,
			success: function(response) {
				callback(response);
			}
		});
		popStack();
	}

	href(url) {window.location.href = url;}

	idExists(sel)
	{
		var status = false;
		if($('#'+sel).length) status = true;
		return status;
	}

	inList(list, item)
	{
		var len = list.length;
		for(var i=0; i<len; i++) {if(list[i]==item) return true;}
		return false;
	}

	login(user, pass, agency)
	{
		var f = new FormData();
		f.append('func', 'login');
		f.append('user', user);
		f.append('pass', pass);
		f.append('agentid', agency);
		$.ajax({
			url: 'framework/login.php',
			method: 'POST',
			data: f,
			processData: false,
			contentType: false,
			success: function(response) {
				log(response);
				if(response=='login') this.href(SERVER_ADDRESS+'casebuilder.php');
				else if(response=='2fa')
				{
					this.href('loginauth.php');
				}
				else
				{
					loginNotify('Invalid login credentials');
					document.getElementById('pass').value = '';
				}
			},
		});
	}

	logout()
	{
		var f = new FormData();
		f.append('func', 'logout');
		$.ajax({
			url: 'framework/login.php',
			method: 'POST',
			data: f,
			processData: false,
			contentType: false,
			success: function(response) {
				log(response);
				this.href(SERVER_ADDRESS+'login.php');
			}
		});
	}

	moveElement(id, nodeid, append=true)
	{
		var e = document.getElementById(id);
		var node = document.getElementById(nodeid);
		if(append) node.appendChild(e);
		else node.insertBefore(e);
	}

	rand(n, add=1) {return Math.floor((Math.random()*n)+add);}

	redirect(url) {window.location.replace(url);}

	removeDuplicates(list)
	{
		for(var i=0; i<list.length; i++)
		{
			for(var j=i+1; j<list.length; j++)
			{
				if(list[i]==list[j])
				{
					list.splice(j,1);
					j--;
				}
			}
		}
		return list;
	}

	removeFromList(list, item) {for(var i=0; i<list.length; i++) {if(list[i] == item) {list.splice(i,1); i--;}}}

	tokenize(string, delim)
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

	tokenizeUID(string)
	{
		var uids = [];
		while(string)
		{
			uids.push(string.substr(0,16));
			string = string.substr(16);
		}
		return uids;
	}

	truncateText(str, len, filler, end=0)
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
}

var Tool = new Toolkit();
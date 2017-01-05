<!DOCTYPE html>
<html>
<head>
	<title>Fortify Bash</title>
	<script src="http://code.jquery.com/jquery-3.1.1.js"></script>
	<script src="../framework/toolkit.js"></script>
	<style>
		body {
			background: #aaa;
		}
		.content-wrapper {
			position: fixed;
			background: #222;
			font-family: "Lucida Console", Monaco, monospace;
			color: #fff;			
			width: 80%;
			height: 80%;
			top: 50%;
			left: 50%;
			transform: translate(-50%,-50%);
			margin: 0px;
		}
		.output {
			box-sizing: border-box;
			position: absolute;
			overflow-y: auto;
			font-family: "Lucida Console", Monaco, monospace;
			color: #fff;
			font-size: 16px;
			padding: 10px;
			top:0%;
			width: 100%;
			max-height: calc(100% - 50px);
		}
		.cmd {
			position: absolute;
			font-family: "Lucida Console", Monaco, monospace;
			color: #8f8;
			box-sizing: border-box;
			text-decoration: bold;
			width: 100%;
			height: 50px;
			padding-left: 20px;
			padding-right: 20px;
			padding-top: 10px;
			padding-bottom: 10px;
			border-top: 2px solid white;
			bottom: 0%;
			background: #222;
			font-size: 20px;
		}
		.indent-1 {
			padding-left: 30px;
		}
		.indent-2 {
			padding-left: 60px;
		}
		.indent-3 {
			padding-left: 90px;
		}
		input {
			border: none;
		}
	</style>
</head>
<body>
	<div class="content-wrapper"> <!-- WRAPPER -->
		<div class="output"> <!-- OUTPUT -->
		</div>
		<input class="cmd" type="text"> <!-- COMMAND LINE -->
		</input>
	</div>
</body>
<script>
	var variables = {};
	var macro = {};
	var buffer = [];
	var buffer_it = -1;
	var return_value = {};
	var return_mutex = false;
	function LOCK_MUTEX() {return_mutex = true;}
	function UNLOCK_MUTEX() {return_mutex = false;}
	function runScript(script,iter)
	{
		if(return_mutex)
		{
			setTimeout(function(){
				runScript(script, iter);
			},50);
		}
		else
		{
			command(script[iter]);
			iter++;
			if(iter>=script.length) return;
			else runScript(script, iter);
		}
	}
	function pushToBuffer(text)
	{
		buffer.unshift(text);
		while(buffer.length>20) {buffer.pop();}
		buffer_it = -1;
	}
	function shift_buffer(dir)
	{
		buffer_it += dir;
		if(buffer_it<=-1)
		{
			buffer_it=-1;
			$('.cmd').val('');
		}
		else
		{
			if(buffer_it>buffer.length-1) {buffer_it=buffer.length-1;}
			$('.cmd').val(buffer[buffer_it]);
		}
	}
	function tokenize(string, delim)
	{
		var tokens = [];
		var pos;
		var quote;
		while(string)
		{
			quote = string.substr(0,1);
			if(quote=='`')
			{
				var check = string.substr(1);
				pos = check.indexOf('`');
				if(pos!=-1)
				{
					tokens.push(check.substr(0, pos));
					string = check.substr(pos+1);
				}
				continue;
			}
			pos = string.indexOf(delim);
			if(pos==-1)
			{
				tokens.push(string);
				break;
			}
			tokens.push(string.substr(0, pos));
			string = string.substr(pos+delim.length);
		}
		for(var i=0; i<tokens.length; i++)
		{
			if(tokens[i].substr(0,1)=='$')
			{
				if(i==0)
				{
					var str = variables[tokens[i].substr(1)];
					tokens = [];
					tokens[0] = 'out';
					tokens[1] = '<span class="indent-1">'+str+'</span>';
					return tokens;
				}
				else tokens[i] = variables[tokens[i].substr(1)];
			}
			else if(tokens[i].substr(0,1)=='@')
			{
				var front = tokens.slice(0,i);
				var rear = tokens.slice(i+1);
				var insert = tokenize(macro[tokens[i].substr(1)], ' ');
				var temp = Array.concat(front, insert, rear);
				tokens = temp;
				--i;
			}
			else if(tokens[i].substr(0,1)=='&')
			{
				tokens[i] = tokens[i].substr(1);
				if(tokens[i]=='*')
				{
					tokens[i] = return_value;
				}
				else
				{
					tokens[i] = return_value[tokens[i]];
				}
				if(i==0)
				{
					var rtn = [];
					rtn[0] = 'out';
					rtn[1] = '<span class="indent-1">'+tokens[i]+'</span>';
					return rtn;
				}
			}
		}
		return tokens;
	}
	function out(text)
	{
		var obj = $('<div>'+(text===undefined?'':text.toString())+'</div>');
		$('.output').append(obj);
		obj[0].scrollIntoView();
	}
	function command(cmd)
	{
		var token = tokenize(cmd, ' ');
		switch(token[0])
		{
			case 'time':
			{
				var d = new Date();
				var t = d.getTime();
				if(token[1])
				{
					var t = 1;
					var count=0;
					var output = '';
					while(t<token.length)
					{
						if(token[t]=='-u') {output += Math.round(d.getTime()/1000) + ' ';count++}
						else if(token[t]=='-d') {output += d.toLocaleDateString() + ' ';count++}
						else if(token[t]=='-t') {output += d.toLocaleTimeString() + ' ';count++}
						t++;
					}
					if(!count) {return_value = t;}
					else {return_value = output;}
				}
				else {return_value = t;}
				break;
			}
			case 'set':
			{
				if(token[1]=='var'&&token[2])
				{
					variables[token[2]] = token[3];
					out('Variable \'$'+token[2]+'\' set to value: '+variables[token[2]]);
				}
				else if(token[1]=='macro'&&token[2]&&token[3])
				{
					macro[token[2]] = token[3];
					out('Macro \'@'+token[2]+'\' set to command: '+macro[token[2]]);
				}
				break;
			}
			case 'del':
			{
				if(token[1]=='var')
				{
					if(!token[2])
					{
						for(var key in variables) {delete variables[key];}
						out('All variables have been deleted.');
					}
					else
					{
						delete variables[token[2]];
						out('Variable \'$'+token[2]+'\' has been removed.');
					}
				}
				else if(token[1]=='macro')
				{
					if(!token[2])
					{
						for(var key in macro) {delete macro[key];}
						out('All macros have been deleted.');
					}
					else
					{
						delete macro[token[2]];
						out('Macro \'@'+token[2]+'\' has been removed.');
					}
				}
				else if(token[1]=='all')
				{
					for(var key in variables) {delete variables[key];}
					for(var key in macro) {delete macro[key];}
					return_value = {};
					out('All environment data has been deleted.');
				}
				break;
			}
			case 'load':
			{
				if(token[1])
				{
					$.ajax({
						method:'POST', url:token[1], dataType:'text', mimeType:'text/plain', async: true,
						success: function(content) {
							var script = content.split(/\r\n|\r|\n/g); //separate by line
							var iter = 0;
							//launch script
							runScript(script, iter);
						}
					});
				}
				break;
			}
			case 'out':
			{
				if(out===undefined) return;
				out(token[1]);
				break;
			}
			case 'vars':
			{
				var size = 0;
				var output = '';
				for(var key in variables)
				{
					output += '<span class="indent-1">$'+key+': '+variables[key] + '<span><br>';
					size++;
				}
				if(!size) out('<span style="color:#f33">No variables have been defined</span>');
				else
				{
					out('Defined Variables:');
					out(output);
				}
				break;
			}
			case 'macros':
			{
				var size = 0;
				var output = '';
				for(var key in macro)
				{
					output += '<span class="indent-1">$'+key+': '+macro[key] + '<span><br>';
					size++;
				}
				if(!size) out('<span style="color:#f33">No macros have been defined</span>');
				else
				{
					out('Defined Macros:');
					out(output);
				}
				break;
			}
			case 'cls':
			{
				$('.output').html('');
				break;
			}
			case 'help':
			{
				out('Functions: ');
				out('<span class="indent-1">set <i>[params]</i></span>');
				out('<span class="indent-2">vars <i>[variable] [value]</i></span>');
				out('<span class="indent-3">Creates or updates a global variable of the first argument (variable) to the value of the second argument (value).</span>');
				out();

				out('<span class="indent-1">time <i>[params (optional)]</i></span>');
				out('<span class="indent-2">Displays time in <i>milliseconds</i> since the Unix Epoch. Flags may be chained to produce more complex time and dates.</span>');
				out('<span class="indent-2">-u</span>');
				out('<span class="indent-3">Displays time in <i>seconds</i> since the Unix Epoch instead.</span>');
				out('<span class="indent-2">-d</span>');
				out('<span class="indent-3">Displays the current date in local format.</span>');
				out('<span class="indent-2">-t</span>');
				out('<span class="indent-3">Displays the current time in local format.</span>');
				out();

				out('<span class="indent-1">vars</span>');
				out('<span class="indent-2">Displays all variables defined in the global environment.</span>');
				out();
				break;
			}
			case 'get':
			{
				if(token[1]=='user'&&token[2])
				{
					var f = new FormData();
					f.append('user', token[2]);
					f.append('function', 'getuser');
					LOCK_MUTEX();
					ajax('functions.php', f, function(response) {
						obj = JSON.parse(response);
						var output = '';
						if(obj.STATUS_HEADER!='0')
						{
							out('<span style="color:#f33;">'+obj.STATUS_MESSAGE+'</span>');
							return;
						}
						else
						{
							for(var key in obj) {output += '<span class="indent-1">'+key+': '+obj[key] + '</span><br>';}
							out(output);
						}
						return_value = {};
						return_value = obj;
						UNLOCK_MUTEX();
					});
				}
				else if(token[1]=='return')
				{
					if(return_value)
					{
						out('Return:');
						for(var key in return_value) {out('<span class="indent-1">'+key+': '+return_value[key]+'</span>');}
					}
					else out('<span style="color:#f33;">No values exist in the return register.</span>');
				}
				else
				{
					out('<span style="color:#f33;">Invalid arguments</span>');
				}
				break;
			}
			default:
			{
				out('<span style="color:#f33;">Unrecognized function: \''+token[0]+'\'</span>');
				break;
			}
		}
		$('.cmd').val('');
	}
	$(document).on('keydown', function(e) {
		if(e.keyCode==13||e.which==13)
		{
			if(!$('.cmd').val()||return_mutex) return;
			pushToBuffer($('.cmd').val());
			out('<span style="color:#8f8;">'+$('.cmd').val()+'</span>');
			command($('.cmd').val());
		}
		else if(e.keyCode==38||e.which==38)
		{
			shift_buffer(1);
		}
		else if(e.keyCode==40||e.which==40)
		{
			shift_buffer(-1);
		}
	});
</script>
</html>
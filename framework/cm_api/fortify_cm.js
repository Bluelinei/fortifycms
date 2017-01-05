var CM = new Casemanager;

function Casemanager() //case manager class
{
	//variables
	this.cases = []; //list of all case objects loaded
	this.files = []; //list of all file objects loaded
	this.tags = []; //list of valid report tags that can be assigned to cases
	this.activecase; //the currently selected, active case to be edited

	//functions
	this.newCase = function() { //create new case object
		var f = new FormData();
		f.append('function', 'caseuid');
		ajax('framework/functions.php', f, function(uid) {
			var case = new Case(uid);
			this.cases.push(case);
		});
	}
	this.newFile = function(file, callback, error) { //create new file object
		var f = new FormData();
		f.append('file', file);
		f.append('filetype', getFileType(file.type));
		f.append('lastModified', getUnixTime(file.lastModified));

		var loadingPlace;
		
		//Check if the file already exists server side, if so, give it a UID and upload a new file. If not, return the uid of the object on the server.
		$.ajax({
			url: 'framework/fileupload.php', method: 'POST', data: f, processData: false, contentType: false,
			success: function(){callback(response);},
			error: function(){error(response);}
		);
	}
	this.postCase = function() { //save case to database
		
	}
	this.postFile = function() { //save file to database

	}
	this.postAll = function() { //saves all currently loaded cases into the database

	}
	this.pullCase = function() { //pull case from database

	}
	this.pullFile = function() { //pull file from database

	}
	this.pullCasesByUser = function() { //pulls all cases that the current user has access to. Current user is derived from the php SESSION variable

	}
	this.getCase = function() { //return case by uid

	}
	this.getFile = function() { //return file by uid

	}
	this.getAll = function() { //returns all cases in the casemanager object

	}
	this.setAsActiveCase = function(case) { //sets case to be currently active

	}
}

function Case(uid) //case object class
{
	//variables
	this.uid = uid; //internal UID of case for databasing purposes
	this.caseid; //case id for department reference
	this.name; //case nickname
	this.location; //case location
	this.file = []; //list of uids for all files in this particular case
	this.tags = []; //list of tags associated with case
	this.admin = false; //further admin or follow up
	this.type; //report type
	this.changed = false; //whether or not the case data has been changed since 
	//prelinking
	this.prelinkstart; //starting time for prelink in UNIX seconds
	this.prelinkend; //ending time for prelink in UNIX seconds
	this.prelinkenabled; //prelink enabled
	//DOM element
	this.element; //optional DOM or jquery element to be attached to case for display

	//functions
	this.addFile = function() { //adds file to case

	}
	this.removeFile = function() { //removes file from case

	}
}

function File(uid, file=null) //file object class
{
	//variables
	this.uid = uid; //internal UID
	this.name = (file?file.name:''); //file name
	this.type = (file?getFileType(file.type):'');
	this.path = '';
	this.date = getUnixTime((file?file.lastModified:0));
	this.uploaddate = 0;
	this.lastmodified = 0;
	this.element;
	this.thumbnail;
	this.status;
	this.caseindex = [];
	//functions
}
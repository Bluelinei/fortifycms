var popupnote = new Note();
var note_timer;

const DFLT_NOTE = 0;
const WARN_NOTE = 1;
const UPLD_NOTE = 2;

$(document).on('mouseenter', '.quick-notify', function (){
    clearTimeout(note_timer);
});

$(document).on('mouseleave', '.quick-notify', function (){
	clearTimeout(note_timer);
	note_timer = setTimeout(function(){
		$('.quick-notify').addClass('hidden');
		setTimeout(function(){
			if((popupnote.queue.length-1)>popupnote.iter)
			{
				popupnote.iter++;
				popupnote.show();
			}
			else 
			{
				popupnote.iter = 0;
				popupnote.queue = [];
				popupnote.type = [];
				popupnote.displaying = false;
				return;
			}
		}, 200);
	}, 2000);
});

$(document).on('click', '.quick-notify-close', function() {
	clearTimeout(note_timer);
	$('.quick-notify').addClass('hidden');
	setTimeout(function(){
		if((popupnote.queue.length-1)>popupnote.iter)
		{
			popupnote.iter++;
			popupnote.show();
		}
		else 
		{
			popupnote.iter = 0;
			popupnote.queue = [];
			popupnote.type = [];
			popupnote.displaying = false;
			return;
		}
	}, 200);
});

function notify(msg, notetype=0)
{
	//Add message to queue
	//Check if it's currently displaying
	//If it is
		//Let it roll
	//If it isn't
		//Start the queue
	popupnote.queue.push(msg);
	popupnote.type.push(notetype);
	if(!popupnote.displaying) popupnote.start();
}

function Note() {
	this.displaying = false;
	this.queue = [];
	this.type = [];
	this.iter = 0;
}

Note.prototype.show = function() {
	//Play the notifcation at the current index.
	//Wait for the requiset time
		//Hide the notification
		//Wait until the notification is hidden
			//Check if there are more notes in the queue
				//If so, Play them recursively
				//If not, clear the queue and exit.
	var t = this;
	$('.quick-notify-content>p').html(t.queue[t.iter]);
	resetPopup(t.type[t.iter]);
	$('.quick-notify').removeClass('hidden');
	clearTimeout(note_timer);
	note_timer = setTimeout(function(){
		$('.quick-notify').addClass('hidden');
		setTimeout(function(){
			if((t.queue.length-1)>t.iter)
			{
				t.iter++;
				t.show();
			}
			else 
			{
				t.iter = 0;
				t.queue = [];
				t.type = [];
				t.displaying = false;
				return;
			}
		}, 200);
	}, 2000);
}

Note.prototype.start = function() {
	this.displaying = true;
	this.show();
}

function resetPopup(e=0)
{
	var x = $('.quick-notify');
	x.removeClass('upld-note');
	x.removeClass('dflt-note');
	x.removeClass('warn-note');
	if(e==UPLD_NOTE) x.addClass('upld-note');
	else if(e==WARN_NOTE) x.addClass('warn-note');
	else x.addClass('dflt-note');
}
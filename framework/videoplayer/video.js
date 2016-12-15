var playheadgrab = false;
var video;
var bar_timer;
var audio;

const BACK = 0;
const FWRD = 1;

function checkAPI()
{
	try {
		window.AudioContext = window.AudioContext||window.webkitAudioContext;
		audio = new AudioContext();
	} catch(e) {
		alert('The Web Audio API is not supported by your browser. Fortify is recommended for use in the most current versions of the Firefox and Google Chrome web browsers.')
	}
}

function resetPlaybackButtons()
{
	$('.normal-speed').removeClass('selected');
	$('.2-faster').removeClass('selected');
	$('.4-faster').removeClass('selected');
	$('.8-faster').removeClass('selected');
	$('.2-slower').removeClass('selected');
	$('.4-slower').removeClass('selected');
	$('.8-slower').removeClass('selected');
}

function setPlaybackSpeed(trg)
{
	log(video.currentTime);
	resetPlaybackButtons();
	if(trg.hasClass('normal-speed'))
	{
		video.playbackRate = 1;
		$('.normal-speed').addClass('selected');
	}
	else if(trg.hasClass('2-faster'))
	{
		video.playbackRate = 2;
		$('.2-faster').addClass('selected');
	}
	else if(trg.hasClass('4-faster'))
	{
		video.playbackRate = 4;
		$('.4-faster').addClass('selected');
	}
	else if(trg.hasClass('8-faster'))
	{
		video.playbackRate = 8;
		$('.8-faster').addClass('selected');
	}
	else if(trg.hasClass('2-slower'))
	{
		video.playbackRate = 0.5;
		$('.2-slower').addClass('selected');
	}
	else if(trg.hasClass('4-slower'))
	{
		video.playbackRate = 0.25;
		$('.4-slower').addClass('selected');
	}
	else if(trg.hasClass('8-slower'))
	{
		video.playbackRate = 0.125;
		$('.8-slower').addClass('selected');
	}
}

function stepFrame(num)
{
	if(num)
	{
		videoPause();
		video.currentTime += 0.04;
	}
	else
	{
		videoPause();
		video.currentTime -= 0.04;
	}
}

function formatTimecode(t)
{
	var h,m,s,hs,ms,ss;

	h = Math.floor(t/3600);
	m = Math.floor((t%3600)/60);
	s = Math.floor(t%60);

	hs = (h<10?"0"+h:h);
	ms = (m<10?"0"+m:m);
	ss = (s<10?"0"+s:s);

	return hs+":"+ms+":"+ss;
}

function videoPlay()
{
	video.play();
	$('#video-toggle').attr('src', 'img/pause-button.png');
}

function videoPause()
{
	video.pause();
	$('#video-toggle').attr('src', 'img/play-button.png');
}

function togglePlayPause()
{
	if(video.paused) videoPlay();
	else videoPause();
}

function toggleAdvanced()
{
	var atc = $('.advanced-toolbar-container');
	if(atc.hasClass('hidden')) atc.removeClass('hidden');
	else atc.addClass('hidden');
}

function setCurTime()
{
	$('#video-curtime').html(formatTimecode(video.currentTime));
	$('#video-duration').html('- '+formatTimecode(video.duration-video.currentTime));
	$('.progress-bar').css('width', (video.currentTime/video.duration)*98+'%');
}

function setEventListeners()
{
	$('#video-toggle').on('click', togglePlayPause);
	$('#video').on('click', togglePlayPause);
	$('#video').on('timeupdate', setCurTime);
	$('#video').on('ended', function(){
		$('#video-toggle').attr('src', 'img/play-button.png');
	});
	$('#advanced-menu-button').on('click', toggleAdvanced);
	$('.play-head').on('mousedown', function(e) {playheadgrab=true;});
	$(document).on('mouseup', function(e) {playheadgrab=false;});
	$(document).on('mousemove', function(e) {
		if(playheadgrab)
		{
			var bar = $('.progress-bar-base');
			var width = bar.width();
			var offset = Math.floor(bar.offset().left);
			var mousepos = e.clientX - offset;
			var progress = ((mousepos>width?width:mousepos)/width);
			$('.progress-bar').css({'width':((progress*98)+'%')});
			video.currentTime = video.duration*progress;
		}
	});
	$('.progress-bar-base').on('mousedown', function(e) {
		playheadgrab = true;
		var bar = $('.progress-bar-base');
		var width = bar.width();
		var offset = Math.floor(bar.offset().left);
		var mousepos = e.clientX - offset;
		var progress = ((mousepos>width?width:mousepos)/width);
		$('.progress-bar').css({'width':((progress*98)+'%')});
		video.currentTime = video.duration*progress;
	});
	$('.video-overlay-area').on('mouseenter', function(e) {
		$('.video-control-container').removeClass('hidden');
	});
	$('.video-overlay-area').on('mouseleave', function(e) {
		$('.video-control-container').addClass('hidden');
	});
	$(document).on('click', '#page-body', function(e) {
		$('.notification-button-container').addClass('hidden');
	});
	$('.playback-speed').on('click', function(e) {
		setPlaybackSpeed($(e.target));
	})
	$('.step-forward-frame').on('click', function(e){stepFrame(FWRD);});
	$('.step-back-frame').on('click', function(e){stepFrame(BACK);});
}

window.onload = function(){
	checkAPI();
	setEventListeners();
	video = document.getElementById('video');
	setPlaybackSpeed($('.normal-speed'));
}
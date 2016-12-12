var playheadgrab = false;
var video;
var bar_timer;

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
	$('.play-head').on('mousedown', function(e) {playheadgrab=true; videoPause();});
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
		videoPause();
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
}

window.onload = function(){
	setEventListeners();
	video = document.getElementById('video');
}
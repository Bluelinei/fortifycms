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

function togglePlayPause()
{
	var video = document.getElementById('video');
	if(video.paused)
	{
		video.play();
		$('#video-toggle').attr('src', 'img/pause-button.png');
	}
	else
	{
		video.pause();
		$('#video-toggle').attr('src', 'img/play-button.png');
	}
}

function toggleAdvanced()
{
	var atc = $('.advanced-toolbar-container');
	if(atc.hasClass('hidden')) atc.removeClass('hidden');
	else atc.addClass('hidden');
}

function setCurTime()
{
	var video = document.getElementById('video');
	$('#video-curtime').text(formatTimecode(video.currentTime));
	$('#video-duration').text(formatTimecode(video.duration-video.currentTime));
	$('.progress-bar').css('width', Math.floor((video.currentTime/video.duration)*98)+'%');
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
}

window.onload = function(){
	var video = document.getElementById('video');
	setEventListeners();
}
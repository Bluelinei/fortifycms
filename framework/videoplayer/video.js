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
	if(video.paused) video.play();
	else video.pause();
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
	$('#video').on('timeupdate', setCurTime);
}

window.onload = function(){
	var video = document.getElementById('video');
	setEventListeners();
}
function togglePlayPause()
{
	var video = document.getElementById('video');
	if(video.paused) video.play();
	else video.pause();
}

function setEventListeners()
{
	$('#video-toggle').on('click', togglePlayPause);
}

window.onload = function(){
	setEventListeners();
}
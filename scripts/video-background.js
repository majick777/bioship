var $ = jQuery.noConflict();

var videoID = document.getElementById('videobackgroundid').value;
var videodelay = document.getElementById('videobackgrounddelay').value;

var fullbodywidth = $(window).width();
var fullbodyheight = $(window).height();
var ytplayer;
var ytPlayerReady = false;
var videoLoop = true;
var bgTimer;
var videoTimer;

function loadbgVideo() {

	if (ytPlayerReady) {
		headerheight = $('#header').height();
		videobackgroundheight = fullbodyheight + 40;
		videobackgroundwidth = fullbodywidth - 40;
		$('#backgroundvideo').prepend($('<div id="ytVideo" width="'+fullbodywidth+'" height="'+fullbodyheight+'"><div id="ytVideoPlayer" width="'+fullbodywidth+'" height="'+fullbodyheight+'"></div></div>').addClass('new').addClass('source'));
		ytplayer = new YT.Player('ytVideoPlayer', {
		  height: videobackgroundheight,
		  width: videobackgroundwidth,
		  videoId: videoID,
		  playerVars: {
			controls: 1,
			showinfo: 0 ,
			modestbranding: 1,
			wmode: 'opaque'
		},
		  events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		  }
		});
		clearInterval(bgTimer);

		$('#backgroundvideowrapper').css('left','20px');
		$('#backgroundvideowrapper').css('right','20px');

		$('#ytVideo').css('width', videobackgroundwidth);
		$('#ytVideo').attr('width', videobackgroundwidth);

		$('#ytVideo').css('height', videobackgroundheight);
		$('#ytVideo').attr('height', videobackgroundheight);

		$('#ytVideoPlayer').attr('width', videobackgroundwidth);
		$('#ytVideoPlayer').css('width', videobackgroundwidth);

		$('#ytVideoPlayer').attr('height', videobackgroundheight);
		$('#ytVideoPlayer').css('height', videobackgroundheight);

		videoTimer = setInterval(playBgVideo, videodelay);
	}
}

function pauseBgVideo(){
	ytplayer.stopVideo();
}

function playBgVideo(){
	ytplayer.playVideo();
	/* videoMute(); */
	clearInterval(videoTimer);
}

function videoMute(){
	ytplayer.mute();
	videoMuted = true;
	setVideoMuteIcon();
}

function videoUnMute(){
	ytplayer.unMute();
	videoMuted=false;
	setVideoMuteIcon();
}

/* Youtube API Begin */

var tag = document.createElement('script');
tag.src = "http://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

function onYouTubePlayerAPIReady() {ytPlayerReady = true;}

function onPlayerReady(event) {
	if (videoMuted) {videoMute();}
	else {videoUnMute();}
}

function onPlayerStateChange(event) {
	if (event.data==YT.PlayerState.ENDED && videoLoop) {event.target.playVideo();}
}

function stopVideo() {
	ytplayer.stopVideo();
}

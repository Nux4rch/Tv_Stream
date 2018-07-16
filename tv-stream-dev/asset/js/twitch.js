var options = {width: '100%', height: '100%', channel: 'mrdjcraft'};
var player = new Twitch.Player('live-stream', options);
player.setMuted(false);
player.setVolume(1);
var interval;//pts
var actu;
var ONLINE=false;
player.addEventListener(Twitch.Player.ONLINE, function () 
{
	console.log("ONLINE");
	ONLINE=true;
	actu = setInterval(function(){$.getJSON( "https://api.twitch.tv/kraken/streams/mrdjcraft?client_id=u2d4zepj7qyrogyhtcouh9023e8mol" ,
		function (data)
		{
			if(data.stream != null ){//live	
			console.log("+1pts");
			$("#live-on").text("ONLINE");
			$("#live-name").text(data.stream.channel.status);
			$("#live-viewers").text(" "+data.stream.viewers+" ");
			$("#live-game").text(data.stream.game);
			$("#live-on").css("color", "green");
			$("#live-viewer").css("color", "green");
			}
		})},2000);
});
player.addEventListener(Twitch.Player.OFFLINE, function () 
{
	console.log("OFFLINE");
	ONLINE=false;
	$("#live-on").text("OFFLINE");
	$("#live-name").text("tv-stream");
	$("#live-viewers").text(" 0 ");
	$("#live-game").text("No Game");
	$("#live-on").css("color", "red");
	$("#live-viewer").css("color", "red");
	clearInterval(actu);
});
player.addEventListener(Twitch.Player.PAUSE, function () 
{
	console.log("PAUSE");
	clearInterval(interval);
});
player.addEventListener(Twitch.Player.ENDED, function () {console.log("ENDED");});
player.addEventListener(Twitch.Player.PLAY, function () 
{
	console.log("PLAY");
	interval = setInterval(function() { if(ONLINE==true){ $.getJSON( "/pts.php" ,function (data){console.log("+1pts");console.log(data);})} },60000);//1000 = 1 seg
});
player.addEventListener(Twitch.Player.READY, function () {console.log("READY");});
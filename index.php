<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Webrig</title>
<link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="jquery.min.js"></script>
        <script src="//cdn.webrtc-experiment.com/firebase.js"> </script>
        <script src="//cdn.webrtc-experiment.com/getMediaElement.min.js"> </script>
        <script src="//cdn.webrtc-experiment.com/RTCMultiConnection.js"> </script>
<script>
setInterval(function(){
      $('#signals').load('signals.php');
 },1000);
</script>
<style>
body {
background: rgb(0,0,0);
color: #fff; 
}
#ptt {
color:red;
width:300px;
height:120px;
font-size:24px;
background: rgb(181,189,200); /* Old browsers */
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
border-radius: 12px;
}
#set {
color:red;
width:200px;
height:50px;
font-size:18px;
background: rgb(181,189,200); /* Old browsers */
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
border-radius: 12px;
}
#setfreq {
color:red;
width:70px;
height:50px;
font-size:18px;
background: rgb(181,189,200); /* Old browsers */
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
border-radius: 12px;
}

#signals {
color:blue;
//background-image: url(http://qrznow.com/wp-content/uploads/2015/10/ft-950.jpg);
background-size: 300px 100px;
background-repeat: no-repeat;
background-position-x: center;
height: 75px;
padding: 18px 0 0 0;
font-family: 'Orbitron', sans-serif;
font-size: 30px;
font-style: italic;
}
</style>
</head>
<body>

<center>
<?php
include ('config.php');
// Read rig
$freq = exec(RIGCTL.'  -m 2 -r '.HOST.' f');
$mod = exec(RIGCTL.'  -m 2 -r '.HOST.' m | head -n 1');


?>
<h2>
<div id="signals"></div>
</h2>

<button id="ptt" onclick="ptt()">PTT</button> 
<br><br>
<script>
function ptt(){
$('#ptt').load('ptt.php');
}; 
function set(){
freq=document.getElementById("frequency").value;
mod=document.getElementById("mod").value;
mem=document.getElementById("mem").value;
$('#set').load('set.php?freq='+freq+'&mod='+mod+'&mem='+mem);
document.getElementById("mem").value = "";
document.getElementById("freq").value = "";
}; 

function setfreq(fr){
$('#set').load('set.php?move='+fr);
};

</script>

<input id="frequency" type="text" name="freq" value="<?php echo $freq;?>">
<br>

 <select id="mod" name="mod">
  <option <?php if ($mod=="USB") { echo 'selected="selected"'; } ?> value="USB">USB</option>
  <option <?php if ($mod=="LSB") { echo 'selected="selected"'; } ?> value="LSB">LSB</option>
  <option <?php if ($mod=="CW") { echo 'selected="selected"'; } ?> value="CW">CW</option>
</select> 

<select id="mem" name="mem">
<?php
$row = 1;
if (($handle = fopen("memory.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	echo '<option value="'.$data[0].'">'.$data[1].'</option>\n';
	$row++;
    }
    fclose($handle);
}
?>
</select>
<br>
<br>

<button id="set" onclick="set()">SET</button> 
<br><br>
<button id="setfreq" onclick="setfreq(-1)">-1</button>
<button id="setfreq" onclick="setfreq(-0.5)">-0.5</button>
<button id="setfreq" onclick="setfreq(0.5)">+0.5</button>
<button id="setfreq" onclick="setfreq(+1)">+1</button>
<br>
<button id="setfreq" onclick="setfreq(-12.5)">-12.5</button>
<button id="setfreq" onclick="setfreq(-10)">-10</button>
<button id="setfreq" onclick="setfreq(+10)">+10</button>
<button id="setfreq" onclick="setfreq(+12.5)">+12.5</button>
<br>

</center>
        
            <script>

                var connection = new RTCMultiConnection();
                connection.session = {
                    audio: true, video: false
                };
                
               connection.bandwidth = { audio: 6 };

                // Find the line in sdpLines that starts with |prefix|, and, if specified,
                // contains |substr| (case-insensitive search).
                function findLine(sdpLines, prefix, substr) {
                  return findLineInRange(sdpLines, 0, -1, prefix, substr);
                }

                // Find the line in sdpLines[startLine...endLine - 1] that starts with |prefix|
                // and, if specified, contains |substr| (case-insensitive search).
                function findLineInRange(sdpLines, startLine, endLine, prefix, substr) {
                  var realEndLine = endLine !== -1 ? endLine : sdpLines.length;
                  for (var i = startLine; i < realEndLine; ++i) {
                    if (sdpLines[i].indexOf(prefix) === 0) {
                      if (!substr ||
                          sdpLines[i].toLowerCase().indexOf(substr.toLowerCase()) !== -1) {
                        return i;
                      }
                    }
                  }
                  return null;
                }

                // Gets the codec payload type from an a=rtpmap:X line.
                function getCodecPayloadType(sdpLine) {
                  var pattern = new RegExp('a=rtpmap:(\\d+) \\w+\\/\\d+');
                  var result = sdpLine.match(pattern);
                  return (result && result.length === 2) ? result[1] : null;
                }

                var roomsList = document.getElementById('rooms-list'), sessions = { };
                connection.onNewSession = function(session) {
                    if (sessions[session.sessionid]) return;
                    sessions[session.sessionid] = session;

                    var tr = document.createElement('tr');
                    tr.innerHTML = '<td><strong>' + ((session.extra && session.extra['user-name']) || session.userid) + '</strong> is making an audio call.</td>' +
                        '<td><button class="join" id="receive-call">Receive Call</button></td>';
                    roomsList.insertBefore(tr, roomsList.firstChild);

                    tr.querySelector('#receive-call').setAttribute('data-sessionid', session.sessionid);
                    tr.querySelector('#receive-call').onclick = function() {
                        this.disabled = true;

                        session = sessions[this.getAttribute('data-sessionid')];
                        if (!session) alert('No room to join.');

                        connection.join(session);
                    };
                };

                var audiosContainer = document.getElementById('audios-container') || document.body;
                connection.onstream = function(e) {
					var audioElement = getAudioElement(e.mediaElement, {
						title: (e.extra && e.extra['user-name']) || e.userid,
						onMuted: function(type) {
                            connection.streams[e.streamid].mute({
                                audio: type == 'audio',
                                video: type == 'video'
                            });
                        },
                        onUnMuted: function(type) {
                            connection.streams[e.streamid].unmute({
                                audio: type == 'audio',
                                video: type == 'video'
                            });
                        },
                        onRecordingStarted: function(type) {
                            connection.streams[e.streamid].startRecording({
                                audio: type == 'audio',
                                video: type == 'video'
                            });
                        },
                        onRecordingStopped: function(type) {
                            connection.streams[e.streamid].stopRecording(function(blob) {
                                var _mediaElement = document.createElement(type);
                                
                                _mediaElement.src = URL.createObjectURL(blob);
                                _mediaElement = getMediaElement(_mediaElement, {
                                    buttons: ['mute-audio', 'mute-video', 'stop']
                                });
                                
                                _mediaElement.toggle(['mute-audio', 'mute-video']);
                                
                                audiosContainer.insertBefore(_mediaElement, audiosContainer.firstChild);
                            }, type);
                        },
                        onStopped: function() {
                            connection.drop();
                        }
					});
					
					if(e.type == 'local') {
						// audioElement.toggle('mute-audio');
                        e.mediaElement.volume = 0;
                        e.mediaElement.muted = true;
					}
					
                    audiosContainer.insertBefore(audioElement, audiosContainer.firstChild);
                };

                connection.onstreamended = function(e) {
                    if (e.mediaElement.parentNode && e.mediaElement.parentNode.parentNode && e.mediaElement.parentNode.parentNode.parentNode) {
                        e.mediaElement.parentNode.parentNode.parentNode.removeChild(e.mediaElement.parentNode.parentNode);
                    }
                };
				
				document.getElementById('user-name').onkeyup = function() {
					connection.extra['user-name'] = this.value;
				};

                document.getElementById('setup-voice-only-call').onclick = function() {
                    this.disabled = true;
                    connection.open();
                };
                
                connection.extra = {
                    'user-name': 'Anonymous'
                };

                connection.connect();
				
				(function() {
                    var uniqueToken = document.getElementById('unique-token');
                    if (uniqueToken)
                        if (location.hash.length > 2) uniqueToken.parentNode.parentNode.parentNode.innerHTML = '<h2 style="text-align:center;"><a href="' + location.href + '" target="_blank">Share this link</a></h2>';
                        else uniqueToken.innerHTML = uniqueToken.parentNode.parentNode.href = '#' + (Math.random() * new Date().getTime()).toString(36).toUpperCase().replace( /\./g , '-');
                })();
            </script>

</body>
</html>

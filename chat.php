<?php
$room = $_GET['room'] ?? 'public';
$subject = $_GET['subject'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Room: <?=htmlspecialchars($room)?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="sidebar">
  <h3 id="roomTitle">Room: <?=htmlspecialchars($room)?></h3>
  <div id="channelList"><strong>Channels</strong></div>
  <div id="memberList"><strong>Members</strong></div>
</div>

<div id="chatArea">
    <div id="messages"></div>
    <div id="inputArea">
        <input id="input" placeholder="Type your message...">
        <button id="send">Send</button>
    </div>
</div>

<!-- Define global variables -->
<script>
window.room = "<?=$room?>";
window.user = localStorage.getItem('chatUser') || prompt("Enter your name:");
</script>

<script src="script.js"></script>
</body>
</html>

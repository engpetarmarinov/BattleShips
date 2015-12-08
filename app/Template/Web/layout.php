<!DOCTYPE html>
<html>
	<head>
		<title>Battle Ships</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<h1>Battle Ships</h1>
		<div id="board">
			<?php $this->displayTemplate(); ?>
		</div>
		<div id="msg"></div>
		<div class="triesWrapper">Shots: <span id="tries"><?=$this->tries?></span></div>
		<a href="index.php?action=restart">Restart game</a>
		<a href="index.php?action=show">Reveal ships</a>		
		<script src="js/game.js" type="text/javascript"></script>
	</body>
</html>

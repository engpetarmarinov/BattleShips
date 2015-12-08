<?php

/**
 * WEB
 */
$game = new Controller\GameWEB();

//simple router
$actionName = (isset($_GET['action'])) ? strtolower(trim($_GET['action'])) . 'Action' : 'startAction';
if (method_exists($game, $actionName)) {
	//invoke public actions
	$game->$actionName();
} else {
	//Start the game
	$game->startAction();
}

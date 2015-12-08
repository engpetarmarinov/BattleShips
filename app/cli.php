<?php
/**
 * CLI
 */
$game = new Controller\GameCLI();
$game->clearConsole();
$game->startAction();

while (true) {
	$action = trim(fgets(STDIN));
	if ($action) {
		$game->clearConsole();
		//simple router {$action}Action
		$actionName = $action . 'Action';
		if (method_exists($game, $actionName)) {
			//invoke public actions
			$game->$actionName();
		} else {
			$game->fireAction($action);
		}
		$action = false;
	}
}

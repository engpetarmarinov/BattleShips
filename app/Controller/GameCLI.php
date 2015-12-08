<?php

namespace Controller;

/**
 * Controller for the CLI interface
 * @package  Battleships
 * @category Controller
 */
class GameCLI extends Game {

	public function __construct() {
		//start the game
		$this->start();
	}

	public function startAction() {
		//display
		$view = new \View();
		$view->grid = $this->board->getGrid();
		$view->symbols = $this->getBoardSymbols();
		$view->rows = $this->getRowsNames();
		$view->cols = $this->getColsNames();
		$view->tries = $this->board->getTries();
		$view->display('Cli/board');
	}
	
	public function restartAction() {
		$this->reset();
		$this->startAction();
	}

	public function showAction() {
		$view = new \View();
		$view->grid = $this->board->getGridRevealed();
		$view->symbols = $this->getBoardSymbols();
		$view->rows = $this->getRowsNames();
		$view->cols = $this->getColsNames();
		$view->tries = $this->board->getTries();
		$view->display('Cli/board');
		
	}

	public function fireAction($input = null) {
		$status = false;
		$parsedInputCoordinates = $this->parseInputCoordinates($input);
		if ($parsedInputCoordinates) {
			$row = $this->getRowNumFromName($parsedInputCoordinates[0]);
			$col = $parsedInputCoordinates[1];
			if ($row && $col) {
				$status = $this->fire($row,$col);
			}
		}
		//display
		$view = new \View();
		$view->grid = $this->board->getGrid();
		$view->symbols = $this->getBoardSymbols();
		$view->rows = $this->getRowsNames();
		$view->cols = $this->getColsNames();
		$view->msg = $this->getMessageByStatus($status);
		$view->tries = $this->board->getTries();
		$view->display('Cli/board');
		//Reset the game if it's over
		if ($this->isGameOver()) {
			$this->reset();
		}
	}
	
	/**
	 * Parse the input coordinates to row/col coordinates
	 * @param string $input
	 * @return array
	 */
	private function parseInputCoordinates($input) {
		$parsedInput[] = strtolower(substr($input, 0, 1));
		$parsedInput[] = (int)substr($input, 1);
		return $parsedInput;
	}

	/**
	 * Get the row number by row name
	 * @param string $char
	 * @return false|integer Returns the row num or false
	 */
	protected function getRowNumFromName($char) {
		$char = strtolower(trim($char));
		$rowNames = $this->getRowsNames();
		return array_search($char, $rowNames);
	}

	protected function reset() {
		$this->status = null;
		$this->start();
	}

	/**
	 * A dumb and universal way to clear the console on all OS
	 */
	public function clearConsole() {
		for ($i = 0; $i < 150; $i++) {
			echo "\r\n";
		}
		//linux clear console
		//@system('clear');
	}

}

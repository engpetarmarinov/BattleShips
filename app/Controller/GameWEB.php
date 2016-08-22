<?php

namespace Controller;

use Model;

/**
 * Controller for the WEB interface
 * @package  Battleships
 * @category Controller
 */
class GameWEB extends Game {

	public function __construct() {
		//Start session
		session_start();
		//start the game
		$this->start();
	}

	protected function start() {
		$this->board = $this->getBoardFromSession();
		if (!$this->board) {
			//init board
			$this->board = new Model\Board();
			$this->board->init();
		}
	}

	public function startAction() {
		//display
		$view = new \View('Web/layout');
		$view->grid = $this->board->getGrid();
		$view->symbols = $this->getBoardSymbols();
		$view->rows = $this->getRowsNames();
		$view->cols = $this->getColsNames();
		$view->tries = $this->board->getTries();
		$view->display('Web/board');
	}
	
	public function restartAction() {
		$this->reset();
		header('Location: index.php');
	}

	public function fireAction() {

		$row = filter_input(INPUT_POST, 'row');
		$col = filter_input(INPUT_POST, 'col');

		$view = new \View();

		if (!$row || !$col) {
			$view->dislayJson(array('status' => 0, 'msg' => 'Invalid coordinates!'));
			return;
		}

		//fire
		$status = $this->fire($row, $col);
		$view->grid = $this->board->getGrid();
		$view->symbols = $this->getBoardSymbols();
		$view->rows = $this->getRowsNames();
		$view->cols = $this->getColsNames();

		//Get the html for the board
		ob_start();
		$view->display('Web/board');
		$html = ob_get_contents();
		ob_clean();

		$data = array(
			'status' => 1,
			'msg' => $this->getMessageByStatus($status),
			'html' => $html,
			'tries' => $this->board->getTries(),
		);
		$view->dislayJson($data);
		//Reset the game if it's over
		if ($this->isGameOver()) {
			$this->reset();
		}
	}

	public function showAction() {
		$view = new \View('Web/layout');
		$view->grid = $this->board->getGridRevealed();
		$view->symbols = $this->getBoardSymbols();
		$view->rows = $this->getRowsNames();
		$view->cols = $this->getColsNames();
		$view->tries = $this->board->getTries();
		$view->display('Web/board');
	}	

	/**
	 * Saves the board into the session
	 */
	private function saveBoardToSession() {
		$_SESSION['board'] = serialize($this->board);
	}

	/**
	 * Gets the board from the session
	 * @return false|\Model\Board
	 */
	private function getBoardFromSession() {
		if (isset($_SESSION['board'])) {
			return unserialize($_SESSION['board']);
		}
		return false;
	}

	protected function reset() {
		$this->status = null;
		session_destroy();
	}

	public function __destruct() {
		//save board
		$this->saveBoardToSession();
	}	

}

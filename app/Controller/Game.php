<?php

namespace Controller;

use Model;

/**
 * Battle Ships game controller
 * @author Petar Marinov <eng.petar.marinov@gmail.com>
 * @package  Battleships
 * @category Controller
 */
abstract class Game {

	const STATUS_HIT = 1;
	const STATUS_SUNK = 2;
	const STATUS_FINISHED = 3;
	const STATUS_MISS = 4;

	/**
	 * @var \Model\Board 
	 */
	protected $board;

	/**
	 * Status of the game
	 * @var integer
	 */
	protected $status;

	/**
	 * Symbols for the grid
	 * @var array
	 */
	protected $symbols = array(
		Model\Board::POSITION_HIT => 'x',
		Model\Board::POSITION_NO_SHOT => '.',
		Model\Board::POSITION_MISS => '-',
	);

	/**
	 * Public action for starting the game
	 */
	abstract public function startAction();

	/**
	 * Action fire
	 */
	abstract public function fireAction();

	/**
	 * Action to show the ships on the grid
	 */
	abstract public function showAction();

	/**
	 * Reset the game
	 */
	abstract protected function reset();

	/**
	 * Start the game
	 */
	protected function start() {
		//init board
		$this->board = new Model\Board();
		$this->board->init();
	}

	/**
	 * Attack coordinates by row/col
	 * @param int $row Number of the row
	 * @param int $col Number of the column
	 * @return int
	 */
	protected function fire($row, $col) {
		$ship = $this->board->fire($row, $col);
		if ($ship) {
			if ($ship->isSunk()) {
				if ($this->board->isCleared()) {
					$status = self::STATUS_FINISHED;
				} else {
					$status = self::STATUS_SUNK;
				}
			} else {
				$status = self::STATUS_HIT;
			}
		} else {
			$status = self::STATUS_MISS;
		}
		$this->setGameStatus($status);
		return $status;
	}

	/**
	 * Get the symbols used to fill the grid of the board
	 * @return array
	 */
	protected function getBoardSymbols() {
		return $this->symbols;
	}

	/**
	 * Sets status of the game
	 * @param int $status
	 */
	protected function setGameStatus($status) {
		$this->status = $status;
	}

	/**
	 * Checks if the game is over
	 * @return boolean
	 */
	protected function isGameOver() {
		return $this->status === self::STATUS_FINISHED;
	}

	/**
	 * Get message for a status
	 * @param int $status
	 * @return string
	 */
	protected function getMessageByStatus($status) {
		switch ($status) {
			case self::STATUS_FINISHED:
				return "Well done! You have destroyed all ships in {$this->board->getTries()} shots!";
			case self::STATUS_MISS:
				return "Missed!";
			case self::STATUS_HIT:
				return "Hit!";
			case self::STATUS_SUNK:
				return "Sunk!";
			default:
				return "Error! Try again?";
		}
	}

	/**
	 * Returns the names of the rows
	 * @return type
	 */
	protected function getRowsNames() {
		$rowNames = array();
		$row = 0;
		for ($letter = ord('a'); $letter <= ord('z'); $letter++) {
			$row++;
			if ($row > Model\Board::ROWS) {
				break;
			}
			$rowNames[$row] = chr($letter);
		}
		return $rowNames;
	}

	/**
	 * Returns the names of the columns
	 * @return int
	 */
	protected function getColsNames() {
		$colNames = array();
		for ($col = 1; $col <= Model\Board::COLS; $col++) {
			$colNames[$col] = $col;
		}
		return $colNames;
	}

}

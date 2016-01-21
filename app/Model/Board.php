<?php

/**
 * @package  Battleships
 * @category model
 */

namespace Model;

/**
 * The board of the game
 */
class Board {

	/**
	 * Grid size
	 */
	const ROWS = 10;
	const COLS = 10;

	/**
	 * Positions
	 */
	const POSITION_NO_SHOT = 0;
	const POSITION_HIT = 1;
	const POSITION_MISS = 2;

	/**
	 * Ships
	 */
	const NUM_BATTLE_SHIPS = 1;
	const NUM_DESTROYER_SHIPS = 2;

	/**
	 * The matrix of the board
	 * @var array
	 */
	private $boardGrid = array();

	/**
	 * Ships on the board
	 * @var array
	 */
	private $ships = array();

	/**
	 * Number of tries to hit a ship on the board
	 * @var int
	 */
	private $tries = 0;

	/**
	 * Generates initial grid for the board
	 * @return array
	 */
	protected function generateGrid() {
		$grid = array();
		for ($row = 1; $row <= self::ROWS; $row++) {
			for ($col = 1; $col <= self::COLS; $col++) {
				$grid[$row][$col] = self::POSITION_NO_SHOT;
			}
		}
		return $grid;
	}

	/**
	 * Init board - generates grid and ships
	 */
	public function init() {
		//grid
		if (!$this->boardGrid) {
			$this->boardGrid = $this->generateGrid();
		}
		//ships
		if (!$this->ships) {
			$this->ships = $this->generateShips();
		}
	}

	/**
	 * Generates ships and places them on the board
	 * @return array Array of instances of \Model\Ship
	 */
	protected function generateShips() {
		//TODO: prevent placing of too many ships, that can't be fitted on the board
		//Battle ships
		for ($battleShipsNum = 0; $battleShipsNum < self::NUM_BATTLE_SHIPS; $battleShipsNum++) {
			$ship = $this->placeShip(new Ship\Battle());
			$this->ships[] = $ship;
		}
		//Destroyer ships
		for ($destroyerShipsNum = 0; $destroyerShipsNum < self::NUM_DESTROYER_SHIPS; $destroyerShipsNum++) {
			$ship = $this->placeShip(new Ship\Destroyer());
			$this->ships[] = $ship;
		}
		return $this->ships;
	}

	/**
	 * Gets number of tries/shots used 
	 * @return integer
	 */
	public function getTries() {
		return $this->tries;
	}

	/**
	 * Places a ship on the board
	 * @param \Model\Ship $ship
	 * @return \Model\Ship
	 */
	protected function placeShip(Ship $ship) {
		$length = $ship->getLengh();
		while (!$ship->isPlaced()) {
			//TODO: optimize
			$direction = rand(0, 1) ? Ship::DIRECTION_HORIZONTAL : Ship::DIRECTION_VERTICAL;
			$row = rand(1, self::ROWS);
			$col = rand(1, self::COLS);
			$points = $this->generateShipPoints($length, $direction, $row, $col);
			if (!$this->hasCollision($points)) {
				$ship->setPoints($points);
			}
		}
		return $ship;
	}

	/**
	 * Generates coordinates on the board
	 * @param int $length
	 * @param int $direction
	 * @param int $row
	 * @param int $col
	 * @return array
	 */
	private function generateShipPoints($length, $direction, $row, $col) {
		$points = array(
			array($row, $col),
		);
		for ($i = 1; $i < $length; $i++) {
			if ($direction === Ship::DIRECTION_HORIZONTAL) {
				$coordinates = array($row, $col + $i);
			} else {
				$coordinates = array($row + $i, $col);
			}
			$points[] = $coordinates;
		}
		return $points;
	}

	/**
	 * Detects collition between points and ships that have been already placed
	 * @param array $points
	 * @return boolean
	 */
	private function hasCollision(Array $points) {
		foreach ($points as $coordinates) {
			if ($coordinates[0] > self::ROWS || $coordinates[1] > self::COLS) {
				return true;
			}
			if ($this->hasShip($coordinates)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Checks if there is a ship for this coordinates
	 * @param array $coordinates
	 * @return boolean
	 */
	protected function hasShip(Array $coordinates) {
		foreach ($this->ships as $ship) {
			if ($ship->isThere($coordinates)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Gets the values of the grid
	 * @return array
	 */
	public function getGrid() {
		return $this->boardGrid;
	}

	/**
	 * Gets the values of the grid with revealed ships positions
	 * @return array
	 */
	public function getGridRevealed() {
		$grid = $this->getGrid();
		foreach ($grid as $rowNum => $cols) {
			foreach ($cols as $colNum => $position) {
				if ($this->hasShip(array($rowNum, $colNum))) {
					$grid[$rowNum][$colNum] = self::POSITION_HIT;
				}
			}
		}
		return $grid;
	}

	/**
	 * Attack row/col
	 * @param int $row
	 * @param int $col
	 * @return false|\Model\Ship Returns false or the Ship that's been hit
	 */
	public function fire($row, $col) {
		if (!isset($this->boardGrid[$row][$col]) || $this->boardGrid[$row][$col] !== self::POSITION_NO_SHOT) {
			return false;
		}
		$this->tries++;
		$coordinates = array($row, $col);
		foreach ($this->ships as $ship) {
			if ($ship->hit($coordinates)) {
				$this->updateGrid($coordinates, self::POSITION_HIT);
				return $ship;
			}
		}
		$this->updateGrid($coordinates, self::POSITION_MISS);
		return false;
	}

	/**
	 * Updates a grid position
	 * @param array $coordinates Array(row,col)
	 * @param int $position
	 */
	private function updateGrid(Array $coordinates, $position) {
		$this->boardGrid[$coordinates[0]][$coordinates[1]] = $position;
	}

	/**
	 * Checks if the board has no more ships
	 */
	public function isCleared() {
		foreach ($this->ships as $ship) {
			if (!$ship->isSunk()) {
				return false;
			}
		}
		return true;
	}

}

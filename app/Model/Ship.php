<?php

namespace Model;

/**
 * Ship that is placed on the board
 * @package  Battleships
 * @category model
 */
abstract class Ship {

	const DIRECTION_HORIZONTAL = 0;
	const DIRECTION_VERTICAL = 1;

	/**
	 * Default length of the ship
	 * @var int
	 */
	protected $length;

	/**
	 * Points with coordinates from the grid, where the ship is located
	 * @var array
	 */
	protected $points = array();
	
	/**
	 * Coordinates of the ship hits
	 * @var aray
	 */
	protected $hits = array();

	/**
	 * Gets the size of the ship
	 * @return int
	 */
	public function getLengh() {
		return $this->length;
	}

	/**
	 * Sets the points of the ship
	 * @param array $points Array with coordinates
	 */
	public function setPoints(Array $points) {
		$this->points = $points;
	}

	/**
	 * Checks if the ship has this coordinates
	 * @param array $coordinates
	 * @return boolean
	 */
	public function isThere(Array $coordinates) {
		if ($this->isPlaced()) {
			return in_array($coordinates, $this->points);
		}
		return false;
	}

	/**
	 * Checks if the ship is placed on the board
	 * @return boolean
	 */
	public function isPlaced() {
		return count($this->points) === $this->getLengh();
	}

	/**
	 * Try to hit the ship
	 * @param array $coordinates
	 * @return boolean
	 */
	public function hit(Array $coordinates) {
		if ($this->isThere($coordinates)) {
			if (!in_array($coordinates, $this->hits)) {
				$this->hits[] = $coordinates;
				return true;
			}
		}
		return false;
	}

	/**
	 * Checks if the ship has been sunk
	 * @return boolean
	 */
	public function isSunk() {
		return count($this->hits) === count($this->points);
	}

}

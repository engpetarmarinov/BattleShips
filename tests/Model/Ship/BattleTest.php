<?php

namespace tests\Model\Ship;

use Model\Ship;

class BattleTest extends \PHPUnit_Framework_TestCase {

	public function testIsPlaced() {
		//placed
		$ship = new Ship\Battle();
		$points = array();
		for($i=1; $i <= $ship->getLengh(); $i++) {
			$points[] = array(1,$i);
		}
		$ship->setPoints($points);
		$this->assertTrue($ship->isPlaced());
		//not placed
		$ship2 = new Ship\Battle();
		$this->assertFalse($ship2->isPlaced());
	}
	
	public function testIsThere() {
		$ship = new Ship\Battle();
		$points = array();
		for($i=1; $i <= $ship->getLengh(); $i++) {
			$points[] = array(1,$i);
		}
		$ship->setPoints($points);
		for($i=1; $i <= $ship->getLengh(); $i++) {
			$this->assertTrue($ship->isThere(array(1,$i)));
		}		
		$this->assertFalse($ship->isThere(array(2,1)));
	}
	
	public function testHit() {
		$ship = new Ship\Battle();
		$points = array();
		for($i=1; $i <= $ship->getLengh(); $i++) {
			$points[] = array($i,1);
		}
		$ship->setPoints($points);
		
		$this->assertTrue($ship->hit(array(3,1)));
		$this->assertFalse($ship->hit(array($ship->getLengh()+1,1)));
	}
	
	public function testIsSunk() {
		$ship = new Ship\Battle();
		$points = array();
		for($i=1; $i <= $ship->getLengh(); $i++) {
			$points[] = array($i,5);
		}
		$ship->setPoints($points);
		$this->assertFalse($ship->isSunk());
		$ship->hit(array(1,5));
		$this->assertFalse($ship->isSunk());
		for($i=2; $i <= $ship->getLengh(); $i++) {
			$ship->hit(array($i,5));
		}
		$this->assertTrue($ship->isSunk());		
	}
	
}

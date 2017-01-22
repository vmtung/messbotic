<?php
class test2{
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	public function getTest123($name, $price){
		return new test2($name, $price);
	}
}
<?php
	class other {
		public $name;
		public $price;
		public function __construct($name, $price) {
			$this->name = $name;
			$this->price = $price;
		}
	} 
	function getHdd($price){
		//query list hdd base on price.
		//get hdd has closest price.
		return new Hdd($name, $price);
	}
?>

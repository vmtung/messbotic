<?php
class Psu {
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	function getPsuInformation($limitPrice, $psuWatt) {
		// get vga has closest price
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/PSU.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$psuData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $psuData ) {
			$value = floatval ( $psuData ['Price'] );
			$psuCapacity = intval($psuWatt);
			if ($value <= $limitPrice && $value > 0 && $psuCapacity * 2 >= $psuData['1']) {
				array_push ( $data, $psuData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		return new Psu ( $name, $price);
	}
}
?>
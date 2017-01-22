<?php
class Ssd {
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	function getSsdInformation($limitPrice) {
		// 	if ($price > 0) {
		// 		if ($m2Slot) {
		// 			// Get ssd type = m2
		// 			// get ssd closest price
		// 			return new Ssd ( $name, $price );
		// 		} else {
		// 			// get ssd type = normal
		// 			// get ssd closest price.
		// 			return new Ssd ( $name, $price );
		// 		}
		// 	} else {
		// 		return null;
		// 	}
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/SSD.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$ssdData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $ssdData ) {
			$value = floatval ( $ssdData ['Price'] );
			if ($value <= $limitPrice && $value > 0) {
				array_push ( $data, $ssdData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		return new Ssd ( $name, $price);
	}
}

?>
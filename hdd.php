<?php
class Hdd {
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	function getHddInformation($limitPrice) {
		// get vga has closest price
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/HDD.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$hddData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $hddData ) {
			$value = floatval ( $hddData ['Price'] );
			if ($value <= $limitPrice && $value > 0) {
				array_push ( $data, $hddData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		return new Hdd ( $name, $price);
	}
}

?>

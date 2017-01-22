<?php
class Cooling {
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	function getCoolingInformation($limitPrice) {
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/Cooling.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$coolingData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $coolingData ) {
			$value = floatval ( $coolingData ['Price'] );
			if ($value <= $limitPrice && $value > 0) {
				array_push ( $data, $coolingData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		return new Cooling ( $name, $price);
	}
}

?>

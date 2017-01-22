<?php
class Ram {
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	function getRamInformation($limitPrice, $ramType) {
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/Ram.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$ramData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $ramData ) {
			$value = floatval ( $ramData ['Price'] );
			if ($value <= $limitPrice && $value > 0 && $ramType === $ramData ['Ramtype']) {
				array_push ( $data, $ramData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
	
		return new Ram ( $name, $price );
	}
}

?>
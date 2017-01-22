<?php
class ComputerCase {
	public $name;
	public $price;
	public function __construct($name, $price) {
		$this->name = $name;
		$this->price = $price;
	}
	function getCaseInformation($limitPrice) {
		// get ComputerCase has closest price
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/Case.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$caseData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $caseData ) {
			$value = floatval ( $caseData ['Price'] );
			if ($value <= $limitPrice && $value > 0) {
				array_push ( $data, $caseData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		$psuName = $data [0] ['Watt'];
		return new ComputerCase ( $name, $price, $psuName );
	}
}

?>
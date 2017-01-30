<?php

class Mainboard {
	public $name;
	public $price;
	public $ramType;
	public $ramSlot;
	public $haveM2Slot;
	public function __construct($name, $price, $ramType, $ramSlot, $haveM2Slot) {
		$this->name = $name;
		$this->price = $price;
		$this->ramType = $ramType;
		$this->ramSlot = $ramSlot;
		$this->haveM2Slot = $haveM2Slot;
	}
	public function getMainboardInformation($limitPrice, $socket) {
		// Lọc theo $socket & Danh mục = Mainboard
		// Loc theo price <= price of #3 ---> get closest price
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/Mainboard.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$mainboardData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $mainboardData ) {
			$value = floatval ( $mainboardData ['Price'] );
			$socketData = $mainboardData['Socket'];
			if ($value <= $limitPrice && $value > 0 && $socket === $socketData) {
				array_push ( $data, $mainboardData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		$ramType = $data [0] ['DDR type'];
		$ramSlot = $data [0] ['RAM slot'];
		$haveM2Slot = $data [0] ['M2 slot'];
	
		return new Mainboard ( $name, $price, $ramType, $ramSlot, $haveM2Slot );
		
	}
}

?>
<?php include_once 'sort.php'?>
<?php
class Vga {
	public $name;
	public $price;
	public $psuName;
	public function __construct($name, $price, $psuName) {
		$this->name = $name;
		$this->price = $price;
		$this->psuName = $psuName;
	}
	function getVga($limitPrice) {
		// get vga has closest price
		$rows = array_map ( 'str_getcsv', file ( 'data/computer/VGA.csv' ) );
		$header = array_shift ( $rows );
		$csv = array ();
		$data = array ();
		$vgaData = array ();
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $vgaData ) {
			$value = floatval ( $vgaData ['Price'] );
			if ($value <= $limitPrice && $value > 0) {
				array_push ( $data, $vgaData );
			}
		}
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		$name = $data [0] ['Name'];
		$price = $data [0] ['Price'];
		$psuName = $data [0] ['Watt'];
		return new Vga ( $name, $price, $psuName );
	}
}

?>
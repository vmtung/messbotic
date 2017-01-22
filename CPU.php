<?php
// CPU:
class Cpu {
	public $name;
	public $price;
	public $socket;
	public function __construct($name, $price, $socket) {
		$this->name = $name;
		$this->price = $price;
		$this->socket = $socket;
	}
	public function getCpuInformation($limitPrice, $purpose) {
		// get list of cpu & get closest price.
		$cpuResult;
		$typeCpu = Array (
				'E5',
				'E3',
				'i7',
				'i5',
				'i3',
				'G'
				);
		$start = 0;
		if ($purpose === 'Game') {
			$start = 2;
		}
		for($i = $start; $i < sizeof ( $typeCpu ); $i ++) {
			$cpuResult = getCpu ( $typeCpu [$i], $limitPrice );
			if (! empty ( $cpuResult ) || isset ( $cpuResult )) {
				$name = $cpuResult ['Name'];
				$price = $cpuResult ['Price'];
				$socket = $cpuResult ['Socket'];
				return new Cpu($name, $price, $socket);
				break;
			}
		}
	}
}
function getCpu($type, $limitPrice) {
	$rows = array_map ( 'str_getcsv', file ( 'data/computer/CPU.csv' ) );
	$header = array_shift ( $rows );
	$csv = array ();
	$data = array ();
	$cpuData = array ();
	foreach ( $rows as $row ) {
		$csv [] = array_combine ( $header, $row );
	}
	
	foreach ( $csv as $cpuData ) {
		$value = floatval ( $cpuData ['Price'] );
		$socketData = substr ( $cpuData ['Socket'], 0, 4 );
		if ($value <= $limitPrice && $value > 0 && $cpuData ['Type'] === $type && $socketData === '2011') {
			array_push ( $data, $cpuData );
		} else if ($value <= $limitPrice && $value > 0 && $cpuData ['Type'] === $type) {
			array_push ( $data, $cpuData );
		}
	}
	if (empty($data)) {
		return null;
	} else {
		$sort = new sort();
		$sort->sortArray ( $data, 'Price' );
		
		return $data[0];
	}
}


?>
<?php
function __autoload($url) {
	require ("$url.php");
}
// if (isset ( $_GET ['budget'] ) && isset ( $_GET ['mucdich'] )) {
	// get purpose and budget
	$purpose = 'Game';
	$budget = 25000000;
	$rows;
	
	// retrieve data from csv file
	if ($purpose === 'Game') {
		$rows = array_map ( 'str_getcsv', file ( 'data/purpose/Game.csv' ) );
	} else if ($purpose === 'Work') {
		$rows = array_map ( 'str_getcsv', file ( 'data/purpose/Work.csv' ) );
	}
	
	$header = array_shift ( $rows );
	$csv = array ();
	$data = array ();
	$test = array ();
	foreach ( $rows as $row ) {
		$csv [] = array_combine ( $header, $row );
	}
	foreach ( $csv as $test ) {
		if ($test ['total'] <= $budget) {
			array_push ( $data, $test );
		}
	}
	
	// sort data descending
	$sort = new sort();
	$sort->sortArray ( $data, 'total' );
	// print_r ( $data[0] );
	
	// Total price
	$total = $data [0] ['total'];
	
	// limit price of each component
	$cpuPrice = $data [0] ['cpu'] * $total;
	$heatsinkPrice = $data [0] ['heatsink'] * $total;
	$mainboardPrice = $data [0] ['mainboard'] * $total;
	$ramPrice = $data [0] ['ram'] * $total;
	$ssdPrice = $data [0] ['ssd'] * $total;
	$hddPrice = $data [0] ['hdd'] * $total;
	$vgaPrice = $data [0] ['vga'] * $total;
	$psuPrice = $data [0] ['psu'] * $total;
	$casePrice = $data [0] ['case'] * $total;
	$otherPrice = $data [0] ['other'] * $total;
	
	$totalPrice;
	
	// get cpu information
	$cpu = new cpu($name, $price, $socket);
	$cpuInfo = $cpu->getCpuInformation ( $cpuPrice, $purpose );
	$totalPrice += $cpuInfo->price;
	
	// get mainboard information
	$socket = $cpuInfo->socket;
	$mainboard = new mainboard($name, $price, $ramType, $ramSlot, $haveM2Slot);
	$mainboardInfo = $mainboard->getMainboardInformation ( $mainboardPrice, $socket );
	$totalPrice += $mainboardInfo->price;
	
	// get ram information
	$ramType = $mainboardInfo->ramType;
	$ram = new ram($name, $price);
	$ramInfo = $ram->getRamInformation ( $ramPrice, $ramType );
	$totalPrice += $ramInfo->price;
	
	// get vga information
	$vga = new vga($name, $price, $psuName);
	$vgaInfo = $vga->getVga ( $vgaPrice );
	$totalPrice += $vgaInfo->price;
	
	// get psu information
	$psuWatt = $vgaInfo->psuName;
	$psu = new psu($name, $price);
	$psuInfo = $psu->getPsuInformation ( $psuPrice, $psuWatt );
	$totalPrice += $psuInfo->price;
	
	// get hdd information
	$hdd = new hdd($name, $price);
	$hddInfo = $hdd->getHddInformation ( $hddPrice );
	$totalPrice += $hddInfo->price;
	
	// get ssd information
	$ssd = new ssd($name, $price);
	$ssdInfo = $ssd->getSsdInformation ( $ssdPrice );
	$totalPrice += $ssdInfo->price;
	
	// get case information
	$case = new computercase($name, $price);
	$caseInfo = $case->getCaseInformation ( $casePrice );
	$totalPrice += $caseInfo->price;
	
	// get cooling information
	$cooling = new cooling($name, $price);
	$coolingInfo = $cooling->getCoolingInformation ( $heatsinkPrice );
	$totalPrice += $coolingInfo->price;
	
	$message["messages"][]=["text"=>"CPU: ".$cpuInfo->name." ".number_format($cpuInfo->price)." vnd"];
	$message["messages"][]=["text"=>"Mainboard: ".$mainboardInfo->name." ".number_format($mainboardInfo->price)." vnd"];
	$message["messages"][]=["text"=>"RAM: ".$ramInfo->name." ".number_format($ramInfo->price)." vnd"];
	$message["messages"][]=["text"=>"VGA: ".$vgaInfo->name." ".number_format($vgaInfo->price)." vnd"];
	$message["messages"][]=["text"=>"Nguồn: ".$psuInfo->name." ".number_format($psuInfo->price)." vnd"];
	$message["messages"][]=["text"=>"HDD: ".$hddInfo->name." ".number_format($hddInfo->price)." vnd"];
	$message["messages"][]=["text"=>"SSD: ".$ssdInfo->name." ".number_format($ssdInfo->price)." vnd"];
	$message["messages"][]=["text"=>"Case: ".$caseInfo->name." ".number_format($caseInfo->price)." vnd"];
	$message["messages"][]=["text"=>"Tản Nhiệt ".$coolingInfo->name." ".number_format($coolingInfo->price)." vnd"];
	
	$message["messages"][]=["text"=>"Tổng: ".number_format($totalPrice,0,',','.')." vnd"];
	if ($totalPrice===0) {$message=["messages"=>[["text"=>"Hệ thống chưa tìm được phần cứng với mức giá bạn yêu cầu."]]];}
// 	$mess1 = json_encode($message, JSON_UNESCAPED_UNICODE);
// 	json_encode($message, JSON_UNESCAPED_SLASHES);
// 	json_encode($message, JSON_PRETTY_PRINT);
	echo json_encode($message);
// }
?>

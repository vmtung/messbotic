<?php
function __autoload($url) {
	require ("$url.php");
}
if (isset ( $_GET ['budget'] ) && isset ( $_GET ['mucdich'] )) {
	// get purpose and budget
	$purpose = $_GET ['mucdich'];
	$budget;
	$budgetinput = $_GET ['budget'];
	$check = false;
	$rows;
	$required = Array('t', 'tr', 'triệu', 'trieu');
	for ($i = 0; $i < sizeof($required);$i++){
		if (preg_match('/'.$required[$i].'/', $budgetinput) === 1){
			$budget = intval(substr($budgetinput, 0, strpos($budgetinput, $required[$i]))) * 1000000;
			$check = true;
		}
	}
	if (! $check) {
	if (strlen ( $budgetinput ) <= 3) {
		$budget = $budgetinput * 1000000;
		$check = true;
	} else if (strlen ( $budgetinput ) >= 7) {
		$budget = $budgetinput;
		$check = true;
	}
}
	// retrieve data from csv file
	$csv = array ();
	$data = array ();
	$eachprice = Array();
	if ($purpose === 'Game') {
		$rows = array_map ( 'str_getcsv', file ( 'data/purpose/Fix.csv' ) );
		$header = array_shift ( $rows );
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
		foreach ( $csv as $data ) {
			if ($budget === intval($data['Budget'])){
				echo $data['Budget']."\n";
				$eachprice = $data;
			}
		}
		if (empty($eachprice)) {
			$rows = array_map ( 'str_getcsv', file ( 'data/purpose/Game.csv' ) );
		}
	} else if ($purpose === 'Work') {
		$rows = array_map ( 'str_getcsv', file ( 'data/purpose/Work.csv' ) );
	}
	
	//message output
	$message = ["messages"=>[["attachment"=>["type"=>"template","payload"=>["template_type"=>"generic",]]]]];
	$totalPrice;
	
	if (!empty($eachprice)) {
		$totalPrice = $eachprice['Total'];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"CPU: ".$eachprice['CPU']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Tản nhiệt: ".$eachprice['Cooling']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Mainboard: ".$eachprice['mainboard']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"RAM: ".$eachprice['Ram']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"SSD: ".$eachprice['SSD']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"HDD: ".$eachprice['HDD']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"VGA: ".$eachprice['VGA']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"PSU: ".$eachprice['PSU']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Case: ".$eachprice['Case']];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>$eachprice['Others']];
	} else {

		$header = array_shift ( $rows );
	
		foreach ( $rows as $row ) {
			$csv [] = array_combine ( $header, $row );
		}
	
		foreach ( $csv as $data ) {
			if (abs($budget - $eachprice['total']) > abs($data['total'] - $budget)){
				$eachprice = $data;
			}
		}
	
		// estimate price of each component
		$cpuPrice = $eachprice ['cpu'] * $budget;
		$heatsinkPrice = $eachprice ['heatsink'] * $budget;
		$mainboardPrice = $eachprice ['mainboard'] * $budget;
		$ramPrice = $eachprice ['ram'] * $budget;
		$ssdPrice = $eachprice ['ssd'] * $budget;
		$hddPrice = $eachprice ['hdd'] * $budget;
		$vgaPrice = $eachprice ['vga'] * $budget;
		$psuPrice = $eachprice ['psu'] * $budget;
		$casePrice = $eachprice ['case'] * $budget;
		$otherPrice = $eachprice ['other'] * $budget;
		
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
		
		$message = ["messages"=>[["attachment"=>["type"=>"template","payload"=>["template_type"=>"generic",]]]]];
	
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"CPU: ".$cpuInfo->name, "subtitle"=>number_format($cpuInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Mainboard: ".$mainboardInfo->name,"subtitle"=>number_format($mainboardInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"RAM: ".$ramInfo->name,"subtitle"=>number_format($ramInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"VGA: ".$vgaInfo->name,"subtitle"=>number_format($vgaInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Nguồn: ".$psuInfo->name,"subtitle"=>number_format($psuInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"HDD: ".$hddInfo->name,"subtitle"=>number_format($hddInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"SSD: ".$ssdInfo->name,"subtitle"=>number_format($ssdInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Case: ".$caseInfo->name,"subtitle"=>number_format($caseInfo->price)." vnd"];
		$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>"Tản Nhiệt: ".$coolingInfo->name,"subtitle"=>number_format($coolingInfo->price)." vnd"];
	}
	
	$message["messages"][]=["text"=>"Tổng: ".number_format($totalPrice,0,',','.')." vnd"];
	if ($totalPrice===0) {$message=["messages"=>[["text"=>"Hệ thống chưa tìm được phần cứng với mức giá bạn yêu cầu."]]];}
// 	$mess1 = json_encode($message, JSON_UNESCAPED_UNICODE);
// 	json_encode($message, JSON_UNESCAPED_SLASHES);
// 	json_encode($message, JSON_PRETTY_PRINT);
	echo json_encode($message);
}
?>

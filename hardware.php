<?php
if (isset($_GET['budget']) && isset($_GET['mucdich'])) {
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	$budget = $_GET['budget'];
	$mucdich = $_GET['mucdich'];
	$f = file('./hardwareinfo.txt');
	$data = array_map("str_getcsv",$f);
	$index=1;
	$temp=0;
	foreach ($data[0] as $key => $value) {
		if (strpos(strtolower($value),strtolower($mucdich))!==false) {
			if ($temp===0) {$temp=$key;}
			if ($budget>=$data[1][$key]) {
				$index = $key;
			}
		}

	}
	if ($index==1) {$index=$temp;}

	$invest = [];

	for ($i=2; $i<sizeof($data)-1; $i++) {
	  $invest[$data[$i][0]]=$data[$i][$index]*$budget;
	}
	
	$result = [];

	foreach ($invest as $key => $value) {
		$result[$key]=0;
	}

	$banggia = file('./banggia.txt');
	$bg_data = array_map('str_getcsv',$banggia);
	for ($i=1; $i<sizeof($bg_data); $i++) {
		foreach ($invest as $key => $price) {
			if (strpos(strtolower($bg_data[$i][0]),strtolower($key))!==false && $price>=$bg_data[$i][2]) {
				$result[$key] = $i;
				break;
			}
		}
	}
	
	$sum=0;
	$message = ["messages"=>[["attachment"=>["type"=>"template","payload"=>["template_type"=>"generic",]]]]];
	foreach($result as $key => $index) {
			if ($index!=0) {
				$sum+=$bg_data[$index][2];
				$message["messages"][0]["attachment"]["payload"]["elements"][]=["title"=>$bg_data[$index][1],"subtitle"=>number_format($bg_data[$index][2],0,',','.')."đ"];
		}
	}
	$message["messages"][]=["text"=>"Tổng: ".number_format($sum,0,',','.')."đ"];
	if ($sum===0) {$message=["messages"=>[["text"=>"Hệ thống chưa tìm được phần cứng với mức giá bạn yêu cầu."]]];}
	echo json_encode($message);
}

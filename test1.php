<?php
function __autoload($url) {
	require ("$url.php");
}
$mainboard = new Mainboard($name, $price, $ramType, $ramSlot, $haveM2Slot);
$mainboardInfo = $mainboard->getMainboardInformation ( 5000000, 1155 );
echo $mainboardInfo->name;
?>

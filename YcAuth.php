<?php

include_once 'secken_private.class.php';

$power_id     = 'ubfjVKuV7HHKuGFYwyHG';
$power_key    = 'Q0eYeCju5wg9qSXHvEkkdSwhnqoHvaRO';

$secken_api = new secken_private("spc.secken.net", $power_id, $power_key);

if ($_GET["Action"] == "GetYcAuthQrCode") {
	$ret  = $secken_api->getQrCode();
	if ( $secken_api->getCode() != 200 ){
		$arr = array(
			'status'=> $secken_api->getCode(),
			'description' => $secken_api->getMessage(),
			);
		$json = json_encode($arr);
		echo $json;
	} else {
		$json = json_encode($ret);
		echo $json;
	}
	return;
}

if ($_GET["Action"] == "CheckYcAuthResult") {
	$event_id = $_GET["eid"];

	$ret  = $secken_api->getResult($event_id);

	if ( $secken_api->getCode() != 200 ){
		$arr = array(
			'status'=> $secken_api->getCode(),
			'description' => $secken_api->getMessage(),
			);
		$json = json_encode($arr);
		echo $json;
	} else {
		$json = json_encode($ret);
		echo $json;
	}

	return;
}


if ($_GET["Action"] == "AskYangAuthPush") {
	$username = $_GET["username"];

	$ret = $secken_api->askPushAuth($username);

	if ( $secken_api->getCode() != 200 ){
		$arr = array(
			'status'=> $secken_api->getCode(),
			'description' => $secken_api->getMessage(),
			);
		$json = json_encode($arr);
		echo $json;
	} else {
		$json = json_encode($ret);
		echo $json;
	}

	return;
}

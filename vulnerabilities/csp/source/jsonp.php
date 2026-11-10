<?php
header("Content-Type: application/json; charset=UTF-8");

if (array_key_exists("callback", $_GET)) {
	if ($_GET['callback'] === 'solveSum') {
		$callback = 'solveSum';
	} else {
		$callback = 'callback';
	}
} else {
	return "";
}

$outp = array ("answer" => "15");

echo $callback . "(".json_encode($outp).")";
?>

<?php
define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

/*
Only the admin user is allowed to update user details.
*/
if (dvwaCurrentUser() != "admin") {
	print json_encode (array ("result" => "fail", "error" => "Access denied"));
	exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	$result = array (
						"result" => "fail",
						"error" => "Only POST requests are accepted"
					);
	echo json_encode($result);
	exit;
}

try {
	$json = file_get_contents('php://input');
	$data = json_decode($json);
	if (is_null ($data)) {
		$result = array (
							"result" => "fail",
							"error" => 'Invalid format, expecting "{id: {user ID}, first_name: "{first name}", surname: "{surname}" }'

						);
		echo json_encode($result);
		exit;
	}
} catch (Exception $e) {
	$result = array (
						"result" => "fail",
						"error" => 'Invalid format, expecting \"{id: {user ID}, first_name: "{first name}", surname: "{surname}\"}'

					);
	echo json_encode($result);
	exit;
}

if (!is_object($data) || !isset($data->id) || !isset($data->first_name) || !isset($data->surname)) {
	$result = array (
						"result" => "fail",
						"error" => 'Invalid format, expecting "{id: {user ID}, first_name: "{first name}", surname: "{surname}" }'
					);
	echo json_encode($result);
	exit;
}

$expected_host = '';
if (isset($_SERVER['HTTP_HOST']) && is_string($_SERVER['HTTP_HOST'])) {
	$expected_host = $_SERVER['HTTP_HOST'];
} elseif (isset($_SERVER['SERVER_NAME']) && is_string($_SERVER['SERVER_NAME'])) {
	$expected_host = $_SERVER['SERVER_NAME'];
}

$source = '';
if (isset($_SERVER['HTTP_ORIGIN']) && is_string($_SERVER['HTTP_ORIGIN'])) {
	$source = $_SERVER['HTTP_ORIGIN'];
} elseif (isset($_SERVER['HTTP_REFERER']) && is_string($_SERVER['HTTP_REFERER'])) {
	$source = $_SERVER['HTTP_REFERER'];
}

$same_origin = false;
if ($source !== '' && $expected_host !== '') {
	$source_url = parse_url($source);
	if (is_array($source_url) && isset($source_url['host']) && is_string($source_url['host'])) {
		$source_host = $source_url['host'];
		if (isset($source_url['port'])) {
			$source_host .= ':' . $source_url['port'];
		}
		if (strcasecmp($source_host, $expected_host) === 0) {
			$same_origin = true;
		}
	}
}

if (!$same_origin) {
	$result = array (
						"result" => "fail",
						"error" => "Request rejected"
					);
	echo json_encode($result);
	exit;
}

$query = "UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?";
$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
$first_name = $data->first_name;
$surname = $data->surname;
$id = (int) $data->id;
mysqli_stmt_bind_param($stmt, "ssi", $first_name, $surname, $id);
mysqli_stmt_execute($stmt) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
mysqli_stmt_close($stmt);

print json_encode (array ("result" => "ok"));
exit;
?>

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

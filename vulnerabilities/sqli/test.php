<?php
$host = "192.168.0.7";
$username = "dvwa";
$password = "password";

mssql_connect($host, $username, $password);
mssql_select_db($database);

$query ="SELECT * FROM users";
$result =mssql_query($query);
while ( $record = mssql_fetch_array($result) ) {
	echo htmlspecialchars($record["first_name"], ENT_QUOTES, 'UTF-8') ."<br />";
}
?>

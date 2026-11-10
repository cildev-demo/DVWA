<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$id = $_POST[ 'id' ];
	$exists = false;

	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
			// Check database
			$query  = "SELECT first_name, last_name FROM users WHERE user_id = ?;";
			try {
				$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
				if ($stmt !== false) {
					mysqli_stmt_bind_param($stmt, "s", $id);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					$exists = (mysqli_stmt_num_rows($stmt) > 0);
					mysqli_stmt_close($stmt);
				}
			} catch (Exception $e) {
				$exists = false;
			}

			break;
		case SQLITE:
			global $sqlite_db_connection;

			$query  = "SELECT first_name, last_name FROM users WHERE user_id = :id;";
			try {
				$stmt = $sqlite_db_connection->prepare($query);
				if ($stmt !== false) {
					$stmt->bindValue(':id', $id, SQLITE3_TEXT);
					$results = $stmt->execute();
					if ($results !== false) {
						$row = $results->fetchArray();
						$exists = $row !== false;
						$results->finalize();
					}
					$stmt->close();
				}
			} catch(Exception $e) {
				$exists = false;
			}
			break;
	}

	if ($exists) {
		// Feedback for end user
		$html .= '<pre>User ID exists in the database.</pre>';
	} else {
		// Feedback for end user
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}

?>

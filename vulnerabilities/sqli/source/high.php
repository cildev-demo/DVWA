<?php

if( isset( $_SESSION [ 'id' ] ) ) {
	// Get input
	$id = $_SESSION[ 'id' ];

	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
			// Check database
			$query  = "SELECT first_name, last_name FROM users WHERE user_id = ? LIMIT 1;";
			$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query) or die( '<pre>Something went wrong.</pre>' );
			mysqli_stmt_bind_param($stmt, "s", $id);
			mysqli_stmt_execute($stmt) or die( '<pre>Something went wrong.</pre>' );
			$result = mysqli_stmt_get_result($stmt);

			// Get results
			while( $row = mysqli_fetch_assoc( $result ) ) {
				// Get values
				$first = $row["first_name"];
				$last  = $row["last_name"];

				// Feedback for end user
				$safe_id = htmlspecialchars( $id, ENT_QUOTES, 'UTF-8' );
				$safe_first = htmlspecialchars( $first, ENT_QUOTES, 'UTF-8' );
				$safe_last = htmlspecialchars( $last, ENT_QUOTES, 'UTF-8' );
				$html .= "<pre>ID: {$safe_id}<br />First name: {$safe_first}<br />Surname: {$safe_last}</pre>";
			}

			mysqli_stmt_close($stmt);
			((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);		
			break;
		case SQLITE:
			global $sqlite_db_connection;

			$query  = "SELECT first_name, last_name FROM users WHERE user_id = ? LIMIT 1;";
			#print $query;
			try {
				$stmt = $sqlite_db_connection->prepare($query);
				$stmt->bindValue(1, $id, SQLITE3_TEXT);
				$results = $stmt->execute();
			} catch (Exception $e) {
				echo 'Caught exception: ' . $e->getMessage();
				exit();
			}

			if ($results) {
				while ($row = $results->fetchArray()) {
					// Get values
					$first = $row["first_name"];
					$last  = $row["last_name"];

					// Feedback for end user
					$safe_id = htmlspecialchars( $id, ENT_QUOTES, 'UTF-8' );
					$safe_first = htmlspecialchars( $first, ENT_QUOTES, 'UTF-8' );
					$safe_last = htmlspecialchars( $last, ENT_QUOTES, 'UTF-8' );
					$html .= "<pre>ID: {$safe_id}<br />First name: {$safe_first}<br />Surname: {$safe_last}</pre>";
				}
			} else {
				echo "Error in fetch ".$sqlite_db->lastErrorMsg();
			}
			break;
	}
}

?>

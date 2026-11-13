<?php

if( isset( $_POST[ 'Change' ] ) &&
	isset( $_POST[ 'user_token' ] ) &&
	isset( $_POST[ 'password_new' ] ) &&
	isset( $_POST[ 'password_conf' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_POST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Get input
	$pass_new  = $_POST[ 'password_new' ];
	$pass_conf = $_POST[ 'password_conf' ];

	// Do the passwords match?
	if( $pass_new == $pass_conf ) {
		// They do!
		$pass_new = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass_new ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$pass_new = md5( $pass_new );

		// Update the database
		$current_user = dvwaCurrentUser();
		$insert = "UPDATE `users` SET password = ? WHERE user = ?;";
		$stmt = mysqli_prepare( $GLOBALS["___mysqli_ston"], $insert );
		if( !$stmt ) {
			die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
		}
		mysqli_stmt_bind_param( $stmt, "ss", $pass_new, $current_user );
		if( !mysqli_stmt_execute( $stmt ) ) {
			die( '<pre>' . mysqli_stmt_error( $stmt ) . '</pre>' );
		}
		mysqli_stmt_close( $stmt );

		// Feedback for the user
		$html .= "<pre>Password Changed.</pre>";
	}
	else {
		// Issue with passwords matching
		$html .= "<pre>Passwords did not match.</pre>";
	}

	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

// Generate Anti-CSRF token
generateSessionToken();

?>

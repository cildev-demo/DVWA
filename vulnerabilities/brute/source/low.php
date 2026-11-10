<?php

if( isset( $_GET[ 'Login' ] ) ) {
	// Get username
	$user = $_GET[ 'username' ];

	// Get password
	$pass = md5( $_GET[ 'password' ] );

	// Check the database
	$stmt = mysqli_prepare( $GLOBALS["___mysqli_ston"], "SELECT * FROM `users` WHERE user = ? AND password = ?" );
	if( $stmt === false ) {
		die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
	}
	mysqli_stmt_bind_param( $stmt, "ss", $user, $pass );
	mysqli_stmt_execute( $stmt );
	$result = mysqli_stmt_get_result( $stmt );

	if( $result && mysqli_num_rows( $result ) == 1 ) {
		// Get users details
		$row    = mysqli_fetch_assoc( $result );
		$avatar = $row["avatar"];

		// Login successful
		$html .= "<p>Welcome to the password protected area " . htmlspecialchars( $user, ENT_QUOTES, 'UTF-8' ) . "</p>";
		$html .= "<img src=\"" . htmlspecialchars( $avatar, ENT_QUOTES, 'UTF-8' ) . "\" />";
	}
	else {
		// Login failed
		$html .= "<pre><br />Username and/or password incorrect.</pre>";
	}

	mysqli_stmt_close( $stmt );
	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

?>

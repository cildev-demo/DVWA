<?php

if( isset( $_GET[ 'Change' ] ) ) {
	// Verify that the request came from this application.
	$expected_host = isset( $_SERVER[ 'SERVER_NAME' ] ) ? $_SERVER[ 'SERVER_NAME' ] : '';
	$source        = '';
	$same_origin   = false;

	if( isset( $_SERVER[ 'HTTP_ORIGIN' ] ) && is_string( $_SERVER[ 'HTTP_ORIGIN' ] ) ) {
		$source = $_SERVER[ 'HTTP_ORIGIN' ];
	}
	elseif( isset( $_SERVER[ 'HTTP_REFERER' ] ) && is_string( $_SERVER[ 'HTTP_REFERER' ] ) ) {
		$source = $_SERVER[ 'HTTP_REFERER' ];
	}

	if( $source !== '' && $expected_host !== '' ) {
		$source_url = parse_url( $source );
		if( is_array( $source_url ) && isset( $source_url[ 'host' ] ) && strcasecmp( $source_url[ 'host' ], $expected_host ) === 0 ) {
			$same_origin = true;
		}
	}

	if( $same_origin ) {
		// Get input
		$pass_new  = $_GET[ 'password_new' ];
		$pass_conf = $_GET[ 'password_conf' ];

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
	}
	else {
		// Didn't come from a trusted source
		$html .= "<pre>That request didn't look correct.</pre>";
	}

	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

?>

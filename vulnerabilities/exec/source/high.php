<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = trim( $_REQUEST[ 'ip' ] );

	// Validate the input as an IP address.
	if( filter_var( $target, FILTER_VALIDATE_IP ) === false ) {
		$html = '<pre>Please enter a valid IP address.</pre>';
	}
	else {
		// Escape the validated input before using it in the shell command.
		$target = escapeshellarg( $target );

		// Determine OS and execute the ping command.
		if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
			// Windows
			$cmd = shell_exec( 'ping  ' . $target );
		}
		else {
			// *nix
			$cmd = shell_exec( 'ping  -c 4 ' . $target );
		}

		// Feedback for the end user
		$html .= '<pre>' . htmlspecialchars( $cmd, ENT_QUOTES, 'UTF-8' ) . '</pre>';
	}
}

?>

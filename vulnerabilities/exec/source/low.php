<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = false;
	if( isset( $_REQUEST[ 'ip' ] ) && is_string( $_REQUEST[ 'ip' ] ) ) {
		$target = filter_var( $_REQUEST[ 'ip' ], FILTER_VALIDATE_IP );
	}

	if( $target !== false ) {
		// Determine OS and execute the ping command.
		if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
			// Windows
			$cmd = shell_exec( 'ping  ' . escapeshellarg( $target ) );
		}
		else {
			// *nix
			$cmd = shell_exec( 'ping  -c 4 ' . escapeshellarg( $target ) );
		}

		// Feedback for the end user
		$html .= '<pre>' . htmlspecialchars( (string) $cmd, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' ) . '</pre>';
	}
}

?>

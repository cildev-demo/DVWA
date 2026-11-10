<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = $_REQUEST[ 'ip' ];
	$cmd = '';

	// Determine OS and execute the ping command for an allowed target.
	if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
		// Windows
		if( $target === '127.0.0.1' ) {
			$cmd = shell_exec( 'ping  127.0.0.1' );
		} elseif( $target === 'localhost' ) {
			$cmd = shell_exec( 'ping  localhost' );
		}
	}
	else {
		// *nix
		if( $target === '127.0.0.1' ) {
			$cmd = shell_exec( 'ping  -c 4 127.0.0.1' );
		} elseif( $target === 'localhost' ) {
			$cmd = shell_exec( 'ping  -c 4 localhost' );
		}
	}

	// Feedback for the end user
	$html .= '<pre>' . htmlspecialchars( (string) $cmd, ENT_QUOTES, 'UTF-8' ) . '</pre>';
}

?>

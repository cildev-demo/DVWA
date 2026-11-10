<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Where are we going to be writing to?
	$target_path = '';

	// File information
	$uploaded_name = isset( $_FILES[ 'uploaded' ][ 'name' ] ) ? $_FILES[ 'uploaded' ][ 'name' ] : '';
	$uploaded_ext  = strtolower( substr( $uploaded_name, strrpos( $uploaded_name, '.' ) + 1 ) );

	// Select a fixed target name from the validated extension.
	if( $uploaded_ext == 'jpg' || $uploaded_ext == 'jpeg' ) {
		$target_path = DVWA_WEB_PAGE_TO_ROOT . 'hackable/uploads/upload.jpg';
	}
	elseif( $uploaded_ext == 'png' ) {
		$target_path = DVWA_WEB_PAGE_TO_ROOT . 'hackable/uploads/upload.png';
	}

	// Can we move the file to the upload folder?
	if( $target_path == '' || !getimagesize( $_FILES[ 'uploaded' ][ 'tmp_name' ] ) || !move_uploaded_file( $_FILES[ 'uploaded' ][ 'tmp_name' ], $target_path ) ) {
		// No
		$html .= '<pre>Your image was not uploaded.</pre>';
	}
	else {
		// Yes!
		$html .= "<pre>{$target_path} succesfully uploaded!</pre>";
	}
}

?>

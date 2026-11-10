<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Where are we going to be writing to?
	$target_path = '';

	// File information
	$uploaded_name = $_FILES[ 'uploaded' ][ 'name' ];
	$uploaded_size = $_FILES[ 'uploaded' ][ 'size' ];
	$uploaded_tmp  = $_FILES[ 'uploaded' ][ 'tmp_name' ];

	// Determine the file extension
	$uploaded_ext = strtolower( pathinfo( $uploaded_name, PATHINFO_EXTENSION ) );

	// Choose a fixed filename based on the validated extension
	if( $uploaded_ext == "jpg" || $uploaded_ext == "jpeg" ) {
		$target_path = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/uploaded.jpg";
	}
	elseif( $uploaded_ext == "png" ) {
		$target_path = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/uploaded.png";
	}

	// Is it an image?
	if( $target_path != "" &&
		( $uploaded_size < 100000 ) &&
		getimagesize( $uploaded_tmp ) ) {

		// Can we move the file to the upload folder?
		if( !move_uploaded_file( $uploaded_tmp, $target_path ) ) {
			// No
			$html .= '<pre>Your image was not uploaded.</pre>';
		}
		else {
			// Yes!
			$html .= "<pre>{$target_path} succesfully uploaded!</pre>";
		}
	}
	else {
		// Invalid file
		$html .= '<pre>Your image was not uploaded. We can only accept JPEG or PNG images.</pre>';
	}
}

?>

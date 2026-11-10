<?php

 define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: File Inclusion' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'fi';
$page[ 'help_button' ]   = 'fi';
$page[ 'source_button' ] = 'fi';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/fi/source/{$vulnerabilityFile}";

// if( count( $_GET ) )
if( !isset( $file ) ) {
	header( 'Location:?page=include.php' );
	exit;
}

switch( $file ) {
	case 'file1.php':
		include __DIR__ . '/file1.php';
		break;
	case 'file2.php':
		include __DIR__ . '/file2.php';
		break;
	case 'file3.php':
		include __DIR__ . '/file3.php';
		break;
	case 'file4.php':
		include __DIR__ . '/file4.php';
		break;
	case 'include.php':
		include __DIR__ . '/include.php';
		break;
	default:
		header( 'Location:?page=include.php' );
		exit;
}

dvwaHtmlEcho( $page );

?>

<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= 'Source' . $page[ 'title_separator' ].$page[ 'title' ];

if (array_key_exists ("id", $_GET) && array_key_exists ("security", $_GET)) {
	$id       = '';
	$vuln     = 'Unknown Vulnerability';

	switch ($_GET[ 'id' ]) {
		case "fi" :
			$id = 'fi';
			$vuln = 'File Inclusion';
			break;
		case "brute" :
			$id = 'brute';
			$vuln = 'Brute Force';
			break;
		case "csrf" :
			$id = 'csrf';
			$vuln = 'CSRF';
			break;
		case "exec" :
			$id = 'exec';
			$vuln = 'Command Injection';
			break;
		case "sqli" :
			$id = 'sqli';
			$vuln = 'SQL Injection';
			break;
		case "sqli_blind" :
			$id = 'sqli_blind';
			$vuln = 'SQL Injection (Blind)';
			break;
		case "upload" :
			$id = 'upload';
			$vuln = 'File Upload';
			break;
		case "xss_r" :
			$id = 'xss_r';
			$vuln = 'Reflected XSS';
			break;
		case "xss_s" :
			$id = 'xss_s';
			$vuln = 'Stored XSS';
			break;
		case "weak_id" :
			$id = 'weak_id';
			$vuln = 'Weak Session IDs';
			break;
		case "javascript" :
			$id = 'javascript';
			$vuln = 'JavaScript';
			break;
		case "authbypass" :
			$id = 'authbypass';
			$vuln = 'Authorisation Bypass';
			break;
		case "open_redirect" :
			$id = 'open_redirect';
			$vuln = 'Open HTTP Redirect';
			break;
		case "bac":
			$id = 'bac';
			$vuln = 'Vulnerability: Broken Access Control';
			break;
		default:
			$id = '';
			$vuln = "Unknown Vulnerability";
	}

	$security = '';
	switch ($_GET[ 'security' ]) {
		case "low" :
			$security = 'low';
			break;
		case "medium" :
			$security = 'medium';
			break;
		case "high" :
			$security = 'high';
			break;
		case "impossible" :
			$security = 'impossible';
			break;
		default:
			$security = '';
	}

	$source = @file_get_contents( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/source/{$security}.php" );
	$source = str_replace( array( '$html .=' ), array( 'echo' ), $source );

	$js_html = "";
	if (file_exists (DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/source/{$security}.js")) {
		$js_source = @file_get_contents( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/source/{$security}.js" );
		$js_html = "
		<h2>vulnerabilities/{$id}/source/{$security}.js</h2>
		<div id=\"code\">
			<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
				<tr>
					<td><div id=\"code\">" . highlight_string( $js_source, true ) . "</div></td>
				</tr>
			</table>
		</div>
		";
	}

	$page[ 'body' ] .= "
	<div class=\"body_padded\">
		<h1>{$vuln} Source</h1>

		<h2>vulnerabilities/{$id}/source/{$security}.php</h2>
		<div id=\"code\">
			<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
				<tr>
					<td><div id=\"code\">" . highlight_string( $source, true ) . "</div></td>
				</tr>
			</table>
		</div>
		{$js_html}
		<br /> <br />

		<form>
			<input type=\"button\" value=\"Compare All Levels\" onclick=\"window.location.href='view_source_all.php?id=$id'\">
		</form>
	</div>\n";
} else {
	$page['body'] = "<p>Not found</p>";
}

dvwaSourceHtmlEcho( $page );

?>

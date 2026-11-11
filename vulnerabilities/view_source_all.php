<?php

define('DVWA_WEB_PAGE_TO_ROOT', '../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated'));

$page = dvwaPageNewGrab();
$page['title'] = 'Source' . $page['title_separator'] . $page['title'];

if (array_key_exists("id", $_GET)) {
	$id = '';
	$vuln = 'Unknown Vulnerability';

	switch ($_GET['id']) {
		case "javascript":
			$id = 'javascript';
			$vuln = 'JavaScript';
			break;
		case "fi":
			$id = 'fi';
			$vuln = 'File Inclusion';
			break;
		case "brute":
			$id = 'brute';
			$vuln = 'Brute Force';
			break;
		case "csrf":
			$id = 'csrf';
			$vuln = 'CSRF';
			break;
		case "exec":
			$id = 'exec';
			$vuln = 'Command Injection';
			break;
		case "sqli":
			$id = 'sqli';
			$vuln = 'SQL Injection';
			break;
		case "sqli_blind":
			$id = 'sqli_blind';
			$vuln = 'SQL Injection (Blind)';
			break;
		case "upload":
			$id = 'upload';
			$vuln = 'File Upload';
			break;
		case "xss_r":
			$id = 'xss_r';
			$vuln = 'Reflected XSS';
			break;
		case "xss_s":
			$id = 'xss_s';
			$vuln = 'Stored XSS';
			break;
		case "weak_id":
			$id = 'weak_id';
			$vuln = 'Weak Session IDs';
			break;
		case "authbypass":
			$id = 'authbypass';
			$vuln = 'Authorisation Bypass';
			break;
		case "open_redirect":
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

	$lowsrc = $medsrc = $highsrc = $impsrc = '';
	if ($id !== '') {
		$lowsrc = @file_get_contents("./{$id}/source/low.php");
		$lowsrc = str_replace(array('$html .='), array('echo'), $lowsrc);
		$lowsrc = highlight_string($lowsrc, true);

		$medsrc = @file_get_contents("./{$id}/source/medium.php");
		$medsrc = str_replace(array('$html .='), array('echo'), $medsrc);
		$medsrc = highlight_string($medsrc, true);

		$highsrc = @file_get_contents("./{$id}/source/high.php");
		$highsrc = str_replace(array('$html .='), array('echo'), $highsrc);
		$highsrc = highlight_string($highsrc, true);

		$impsrc = @file_get_contents("./{$id}/source/impossible.php");
		$impsrc = str_replace(array('$html .='), array('echo'), $impsrc);
		$impsrc = highlight_string($impsrc, true);
	}

	$page['body'] .= "
	<div class=\"body_padded\">
		<h1>{$vuln}</h1>
		<br />

		<h3>Impossible {$vuln} Source</h3>
		<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
			<tr>
				<td><div id=\"code\">{$impsrc}</div></td>
			</tr>
		</table>
		<br />

		<h3>High {$vuln} Source</h3>
		<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
			<tr>
				<td><div id=\"code\">{$highsrc}</div></td>
			</tr>
		</table>
		<br />

		<h3>Medium {$vuln} Source</h3>
		<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
			<tr>
				<td><div id=\"code\">{$medsrc}</div></td>
			</tr>
		</table>
		<br />

		<h3>Low {$vuln} Source</h3>
		<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
			<tr>
				<td><div id=\"code\">{$lowsrc}</div></td>
			</tr>
		</table>
		<br /> <br />

		<form>
			<input type=\"button\" value=\"<-- Back\" onclick=\"history.go(-1);return true;\">
		</form>

	</div>\n";
} else {
	$page['body'] = "<p>Not found</p>";
}

dvwaSourceHtmlEcho($page);

?>

<?php

if (array_key_exists ('redirect', $_GET) && $_GET['redirect'] != '') {
	if ($_GET['redirect'] === 'info.php?id=1') {
		header ('location: info.php?id=1');
		exit;
	}
	if ($_GET['redirect'] === 'info.php?id=2') {
		header ('location: info.php?id=2');
		exit;
	}
	http_response_code (500);
	?>
	<p>Invalid redirect target.</p>
	<?php
	exit;
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>

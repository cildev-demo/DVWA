<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	if (preg_match ("/http:\/\/|https:\/\//i", $_GET['redirect'])) {
		http_response_code (500);
		?>
		<p>Absolute URLs not allowed.</p>
		<?php
		exit;
	} else {
		switch ($_GET['redirect']) {
			case "info.php?id=1":
				header ("location: info.php?id=1");
				exit;
			case "info.php?id=2":
				header ("location: info.php?id=2");
				exit;
			default:
				http_response_code (500);
				?>
				<p>Invalid redirect target.</p>
				<?php
				exit;
		}
	}
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>

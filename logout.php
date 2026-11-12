<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( ) );

if( !dvwaIsLoggedIn() ) {
    dvwaRedirect( 'login.php' );
}

if( !isset( $_SESSION[ 'session_token' ] ) ) {
    generateSessionToken();
}

if( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $user_token = isset( $_POST[ 'user_token' ] ) ? $_POST[ 'user_token' ] : '';
    $session_token = isset( $_SESSION[ 'session_token' ] ) ? $_SESSION[ 'session_token' ] : '';
    checkToken( $user_token, $session_token, 'logout.php' );
    dvwaLogout();
    dvwaMessagePush( 'You have logged out' );
    dvwaRedirect( 'login.php' );
}

$messagesHtml = messagesPopAllToHtml();

Header( 'Cache-Control: no-cache, must-revalidate' );
Header( 'Content-Type: text/html;charset=utf-8' );

?>
<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Logout :: Damn Vulnerable Web Application (DVWA)</title>
        <link rel="stylesheet" type="text/css" href="dvwa/css/login.css" />
    </head>
    <body>
    <div id="wrapper">
    <div id="content">
    <form action="logout.php" method="post">
    <fieldset>
        <p>Are you sure you want to log out?</p>
        <p class="submit"><input type="submit" value="Logout" name="Logout"></p>
    </fieldset>
    <?php echo tokenField(); ?>
    </form>
    <?php echo $messagesHtml; ?>
    </div>
    </div>
    </body>
</html>

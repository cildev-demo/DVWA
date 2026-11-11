<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

$link = $GLOBALS["___mysqli_ston"];

// Get current user's ID
$current_user_id = 0;
$query = "SELECT user_id FROM users WHERE user = ?";
if ($stmt = mysqli_prepare($link, $query)) {
    $current_dvwa_user = dvwaCurrentUser();
    mysqli_stmt_bind_param($stmt, 's', $current_dvwa_user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $current_user_id_result);
    if (mysqli_stmt_fetch($stmt)) {
        $current_user_id = (int) $current_user_id_result;
    }
    mysqli_stmt_close($stmt);
}

$html = "";
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!is_string($_GET['user_id']) || !preg_match('/^\d+$/', $_GET['user_id'])) {
        $html .= "<p>Invalid user ID format. Please enter a number.</p>";
    } else {
        $id = (int) $_GET['user_id'];

        if ($current_user_id <= 0 || $id !== $current_user_id) {
            $html .= "<p>Access denied. You can only view your own profile.</p>";
        } else {
            $query = "SELECT first_name, last_name, user_id, avatar FROM users WHERE user_id = ?";
            if ($stmt = mysqli_prepare($link, $query)) {
                mysqli_stmt_bind_param($stmt, 'i', $current_user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $row_first_name, $row_last_name, $row_user_id, $row_avatar);
                if (mysqli_stmt_fetch($stmt)) {
                    $html .= '
                    <div class="profile-info">
                        <h3>User Profile</h3>
                        <p>User ID: ' . htmlspecialchars((string) $row_user_id, ENT_QUOTES, 'UTF-8') . '</p>
                        <p>Name: ' . htmlspecialchars((string) $row_first_name, ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars((string) $row_last_name, ENT_QUOTES, 'UTF-8') . '</p>
                        <p>Avatar: ' . htmlspecialchars((string) $row_avatar, ENT_QUOTES, 'UTF-8') . '</p>
                    </div>';
                } else {
                    $html .= "<p>No user found with ID: " . htmlspecialchars((string) $current_user_id, ENT_QUOTES, 'UTF-8') . "</p>";
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Log access attempts
        try {
            $check_table = "SHOW TABLES LIKE 'bac_log'";
            $table_exists = mysqli_query($link, $check_table);

            if ($table_exists && mysqli_num_rows($table_exists) == 0) {
                $create_table = "CREATE TABLE IF NOT EXISTS bac_log (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id INT(6) NULL,
                    target_id INT(6) NULL,
                    ip_address VARCHAR(50) NULL,
                    action VARCHAR(50) NULL,
                    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                mysqli_query($link, $create_table);
            }

            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $target_id = ($current_user_id > 0 && $id === $current_user_id) ? $current_user_id : 0;
            $log_query = "INSERT INTO bac_log (user_id, target_id, ip_address) VALUES (?, ?, ?)";
            if ($log_stmt = mysqli_prepare($link, $log_query)) {
                mysqli_stmt_bind_param($log_stmt, 'iis', $current_user_id, $target_id, $ip);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }
        } catch (Exception $e) {
        }
    }
}
?>

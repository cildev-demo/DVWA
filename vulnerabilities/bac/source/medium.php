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

// Basic attempt at access control (but easily bypassed)
$html = "";
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html .= "<p>Invalid user ID format. Please enter a number.</p>";
    } else {
        $id = $_GET['user_id'];
        $user_exists = false;
        
        // Check if user exists first
        $check_query = "SELECT user_id FROM users WHERE user_id = ?";
        if ($check_stmt = mysqli_prepare($link, $check_query)) {
            $check_id = (int) $id;
            mysqli_stmt_bind_param($check_stmt, 'i', $check_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            $user_exists = (mysqli_stmt_num_rows($check_stmt) > 0);
            mysqli_stmt_close($check_stmt);
        }
        
        // "Secure" check that's easily bypassed
        if (isset($_GET['token']) && $_GET['token'] == 'user_token') {
            if ($user_exists) {
                $query = "SELECT first_name, last_name, user_id, avatar FROM users WHERE user_id = ?";
                if ($stmt = mysqli_prepare($link, $query)) {
                    $profile_id = (int) $id;
                    mysqli_stmt_bind_param($stmt, 'i', $profile_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $row_first_name, $row_last_name, $row_user_id, $row_avatar);
                    if (mysqli_stmt_fetch($stmt)) {
                        $html .= "
                        <div class=\"profile-info\">
                            <h3>User Profile</h3>
                            <p>User ID: {$row_user_id}</p>
                            <p>Name: {$row_first_name} {$row_last_name}</p>
                            <p>Avatar: {$row_avatar}</p>
                            <!-- Hint: This token check isn't very secure... -->
                        </div>";
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                $html .= "<p>No user found with ID: {$id}</p>";
            }
        } else {
            $html .= "<p>Access denied. Valid token required. <!-- Try using token=user_token --></p>";
        }
        
        // Log access attempts
        try {
            // First check if the bac_log table exists
            $check_table = "SHOW TABLES LIKE 'bac_log'";
            $table_exists = mysqli_query($link, $check_table);
            
            if ($table_exists && mysqli_num_rows($table_exists) == 0) {
                // Create the table if it doesn't exist
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
            
            // Log the access attempt - only log numeric target_id
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $target_id = $user_exists ? (int) $id : 0; // Use 0 for non-existent users
            $log_query = "INSERT INTO bac_log (user_id, target_id, ip_address) VALUES (?, ?, ?)";
            if ($log_stmt = mysqli_prepare($link, $log_query)) {
                mysqli_stmt_bind_param($log_stmt, 'iis', $current_user_id, $target_id, $ip);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }
        } catch (Exception $e) {
            // Silently fail if logging doesn't work
        }
    }
}
?>

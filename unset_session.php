<?php
session_start();
if (isset($_SESSION['rolledItem'])) {
    unset($_SESSION['rolledItem']);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Session variable not set.']);
}
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Session variable not set.']);
}
?>
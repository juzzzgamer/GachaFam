<?php
session_start();
$response = ['status' => 'success', 'messages' => []];

if (isset($_SESSION['rolledItem'])) {
    unset($_SESSION['rolledItem']);
    $response['messages'][] = ['rolledItem' => 'Session variable unset successfully.'];
} else {
    $response['messages'][] = ['rolledItem' => 'Session variable not set.'];
}

if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
    $response['messages'][] = ['error' => 'Session variable unset successfully.'];
} else {
    $response['messages'][] = ['error' => 'Session variable not set.'];
}

echo json_encode($response);
?>
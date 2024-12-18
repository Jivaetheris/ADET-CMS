<?php
// Database connection
require 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$appointmentId = $data['appointmentId'];
$action = $data['action'];

$response = array('success' => false);

if ($action == 'accept' || $action == 'decline') {
    $status = ($action == 'accept') ? 'Confirmed' : 'Cancelled';

    // Update the appointment status in the database
    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $status, $appointmentId);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    }
    $stmt->close();
}

echo json_encode($response);
?>

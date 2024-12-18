<?php
require 'connection.php';
header('Content-Type: application/json');

if (!$connection) {
    echo json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch Appointments
    $sql = "SELECT a.id AS appointment_id, r.firstName, r.lastName, a.disease_description, 
            DATE_FORMAT(a.appointment_date, '%Y-%m-%d %H:%i') AS appointment_date, a.status
            FROM appointments AS a
            INNER JOIN registration AS r ON a.patient_id = r.id
            ORDER BY a.appointment_date ASC";

    $result = $connection->query($sql);

    if (!$result) {
        error_log("SQL Error: " . $connection->error); 
        echo json_encode(["error" => "SQL Error: " . $connection->error]);
        exit;
    }

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = [
            'appointment_id' => $row['appointment_id'],
            'firstName' => $row['firstName'],
            'lastName' => $row['lastName'],
            'disease_description' => $row['disease_description'],
            'appointment_date' => $row['appointment_date'],
            'status' => $row['status']
        ];
    }

    echo json_encode($appointments);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Check for 'action' in the input data
    if (isset($input['action']) && isset($input['appointment_id'])) {
        $appointment_id = $input['appointment_id'];
        $action = $input['action'];

        if ($action === 'updateStatus' && isset($input['status'])) {
            // Update appointment status
            $status = $input['status'];
            $stmt = $connection->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $appointment_id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Status updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update status."]);
            }
            $stmt->close();

        } elseif ($action === 'reschedule' && isset($input['appointment_date'])) {
            // Reschedule appointment
            $appointment_date = $input['appointment_date'];
            $stmt = $connection->prepare("UPDATE appointments SET appointment_date = ? WHERE id = ?");
            $stmt->bind_param("si", $appointment_date, $appointment_id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Appointment rescheduled successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to reschedule appointment."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Invalid action or missing parameters."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
}

$connection->close();
?>

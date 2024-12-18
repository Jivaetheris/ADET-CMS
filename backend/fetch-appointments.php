<?php
require 'connection.php';

// Ensure proper content type for JSON response
header('Content-Type: application/json');

// Check if the database connection is successful
if (!$connection) {
    echo json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

// SQL query to fetch appointments and related patient data
$sql = "SELECT a.id AS appointment_id, r.firstName, r.lastName, a.disease_description, 
            DATE_FORMAT(a.appointment_date, '%Y-%m-%d %H:%i') AS appointment_date, a.status
        FROM appointments AS a
        INNER JOIN registration AS r ON a.patient_id = r.id
        ORDER BY a.appointment_date ASC";

// Execute the query
$result = $connection->query($sql);

// Check if the query was successful
if (!$result) {
    // Log the error for debugging
    error_log("SQL Error: " . $connection->error); // Log SQL error to server's error log
    echo json_encode(["error" => "SQL Error: " . $connection->error]); // Return error message in JSON format
    exit;
}

// Initialize an empty array to store appointments
$appointments = [];

// Fetch the results from the query and store them in the $appointments array
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

// Check if we have any appointments and send the response
if (count($appointments) > 0) {
    echo json_encode($appointments);
} else {
    // If no appointments are found, return a message indicating no data
    echo json_encode(["message" => "No appointments found"]);
}

// Close the database connection
$connection->close();
?>

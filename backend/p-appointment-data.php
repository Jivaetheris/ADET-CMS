<?php
require 'connection.php'; // Use relative path since it's in the same folder

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient-id'];
    $disease_description = $_POST['disease-description'];
    $doctor_name = $_POST['doctor-name'];
    $appointment_date = $_POST['appointment-date'];

    // Validate inputs
    if (empty($patient_id) || empty($disease_description) || empty($doctor_name) || empty($appointment_date)) {
        echo "All fields are required.";
        exit;
    }

    // Insert appointment into the database
    $stmt = $connection->prepare("INSERT INTO appointments (patient_id, disease_description, doctor_name, appointment_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $disease_description, $doctor_name, $appointment_date);

    if ($stmt->execute()) {
        echo "Appointment created successfully!";
        header("Location: ../html/appointment-success.html"); // wara pa
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>

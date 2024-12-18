<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'clinicsystem';

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_GET['id'])) {
    $doctor_id = $_GET['id'];

    // Prepare the query
    $getDoctorQuery = "SELECT * FROM doctors WHERE id = ?";
    if ($stmt = $connection->prepare($getDoctorQuery)) {
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
            echo json_encode(["success" => true, "doctor" => $doctor]);
        } else {
            echo json_encode(["success" => false, "error" => "Doctor not found."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Failed to prepare query."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "No ID provided."]);
}
?>

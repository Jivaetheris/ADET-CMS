<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form fields are set
    if (isset($_POST['doctor-name'], $_POST['specialization'], $_POST['email'], $_POST['phone'])) {
        $name = $_POST['doctor_name'];
        $specialization = $_POST['specialization'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        try {
            // Prepare and execute the SQL query to insert the doctor
            $stmt = $pdo->prepare("INSERT INTO doctors (name, specialization, email, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $specialization, $email, $phone]);

            // Return success response
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Log the error message and return error response
            error_log("Error adding doctor: " . $e->getMessage());  // Log error
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        // If form fields are missing
        echo json_encode(['success' => false, 'error' => 'Missing form fields']);
    }
}
?>

<?php
include 'db.php'; // Ensure this file initializes $pdo securely

header('Content-Type: application/json'); // Ensure JSON responses are correctly set

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are present
    if (!isset($_POST['doctor-id'], $_POST['doctor-name'], $_POST['specialization'], $_POST['email'], $_POST['phone'])) {
        echo json_encode(['success' => false, 'error' => 'Missing form fields']);
        exit;
    }

    // Sanitize input
    $doctorId = filter_var($_POST['doctor-id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_POST['doctor-name'], FILTER_SANITIZE_STRING);
    $specialization = filter_var($_POST['specialization'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email address']);
        exit;
    }

    if (!is_numeric($doctorId) || empty($name) || empty($specialization) || empty($phone)) {
        echo json_encode(['success' => false, 'error' => 'Invalid input values']);
        exit;
    }

    try {
        // Prepare and execute the update query
        $stmt = $pdo->prepare("UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $specialization, $email, $phone, $doctorId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No changes were made or invalid doctor ID']);
        }
    } catch (PDOException $e) {
        error_log("Error updating doctor: " . $e->getMessage()); // Log the error for debugging
        echo json_encode(['success' => false, 'error' => 'Database error occurred']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>

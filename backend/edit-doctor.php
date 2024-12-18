<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['doctor-id'], $_POST['doctor-name'], $_POST['specialization'], $_POST['email'], $_POST['phone'])) {
        $doctorId = $_POST['doctor-id'];
        $name = $_POST['doctor-name'];
        $specialization = $_POST['specialization'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        try {
            $stmt = $pdo->prepare("UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $specialization, $email, $phone, $doctorId]);

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            error_log("Error updating doctor: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing form fields']);
    }
}
?>

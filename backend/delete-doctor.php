<?php
include 'db.php';

if (isset($_GET['id'])) {
    $doctorId = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
        $stmt->execute([$doctorId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        error_log("Error deleting doctor: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>

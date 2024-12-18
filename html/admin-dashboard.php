<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'clinicsystem';

// Create connection
$connection = new mysqli($host, $username, $password);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Create database if it does not exist
$createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$connection->query($createDatabaseQuery)) {
    die("Error creating database: " . $connection->error);
}

// Select database
$connection->select_db($dbname);

// Create the doctors table if it does not exist
$createDoctorsTableQuery = "
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    specialization VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL
)";

if (!$connection->query($createDoctorsTableQuery)) {
    die("Error creating table 'doctors': " . $connection->error);
}

// Handle adding a new doctor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_name'], $_POST['specialization'], $_POST['email'], $_POST['phone'])) {
    $doctor_name = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT COUNT(*) AS count FROM doctors WHERE email = ?";
    $stmt = $connection->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        json_encode(["success" => false, "error" => "The email is already taken."]);
    } else {
        $insertDoctorQuery = "INSERT INTO doctors (name, specialization, email, phone) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insertDoctorQuery);
        $stmt->bind_param("ssss", $doctor_name, $specialization, $email, $phone);

        if ($stmt->execute()) {
            //echo json_encode(["success" => true]);
        } else {
            //echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
        }

        $stmt->close();
    }
}

// Handle updating a doctor's information
if (isset($_POST['edit_id'], $_POST['doctor_name'], $_POST['specialization'], $_POST['email'], $_POST['phone'])) {
    $edit_id = $_POST['edit_id'];
    $doctor_name = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $updateDoctorQuery = "UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $connection->prepare($updateDoctorQuery);
    $stmt->bind_param("ssssi", $doctor_name, $specialization, $email, $phone, $edit_id);

    if ($stmt->execute()) {
        //echo json_encode(["success" => true]);
    } else {
        //echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
}

// Handle deleting a doctor
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $deleteDoctorQuery = "DELETE FROM doctors WHERE id = ?";
    $stmt = $connection->prepare($deleteDoctorQuery);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php");
        exit;
    } else {
        //echo "Error deleting doctor: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all doctors to display
$doctorsQuery = "SELECT * FROM doctors";
$doctorsResult = $connection->query($doctorsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Doctors</title>
    <style>
        /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }

    /* Navbar Styles */
    .navbar {
        background-color: #007BFF;
        color: white;
        padding: 15px;
        text-align: center;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
    }

    /* Container Styles */
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Header Styles */
    h1 {
        color: #007BFF;
    }

    /* Form Group Styles */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Form Button Styles */
    .form-buttons {
        display: flex;
        justify-content: space-between;
    }

    button {
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .submit-btn {
        background-color: #28a745;
        color: white;
    }

    .cancel-btn {
        background-color: #dc3545;
        color: white;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ccc;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    /* Action Button Styles */
    .action-btns {
        display: flex;
        gap: 10px;
    }

    .edit-btn, .delete-btn {
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .edit-btn {
        background-color: #ffc107;
        color: white;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
    }

    /* Modal/Overlay Styles */
    .overlay {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .overlay .modal {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 600px;
        width: 100%;
    }

    .overlay.active {
        display: flex; /* Show when active */
    }

    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <a href="index.html">Home</a>
        <a href="logout.html">Logout</a>
    </div>

    <div class="container">
        <h1>Manage Doctors</h1>
        <form id="doctor-form" action="admin-dashboard.php" method="post">
            <div class="form-group">
                <label for="doctor-name">Doctor Name</label>
                <input type="text" id="doctor-name" name="doctor_name" placeholder="Enter Doctor Name" required>
            </div>
            <div class="form-group">
                <label for="specialization">Specialization</label>
                <input type="text" id="specialization" name="specialization" placeholder="Enter Specialization" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number" required>
            </div>
            <div class="form-buttons">
                <button type="reset" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn">Add Doctor</button>
            </div>
        </form>

        <h2>Registered Doctors</h2>
        <table id="doctor-table">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display doctors and add edit/delete options
                while ($row = $doctorsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['name'] . "</td>
                            <td>" . $row['specialization'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['phone'] . "</td>
                            <td class='action-btns'>
                                <button class='edit-btn' onclick='openEditModal(" . $row['id'] . ")'>Edit</button>
                                <a href='?delete_id=" . $row['id'] . "'><button class='delete-btn'>Delete</button></a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal/Overlay for Edit -->
    <div class="overlay" id="edit-modal">
        <div class="modal">
            <h2>Edit Doctor</h2>
            <form id="edit-doctor-form" action="admin-dashboard.php" method="post">
                <input type="hidden" name="edit_id" id="edit-id">
                <div class="form-group">
                    <label for="edit-doctor-name">Doctor Name</label>
                    <input type="text" id="edit-doctor-name" name="doctor_name" required>
                </div>
                <div class="form-group">
                    <label for="edit-specialization">Specialization</label>
                    <input type="text" id="edit-specialization" name="specialization" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit-phone">Phone</label>
                    <input type="tel" id="edit-phone" name="phone" required>
                </div>
                <div class="form-buttons">
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="submit-btn">Update Doctor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id) {
        // Fetch the doctor's details and populate the modal
        fetch(`../backend/get-doctors.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal fields with doctor's data
                    document.getElementById('edit-id').value = data.doctor.id;
                    document.getElementById('edit-doctor-name').value = data.doctor.name;
                    document.getElementById('edit-specialization').value = data.doctor.specialization;
                    document.getElementById('edit-email').value = data.doctor.email;
                    document.getElementById('edit-phone').value = data.doctor.phone;

                    // Show modal
                    document.getElementById('edit-modal').classList.add('active');
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => {
                console.error("Error fetching doctor data:", error);
                alert("Error fetching doctor data.");
            });
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.remove('active');
    }

    </script>
</body>
</html>

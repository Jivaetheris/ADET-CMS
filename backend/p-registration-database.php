<?php 
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

// Create the registration table if it does not exist
$createRegistrationTableQuery = "
CREATE TABLE IF NOT EXISTS registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100),
    lastName VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    fatherName VARCHAR(100),
    motherName VARCHAR(100),
    address VARCHAR(255),
    gender VARCHAR(10),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$connection->query($createRegistrationTableQuery)) {
    die("Error creating table 'registration': " . $connection->error);
}

// Create the appointments table if it does not exist
$createAppointmentsTableQuery = "
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    disease_description TEXT NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    appointment_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES registration(id)
)";
if (!$connection->query($createAppointmentsTableQuery)) {
    die("Error creating table 'appointments': " . $connection->error);
}

// Get input from form
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$fatherName = $_POST['fatherName'];
$motherName = $_POST['motherName'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Fix: semicolon added

// Validate inputs (optional, but recommended)
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($fatherName) || empty($motherName) || empty($address) || empty($gender) || empty($_POST['password'])) {
    die("All fields are required!");
}

// Database connection is already established above, no need to reconnect

// Prepare and bind the statement to insert user data
$stmt = $connection->prepare("INSERT INTO registration (firstName, lastName, email, phone, fatherName, motherName, address, gender, password) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $firstName, $lastName, $email, $phone, $fatherName, $motherName, $address, $gender, $password);

// Execute the statement and check for success
if ($stmt->execute()) {
    header("Location:../html/reg-success.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$connection->close();
?>

<?php 
//Gets input sa form
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$fatherName = $_POST['fatherName'];
$motherName = $_POST['motherName'];
$address = $_POST['address'];
$gender = $_POST['gender'];

//Database Connection
$connection = new mysqli('localhost', 'root', '', 'clinicsystem');

if ($connection->connect_error) {
    die('Connection Failed: ' . $connection->connect_error);
}

$stmt = $connection->prepare("INSERT INTO registration (firstName, lastName, email, phone, fatherName, motherName, address, gender) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $fatherName, $motherName, $address, $gender);

if ($stmt->execute()) {
    header("Location:../html/reg-success.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>

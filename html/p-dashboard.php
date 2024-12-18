<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
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
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007BFF;
        }
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
        .status {
            font-weight: bold;
        }
        .status-confirmed {
            color: green;
        }
        .status-pending {
            color: orange;
        }
        .status-cancelled {
            color: red;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Patient Dashboard</h1>
        <a href="index.html">Home</a>
        <a href="p-Appointment.php">Create Appointment</a>
        <a href="index.html">Logout</a>
    </div>

    <div class="container">
        <h1>Your Appointments</h1>
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Appointment Schedule</th>
                    <th>Appointed Doctor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Add more rows as needed -->
            </tbody>
    <?php
    $conn = mysqli_connect("localhost", "root", '', 'clinicsystem');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT 
            CONCAT(firstName, ' ', lastName) AS PatientName, 
            appointment_date, 
            doctor_name, 
            status 
            FROM appointments 
            INNER JOIN registration 
            ON appointments.patient_id = registration.id";

    $result = $conn-> query($sql);

    if($result-> num_rows > 0) {
        while ($row = $result-> fetch_assoc()){
            echo "<tr>
                <td>" . htmlspecialchars($row["PatientName"]) . "</td>
                <td>" . htmlspecialchars($row["appointment_date"]) . "</td>
                <td>" . htmlspecialchars($row["doctor_name"]) . "</td>
                <td>" . htmlspecialchars($row["status"]) . "</td>
              </tr>";
        }
    }else {
        echo "<tr><td colspan='3'>No appointments found</td></tr>";
    }
    $conn->close();
?>
        </table>
    </div>

</body>
</html>


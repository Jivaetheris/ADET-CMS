<?php
// Include database connection
require_once '../backend/connection.php';

// Assume doctor_name or a session variable determines the logged-in doctor
$doctor_name = "Dr. John Doe";  // Replace with the actual logged-in doctor's name or session data

// Prepare the SQL query to fetch appointments for the specific doctor
$query = "
    SELECT 
        a.id,
        r.firstName, 
        a.appointment_date,
        a.doctor_name,  // Assuming doctor_name exists in the appointments table
        a.status
    FROM appointments a
    INNER JOIN registration r ON a.patient_id = r.patient_id
    WHERE a.doctor_name = ?  // Use doctor_name instead of doctor_id if needed
    ORDER BY a.appointment_date DESC
";

// Prepare and execute the query
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $doctor_name);  // 's' indicates string type
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row["PatientName"]) . "</td>
            <td>" . htmlspecialchars($row["appointment_date"]) . "</td>
            <td>" . htmlspecialchars($row["doctor_name"]) . "</td>
            <td>" . htmlspecialchars($row["status"]) . "</td>
          </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No appointments found</td></tr>";
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #007BFF;
            margin-top: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            margin: 20px auto;
            display: block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .status-confirmed {
            color: green;
        }
        .status-cancelled {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Doctor Dashboard - Appointments</h1>

    <!-- Table to display appointments -->
    <table id="appointmentTable">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Disease Description</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["PatientName"]) . "</td>
                            <td>" . htmlspecialchars($row["appointment_date"]) . "</td>
                            <td>" . htmlspecialchars($row["doctor_name"]) . "</td>
                            <td class='status-" . strtolower($row["status"]) . "'>" . htmlspecialchars($row["status"]) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No appointments found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Button to Refresh Appointments -->
    <button onclick="fetchAppointments()">Refresh Appointments</button>

    <script src="doctor-dashboard-script.js">
    </script>
</body>
</html>

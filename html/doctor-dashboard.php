<?php
// Include database connection
require_once '../backend/connection.php';

if (isset($_GET['doctor_name'])) {
    $doctor_name = $_GET['doctor_name'];

    // Prepare the SQL query to fetch appointments for the specific doctor
    $query ="
        SELECT 
        a.id AS appointment_id,
        r.firstName AS PatientFirstName,
        r.lastName AS PatientLastName,
        a.disease_description,
        a.appointment_date,
        a.status
        FROM appointments a
        INNER JOIN registration r ON a.patient_id = r.id
        WHERE a.doctor_name = ".$doctor_name."
        ORDER BY a.appointment_date DESC
    ";

    // Prepare and execute the query
    if ($stmt = $connection->prepare($query)) {
        
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        $stmt->close();
    } else {
        echo "Error preparing the query.";
    }
} else {
    echo "<p>Please select a doctor to view their appointments.</p>";
}
?>

<?php
// Fetch doctors from the database
$query = "SELECT id, name FROM doctors";
$doctor_result = $connection->query($query);
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
        .overlay .modal {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 600px;
        width: 100%;
        }
        .overlay.active {
            display: flex;
        }
    </style>
</head>
<body>
    <h1>Doctor Dashboard - Appointments</h1>

    <!-- Doctor Selection Form -->
    <form action="doctor-dashboard.php" method="GET">
        <label for="doctor_select">Select a Doctor:</label>
        <select id="doctor_select" name="doctor_name">
            <option value="">-- Select Doctor --</option>
            <?php
            if ($doctor_result->num_rows > 0) {
                while ($row = $doctor_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
            } else {
                echo "<option value='' disabled>No doctors available</option>";
            }
            ?>
        </select>
        <button type="submit">View Appointments</button>
    </form>

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
            // If appointments are found, display them
            if (isset($result) && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["PatientFirstName"]) . " " . htmlspecialchars($row["PatientLastName"]) . "</td>
                            <td>" . htmlspecialchars($row["disease_description"]) . "</td>
                            <td>" . htmlspecialchars($row["appointment_date"]) . "</td>
                            <td class='status-" . strtolower($row["status"]) . "'>" . htmlspecialchars($row["status"]) . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No appointments found.</td></tr>";
            }
        ?>
        </tbody>
    </table>

    <button onclick="fetchAppointments()">Refresh Appointments</button>

    <script src="doctor-dashboard-script.js"></script>
</body>
</html>

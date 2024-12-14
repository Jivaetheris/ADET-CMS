<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System - Create Appointment</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <form action="../backend/p-appointment-data.php" method="post" class="appointment-form">
            <div class="banner">Create Appointment</div>

            <!-- Patient Selection -->
            <label for="patient-id">Select Existing Patient</label>
            <select id="patient-id" name="patient-id" required>
                <option value="">-- Select a Patient --</option>
                <?php
                require '../backend/connection.php';

                $stmt = $connection->prepare("SELECT id, firstName, lastName FROM registration");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['firstName']} {$row['lastName']}</option>";
                    }
                } else {
                    echo "<option value=''>No patients found</option>";
                }

                $stmt->close();
                ?>
            </select>

            <!-- Appointment Details -->
            <label for="disease-description">Disease Description</label>
            <textarea id="disease-description" name="disease-description" rows="3" placeholder="Describe the disease" required></textarea>

            <label for="doctor-name">Doctor Name</label>
            <input type="text" id="doctor-name" name="doctor-name" placeholder="Enter doctor name" required>

            <label for="appointment-date">Appointment Date</label>
            <input type="datetime-local" id="appointment-date" name="appointment-date" required>

            <div style="display: flex; justify-content: space-between;">
                <button type="button" class="cancel-btn" onclick="window.location.href='p-dashboard.php'">Cancel</button>
                <button type="submit" class="submit-btn">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
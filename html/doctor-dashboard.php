<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #28a745;
            color: white;
        }
        .decline-btn {
            background-color: #dc3545;
            color: white;
        }
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Doctor Dashboard</h1>
        <a href="index.html">Home</a>
        <a href="p-Appointment.php">View Appointments</a>
        <a href="index.html">Logout</a>
    </div>

    <div class="container">
        <h1>Upcoming Appointments</h1>
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Disease Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example data, replace with dynamic data from the database -->
                <tr>
                    <td>John Doe</td>
                    <td>2023-10-15 10:00 AM</td>
                    <td>Flu Symptoms</td>
                    <td class="status status-pending">Pending</td>
                    <td>
                        <button class="action-btn accept-btn" onclick="acceptAppointment(1)">Accept</button>
                        <button class="action-btn decline-btn" onclick="declineAppointment(1)">Decline</button>
                        <button class="action-btn edit-btn" onclick="editAppointment(1)">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>2023-10-16 11:00 AM</td>
                    <td>Check-up</td>
                    <td class="status status-pending">Pending</td>
                    <td>
                        <button class="action-btn accept-btn" onclick="acceptAppointment(2)">Accept</button>
                        <button class="action-btn decline-btn" onclick="declineAppointment(2)">Decline</button>
                        <button class="action-btn edit-btn" onclick="editAppointment(2)">Edit</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <script>
        function
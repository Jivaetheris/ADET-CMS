function fetchAppointments() {
    const refreshButton = document.querySelector("button");
    refreshButton.disabled = true;  // Disable the button

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "../backend/fetch_appointments.php", true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            const tableBody = document.querySelector("#appointmentTable tbody");
            tableBody.innerHTML = ""; // Clear existing data

            if (data.length > 0) {
                data.forEach(appointment => {
                    const row = `
                        <tr>
                            <td>${appointment.firstName} ${appointment.lastName}</td>
                            <td>${appointment.disease_description}</td>
                            <td>${appointment.appointment_date}</td>
                            <td class="status-${appointment.status.toLowerCase()}">${appointment.status}</td>
                            <td>
                                <button onclick="updateStatus(${appointment.appointment_id}, 'accept')">Accept</button>
                                <button onclick="updateStatus(${appointment.appointment_id}, 'decline')">Decline</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='5' style='text-align:center;'>No appointments available.</td></tr>";
            }
        } else {
            alert("Failed to load appointments. Please try again.");
        }

        refreshButton.disabled = false;  // Re-enable the button after the request
    };

    xhr.onerror = function () {
        alert("An error occurred while fetching appointments.");
        refreshButton.disabled = false;
    };

    xhr.send();
}


// Update the appointment status (Accept/Decline)
function updateStatus(appointmentId, action) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../backend/update-appointment.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    const data = JSON.stringify({
        appointmentId: appointmentId,
        action: action
    });

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Appointment status updated successfully!");
                fetchAppointments();  // Refresh the table
            } else {
                alert("Failed to update appointment status.");
            }
        } else {
            alert("Failed to update appointment status.");
        }
    };

    xhr.onerror = function () {
        alert("An error occurred while updating the appointment status.");
    };

    xhr.send(data);
}

// Fetch appointments on page load
window.onload = fetchAppointments;
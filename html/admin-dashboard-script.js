document.addEventListener('DOMContentLoaded', function () {
    loadDoctors();

    // Add event listener to the form
    document.getElementById('doctor-form').addEventListener('submit', function (e) {
        e.preventDefault();
        addDoctor();
    });
});

// Function to load doctors from the backend
function loadDoctors() {
    fetch('get-doctors.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('doctor-table').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = ''; // Clear the table before adding new rows
            if (data.length > 0) {
                data.forEach(doctor => {
                    const row = tableBody.insertRow();
                    row.innerHTML = `
                        <td>${doctor.name}</td>
                        <td>${doctor.specialization}</td>
                        <td>${doctor.email}</td>
                        <td>${doctor.phone}</td>
                        <td>
                            <button onclick="editDoctor(${doctor.id})">Edit</button>
                            <button onclick="deleteDoctor(${doctor.id})">Delete</button>
                        </td>
                    `;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="5">No doctors found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching doctors:', error);
        });

}

// Function to add a new doctor
function addDoctor() {
    const form = document.getElementById('doctor-form');
    const formData = new FormData(form);

    fetch('admin-dashboard.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Doctor added successfully!');
            loadDoctors(); // Refresh the doctors list
            form.reset(); // Reset the form
            
            window.location.reload();
        } else {
            alert('Error adding doctor: ' + data.error);
        }
    })
}

// Function to edit a doctor
function editDoctor(doctorId) {
    fetch('get-doctor.php?id=' + doctorId)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Populate the form with the current doctor data
                document.getElementById('edit-doctor-id').value = data.id;
                document.getElementById('edit-doctor-name').value = data.name;
                document.getElementById('edit-specialization').value = data.specialization;
                document.getElementById('edit-email').value = data.email;
                document.getElementById('edit-phone').value = data.phone;

                // Show the edit form
                document.querySelector('.edit-form').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching doctor details:', error);
        });
}

// Function to close the edit form
function closeEditForm() {
    document.querySelector('.edit-form').style.display = 'none';
}

// Function to update doctor data
document.getElementById('edit-doctor-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = new FormData(this);
    fetch('edit-doctor.php', {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Doctor updated successfully!');
            loadDoctors(); // Reload the doctors list
            closeEditForm(); // Close the form
        } else {
            alert('Error updating doctor: ' + data.error);
        }
    });
});


// Function to delete a doctor
function deleteDoctor(doctorId) {
    if (confirm('Are you sure you want to delete this doctor?')) {
        fetch('delete-doctor.php?id=' + doctorId, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Doctor deleted successfully!');
                    loadDoctors(); // Reload the doctors list
                } else {
                    alert('Error deleting doctor: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error deleting doctor:', error);
            });
    }
}




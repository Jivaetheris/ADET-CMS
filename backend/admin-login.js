function validateForm() {
    var email = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    
    if (email === "" || password === "") {
        alert("Please fill in all fields.");
        return false;  // Prevent form submission
    }
    return true;
}

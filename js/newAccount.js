// Wait for the page to fully load before executing
window.addEventListener("load",function(event){ 

    // Get references to form elements for new account creation
    let email = document.getElementById("email");
    let confirm = document.getElementById("confirm");
    let myForm = document.getElementById("newAccountForm");
    let password = document.getElementById("password");
    let showPassword = document.getElementById("showPassword");

    // Add form validation when the form is submitted
    myForm.addEventListener("submit",function(event){
        // Check if password meets minimum length requirement
        if(password.value.length < 8){
            password.value = ""; // Clear the password field
            password.placeholder = "MUST BE 8 CHARECTERS OR GREATER"; // Show error message
            password.classList.add("invalid"); // Add error styling
            event.preventDefault(); // Prevent form submission
        }
        // Check if email and confirm email fields match
        if(email.value !== confirm.value){
            confirm.value = ""; // Clear the confirm email field
            confirm.placeholder = "MUST MATCH EMAIL ABOVE"; // Show error message
            confirm.classList.add("invalid"); // Add error styling
            event.preventDefault(); // Prevent form submission
        }
    })

    // Toggle password visibility when checkbox is clicked
    showPassword.addEventListener("input",function(event){
        console.log("got here"); // Debug log
        // Switch between showing and hiding password
        if (password.type === "password") {
            password.type = "text"; // Show password as plain text
        } else {
            password.type = "password"; // Hide password with dots
        }
    })
    
});


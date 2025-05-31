// Wait for the page to fully load before executing
window.addEventListener("load", function () {
    // Get references to the change info button and container div
    let changeInfoBtn = document.getElementById("change-info-btn");
    let profileOptions = document.getElementById("profile-options");
    
    // Add click event listener to the change personal info button
    changeInfoBtn.addEventListener("click", function () {
        // Clear any existing content in the profile options container
        profileOptions.innerHTML = "";
        
        // Create the main form element that will submit to PHP handler
        let infoForm = document.createElement("form");
        infoForm.id = "info-form";
        infoForm.method = "POST";
        infoForm.action = "updatePersonalInfo.php"; // Form submits to PHP handler
        
        // Create and add the form heading
        let heading = document.createElement("h2");
        heading.innerHTML = "Change Personal Info";
        heading.className = "form-heading";
        infoForm.appendChild(heading);        
        // Email Group - Container for email input field
        let emailGroup = document.createElement("div");
        emailGroup.className = "form-group";
        
        // Create label for email field
        let emailLabel = document.createElement("label");
        emailLabel.innerHTML = "Email:";
        
        // Create input field for new email address
        let emailInput = document.createElement("input");
        emailInput.type = "email"; // Built-in email validation
        emailInput.name = "email"; // Name for POST data
        emailInput.required = true; // Form validation
        emailInput.className = "form-input";

        // Add label and input to group, then add group to form
        emailGroup.appendChild(emailLabel);
        emailGroup.appendChild(emailInput);
        infoForm.appendChild(emailGroup);
        
        // Password Group - For security verification
        let passwordGroup = document.createElement("div");
        passwordGroup.className = "form-group";
        
        // Create label for password confirmation
        let passwordLabel = document.createElement("label");
        passwordLabel.innerHTML = "Confirm Password:";
        
        // Create input field for password verification
        let passwordInput = document.createElement("input");
        passwordInput.type = "password"; // Hide text input
        passwordInput.name = "confirmPassword"; // Name for POST data
        passwordInput.required = true; // Form validation
        passwordInput.className = "form-input";

        // Add label and input to group, then add group to form
        passwordGroup.appendChild(passwordLabel);
        passwordGroup.appendChild(passwordInput);
        infoForm.appendChild(passwordGroup);
        
        // Button Group - Container for form action buttons
        let buttonGroup = document.createElement("div");
        buttonGroup.className = "button-group";
        
        // Create submit button to save changes
        let saveButton = document.createElement("button");
        saveButton.type = "submit"; // Submits the form
        saveButton.innerHTML = "Save Changes";
        saveButton.className = "save-button";
        
        // Create cancel button to exit without saving
        let cancelButton = document.createElement("button");
        cancelButton.type = "button"; // Doesn't submit form
        cancelButton.innerHTML = "Cancel";
        cancelButton.className = "cancel-button";

        // Add click event to cancel button to reload page
        cancelButton.addEventListener("click", function () {
            window.location.reload(); // Refresh page to cancel changes
        });
        
        // Add both buttons to button group, then add to form
        buttonGroup.appendChild(saveButton);
        buttonGroup.appendChild(cancelButton);
        infoForm.appendChild(buttonGroup);

        // Add the completed form to the profile options container
        profileOptions.appendChild(infoForm);
        
        // Fetch current user info from server to pre-populate form
        fetch('getUserInfo.php')
            .then(function(response) {
                return response.json(); // Parse JSON response
            })
            .then(function(data) {
                // Pre-fill email field with current email if available
                if (data.email) {
                    emailInput.value = data.email;
                }
            });
    });
}); 
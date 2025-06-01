// Wait for the page to fully load before executing
window.addEventListener("load", function () {
    // Get references to the change password button and container div
    let changePasswordBtn = document.getElementById("change-password-btn");
    let profileOptions = document.getElementById("profile-options");
    
    // Add click event listener to the change password button
    changePasswordBtn.addEventListener("click", function () {
        // Clear any existing content in the profile options container
        profileOptions.innerHTML = "";
        
        // Create the main form element that will submit to PHP handler
        let passwordForm = document.createElement("form");
        passwordForm.id = "password-form";
        passwordForm.method = "POST";
        passwordForm.action = "updatePassword.php"; // Form submits to PHP handler
        
        // Create and add the form heading on new page
        let heading = document.createElement("h2");
        heading.innerHTML = "Change Password";
        heading.className = "form-heading";
        passwordForm.appendChild(heading);        
        // New Password Group - First input field
        let newPasswordGroup = document.createElement("div");
        newPasswordGroup.className = "form-group";
        
        // Create label for new password
        let newPasswordLabel = document.createElement("label");
        newPasswordLabel.innerHTML = "New Password:";
        
        // Create input field for new password
        let newPasswordInput = document.createElement("input");
        newPasswordInput.type = "password"; // Hide text input
        newPasswordInput.name = "newPassword"; // Name for POST data
        newPasswordInput.required = true; // Form validation
        newPasswordInput.className = "form-input";
        
        // Add label and input to group, then add group to form
        newPasswordGroup.appendChild(newPasswordLabel);
        newPasswordGroup.appendChild(newPasswordInput);
        passwordForm.appendChild(newPasswordGroup);
        
        // Confirm New Password Group - Second input field for verification
        let confirmNewPasswordGroup = document.createElement("div");
        confirmNewPasswordGroup.className = "form-group";
        
        // Create label for password confirmation
        let confirmNewPasswordLabel = document.createElement("label");
        confirmNewPasswordLabel.innerHTML = "Confirm New Password:";
        
        // Create input field for password confirmation
        let confirmNewPasswordInput = document.createElement("input");
        confirmNewPasswordInput.type = "password";
        confirmNewPasswordInput.name = "confirmNewPassword";
        confirmNewPasswordInput.required = true;
        confirmNewPasswordInput.className = "form-input";

        // Add label and input to group, then add group to form
        confirmNewPasswordGroup.appendChild(confirmNewPasswordLabel);
        confirmNewPasswordGroup.appendChild(confirmNewPasswordInput);
        passwordForm.appendChild(confirmNewPasswordGroup);
        
        // Current Password Group - Third input field for security verification
        let currentPasswordGroup = document.createElement("div");
        currentPasswordGroup.className = "form-group";
        
        // Create label for current password
        let currentPasswordLabel = document.createElement("label");
        currentPasswordLabel.innerHTML = "Current Password:";
        
        // Create input field for current password verification
        let currentPasswordInput = document.createElement("input");
        currentPasswordInput.type = "password";
        currentPasswordInput.name = "currentPassword";
        currentPasswordInput.required = true;
        currentPasswordInput.className = "form-input";

        // Add label and input to group, then add group to form
        currentPasswordGroup.appendChild(currentPasswordLabel);
        currentPasswordGroup.appendChild(currentPasswordInput);
        passwordForm.appendChild(currentPasswordGroup);
        
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
        passwordForm.appendChild(buttonGroup);
        
        // Add the completed form to the profile options container
        profileOptions.appendChild(passwordForm);
        
        // Add form submission validation
        passwordForm.addEventListener("submit", function(event) {
            // Check if new password and confirmation match
            if (newPasswordInput.value !== confirmNewPasswordInput.value) {
                event.preventDefault(); // Stop form submission
                alert("New passwords do not match!");
            }
        });
    });
}); 
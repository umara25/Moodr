// Wait for the page to fully load before executing
window.addEventListener("load", function () {
    // Get references to the change username button and container div
    let changeUsernameBtn = document.getElementById("change-username-btn");
    let profileOptions = document.getElementById("profile-options");
    
    // Add click event listener to the change username button
    changeUsernameBtn.addEventListener("click", function () {
        // Clear any existing content in the profile options container
        profileOptions.innerHTML = "";
        
        // Create the main form element that will submit to PHP handler
        let usernameForm = document.createElement("form");
        usernameForm.id = "username-form";
        usernameForm.method = "POST";
        usernameForm.action = "updateUsername.php"; // Form submits to PHP handler
        
        // Create and add the form heading
        let heading = document.createElement("h2");
        heading.innerHTML = "Change Username";
        heading.className = "form-heading";
        usernameForm.appendChild(heading);
        
        // Username Group - Container for new username input
        let usernameGroup = document.createElement("div");
        usernameGroup.className = "form-group";
        
        // Create label for new username field
        let usernameLabel = document.createElement("label");
        usernameLabel.innerHTML = "New Username:";
        
        // Create input field for new username
        let usernameInput = document.createElement("input");
        usernameInput.type = "text"; // Plain text input
        usernameInput.name = "newUsername"; // Name for POST data
        usernameInput.required = true; // Form validation
        usernameInput.className = "form-input";

        // Add label and input to group, then add group to form
        usernameGroup.appendChild(usernameLabel);
        usernameGroup.appendChild(usernameInput);
        usernameForm.appendChild(usernameGroup);
        
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
        usernameForm.appendChild(passwordGroup);
        
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
        usernameForm.appendChild(buttonGroup);
        
        // Add the completed form to the profile options container
        profileOptions.appendChild(usernameForm);
    });
});
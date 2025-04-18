window.addEventListener("load", function () {
    let changePasswordBtn = document.getElementById("change-password-btn");
    let profileOptions = document.getElementById("profile-options");
    
    changePasswordBtn.addEventListener("click", function () {
        profileOptions.innerHTML = "";
        
        let passwordForm = document.createElement("form");
        passwordForm.id = "password-form";
        passwordForm.method = "POST";
        passwordForm.action = "updatePassword.php";
        
        let heading = document.createElement("h2");
        heading.innerHTML = "Change Password";
        heading.className = "form-heading";
        passwordForm.appendChild(heading);
        
        // New Password Group
        let newPasswordGroup = document.createElement("div");
        newPasswordGroup.className = "form-group";
        
        let newPasswordLabel = document.createElement("label");
        newPasswordLabel.innerHTML = "New Password:";
        
        let newPasswordInput = document.createElement("input");
        newPasswordInput.type = "password";
        newPasswordInput.name = "newPassword";
        newPasswordInput.required = true;
        newPasswordInput.className = "form-input";
        
        newPasswordGroup.appendChild(newPasswordLabel);
        newPasswordGroup.appendChild(newPasswordInput);
        passwordForm.appendChild(newPasswordGroup);
        
        // Confirm New Password Group
        let confirmNewPasswordGroup = document.createElement("div");
        confirmNewPasswordGroup.className = "form-group";
        
        let confirmNewPasswordLabel = document.createElement("label");
        confirmNewPasswordLabel.innerHTML = "Confirm New Password:";
        
        let confirmNewPasswordInput = document.createElement("input");
        confirmNewPasswordInput.type = "password";
        confirmNewPasswordInput.name = "confirmNewPassword";
        confirmNewPasswordInput.required = true;
        confirmNewPasswordInput.className = "form-input";
        
        confirmNewPasswordGroup.appendChild(confirmNewPasswordLabel);
        confirmNewPasswordGroup.appendChild(confirmNewPasswordInput);
        passwordForm.appendChild(confirmNewPasswordGroup);
        
        // Current Password Group
        let currentPasswordGroup = document.createElement("div");
        currentPasswordGroup.className = "form-group";
        
        let currentPasswordLabel = document.createElement("label");
        currentPasswordLabel.innerHTML = "Current Password:";
        
        let currentPasswordInput = document.createElement("input");
        currentPasswordInput.type = "password";
        currentPasswordInput.name = "currentPassword";
        currentPasswordInput.required = true;
        currentPasswordInput.className = "form-input";
        
        currentPasswordGroup.appendChild(currentPasswordLabel);
        currentPasswordGroup.appendChild(currentPasswordInput);
        passwordForm.appendChild(currentPasswordGroup);
        
        // Button Group
        let buttonGroup = document.createElement("div");
        buttonGroup.className = "button-group";
        
        let saveButton = document.createElement("button");
        saveButton.type = "submit";
        saveButton.innerHTML = "Save Changes";
        saveButton.className = "save-button";
        
        let cancelButton = document.createElement("button");
        cancelButton.type = "button";
        cancelButton.innerHTML = "Cancel";
        cancelButton.className = "cancel-button";
        
        cancelButton.addEventListener("click", function () {
            window.location.reload();
        });
        
        buttonGroup.appendChild(saveButton);
        buttonGroup.appendChild(cancelButton);
        passwordForm.appendChild(buttonGroup);
        
        profileOptions.appendChild(passwordForm);
        
        passwordForm.addEventListener("submit", function(event) {
            if (newPasswordInput.value !== confirmNewPasswordInput.value) {
                event.preventDefault();
                alert("New passwords do not match!");
            }
        });
    });
}); 
window.addEventListener("load", function () {
    let changeInfoBtn = document.getElementById("change-info-btn");
    let profileOptions = document.getElementById("profile-options");
    
    changeInfoBtn.addEventListener("click", function () {
        profileOptions.innerHTML = "";
        
        let infoForm = document.createElement("form");
        infoForm.id = "info-form";
        infoForm.method = "POST";
        infoForm.action = "updatePersonalInfo.php";
        
        let heading = document.createElement("h2");
        heading.innerHTML = "Change Personal Info";
        heading.className = "form-heading";
        infoForm.appendChild(heading);
        
        // Email Group
        let emailGroup = document.createElement("div");
        emailGroup.className = "form-group";
        
        let emailLabel = document.createElement("label");
        emailLabel.innerHTML = "Email:";
        
        let emailInput = document.createElement("input");
        emailInput.type = "email";
        emailInput.name = "email";
        emailInput.required = true;
        emailInput.className = "form-input";
        
        emailGroup.appendChild(emailLabel);
        emailGroup.appendChild(emailInput);
        infoForm.appendChild(emailGroup);
        
        // Password Group for verification
        let passwordGroup = document.createElement("div");
        passwordGroup.className = "form-group";
        
        let passwordLabel = document.createElement("label");
        passwordLabel.innerHTML = "Confirm Password:";
        
        let passwordInput = document.createElement("input");
        passwordInput.type = "password";
        passwordInput.name = "confirmPassword";
        passwordInput.required = true;
        passwordInput.className = "form-input";
        
        passwordGroup.appendChild(passwordLabel);
        passwordGroup.appendChild(passwordInput);
        infoForm.appendChild(passwordGroup);
        
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
        infoForm.appendChild(buttonGroup);
        
        profileOptions.appendChild(infoForm);
        
        fetch('getUserInfo.php')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.email) {
                    emailInput.value = data.email;
                }
            });
    });
}); 
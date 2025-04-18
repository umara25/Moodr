window.addEventListener("load", function () {
    let changeUsernameBtn = document.getElementById("change-username-btn");
    let profileOptions = document.getElementById("profile-options");
    
    changeUsernameBtn.addEventListener("click", function () {
        profileOptions.innerHTML = "";
        
        let usernameForm = document.createElement("form");
        usernameForm.id = "username-form";
        usernameForm.method = "POST";
        usernameForm.action = "updateUsername.php";
        
        let heading = document.createElement("h2");
        heading.innerHTML = "Change Username";
        heading.className = "form-heading";
        usernameForm.appendChild(heading);
        
        let usernameGroup = document.createElement("div");
        usernameGroup.className = "form-group";
        
        let usernameLabel = document.createElement("label");
        usernameLabel.innerHTML = "New Username:";
        
        let usernameInput = document.createElement("input");
        usernameInput.type = "text";
        usernameInput.name = "newUsername";
        usernameInput.required = true;
        usernameInput.className = "form-input";
        
        usernameGroup.appendChild(usernameLabel);
        usernameGroup.appendChild(usernameInput);
        usernameForm.appendChild(usernameGroup);
        
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
        usernameForm.appendChild(passwordGroup);
        
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
        usernameForm.appendChild(buttonGroup);
        
        profileOptions.appendChild(usernameForm);
    });
}); 
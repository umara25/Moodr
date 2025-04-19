window.addEventListener("load", function(event) {
    let deleteProfileBtn = document.getElementById("delete-profile-btn");
    let deleteProfilePopup = document.getElementById("delete-profile");
    let closeBtn = deleteProfilePopup.querySelector(".close");
    let cancelBtn = document.getElementById("cancel-delete");
    let deleteForm = document.getElementById("delete-profile-form");
    let passwordInput = document.getElementById("password");
    let confirmPasswordInput = document.getElementById("confirmPassword");
    let confirmDeleteCheckbox = document.getElementById("confirmDelete");
    let passwordError = document.getElementById("password-error");
    let checkboxError = document.getElementById("checkbox-error");
    
    deleteProfileBtn.addEventListener("click", function() {
        deleteProfilePopup.style.display = "block";
        deleteForm.reset();
        passwordError.innerHTML = "";
        checkboxError.innerHTML = "";
    });
    
    closeBtn.addEventListener("click", function() {
        deleteProfilePopup.style.display = "none";
    });
    
    cancelBtn.addEventListener("click", function() {
        deleteProfilePopup.style.display = "none";
    });
    
    window.addEventListener("click", function(event) {
        if (event.target === deleteProfilePopup) {
            deleteProfilePopup.style.display = "none";
        }
    });
    
    deleteForm.addEventListener("submit", function(event) {
        let isValid = true;
        passwordError.innerHTML = "";
        checkboxError.innerHTML = "";
        
        if (passwordInput.value !== confirmPasswordInput.value) {
            passwordError.innerHTML = "Passwords do not match";
            isValid = false;
        }
        
        if (!confirmDeleteCheckbox.checked) {
            checkboxError.innerHTML = "You must confirm that you understand";
            isValid = false;
        }
        
        if (!isValid) {
            event.preventDefault();
        }
    });
}); 
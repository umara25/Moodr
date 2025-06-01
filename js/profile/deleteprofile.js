// Wait for the page to fully load before executing
window.addEventListener("load", function(event) {
    // Get references to all the delete profile popup elements
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
    
    // Show the delete profile popup when delete button is clicked
    deleteProfileBtn.addEventListener("click", function() {
        deleteProfilePopup.style.display = "block"; // Make popup visible
        deleteForm.reset(); // Clear all form fields
        passwordError.innerHTML = ""; // Clear any previous error messages
        checkboxError.innerHTML = "";
    });
    
    // Close popup when X button is clicked
    closeBtn.addEventListener("click", function() {
        deleteProfilePopup.style.display = "none"; // Hide popup
    });
    
    // Close popup when Cancel button is clicked
    cancelBtn.addEventListener("click", function() {
        deleteProfilePopup.style.display = "none"; // Hide popup
    });
    
    // Close popup when user clicks outside the popup content
    window.addEventListener("click", function(event) {
        if (event.target === deleteProfilePopup) {
            deleteProfilePopup.style.display = "none"; // Hide popup
        }
    });
    
    // Validate form before submission
    deleteForm.addEventListener("submit", function(event) {
        let isValid = true;
        // Clear previous error messages
        passwordError.innerHTML = "";
        checkboxError.innerHTML = "";
        
        // Check if passwords match
        if (passwordInput.value !== confirmPasswordInput.value) {
            passwordError.innerHTML = "Passwords do not match";
            isValid = false;
        }
        
        // Check if user confirmed they understand deletion is permanent
        if (!confirmDeleteCheckbox.checked) {
            checkboxError.innerHTML = "You must confirm that you understand";
            isValid = false;
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault(); // Stop form from submitting
        }
    });
});
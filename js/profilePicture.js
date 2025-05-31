// Wait for the entire page to load before setting up profile picture functionality
window.addEventListener("load", function(event) {
    // Get references to profile picture related elements
    let fileInput = document.getElementById("pfp-input");        // File input for selecting images
    let saveButton = document.getElementById("pfp-submit-btn");  // Submit button for uploading
    let profileImage = document.getElementById("profile-image"); // Current profile image display
    let successMessage = document.getElementById("success-message"); // Success notification element
    
    // Initially disable the save button until a valid file is selected
    saveButton.disabled = true;
    saveButton.classList.add("button-disabled"); // Add visual disabled styling
    
    // Store the original image source for restoration if needed
    let originalImageSrc = profileImage.src;
    // Check if user already has a custom profile image (not the default)
    let hasCustomImage = profileImage.src.indexOf("getPfp.php") > -1;
    
    // Add styling class if user has uploaded a custom image
    if (hasCustomImage) {
        profileImage.classList.add("custom-image");
    }
    
    // Add event listener for file input changes (when user selects a new image)
    fileInput.addEventListener("change", function() {
        let file = this.files[0]; // Get the selected file
        
        if (file) {
            // File was selected, proceed with validation
            let fileType = file.type; // Get MIME type of selected file
            
            // Define allowed image file types for security and compatibility
            let validTypes = ["image/jpeg", "image/jpg", "image/png"];
            
            // Validate file type - reject if not an allowed image format
            if (!validTypes.includes(fileType)) {
                alert("Only JPEG and PNG files are allowed."); // Show error message
                this.value = ""; // Clear the file input
                return false;    // Exit function without proceeding
            }
            
            // File is valid, enable the save button
            saveButton.disabled = false;
            saveButton.classList.remove("button-disabled"); // Remove disabled styling
            
            // Create object URL for immediate image preview
            // This lets the user see the image before it is uploaded
            let objectURL = URL.createObjectURL(file);
            
            // Update the profile image preview with selected file
            profileImage.src = objectURL;
            profileImage.classList.add("custom-image"); // Add custom image styling
        } else {
            // No file selected, disable save button and restore original image
            saveButton.disabled = true;
            saveButton.classList.add("button-disabled"); // Add disabled styling
            
            // Restore original profile image
            profileImage.src = originalImageSrc;
            
            // Remove custom image styling if user didn't have a custom image originally
            if (!hasCustomImage) {
                profileImage.classList.remove("custom-image");
            }
        }
    });
    
    // Handle success message display timeout
    // If there's a success message visible, hide it after 3 seconds
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = "none"; // Hide success message
        }, 3000); // 3 second delay
    }
});
window.addEventListener("load", function(event) {
    let fileInput = document.getElementById("pfp-input");
    let saveButton = document.getElementById("pfp-submit-btn");
    let profileImage = document.getElementById("profile-image");
    let successMessage = document.getElementById("success-message");
    
    saveButton.disabled = true;
    saveButton.classList.add("button-disabled");
    
    // Store the original profile image
    let originalImageSrc = profileImage.src;
    
    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            saveButton.disabled = false;
            saveButton.classList.remove("button-disabled");
            
            let selectedFile = fileInput.files[0];
            let objectURL = URL.createObjectURL(selectedFile);
            
            profileImage.src = objectURL;
        } else {
            saveButton.disabled = true;
            saveButton.classList.add("button-disabled");
            
            // Restore the original image
            profileImage.src = originalImageSrc;
        }
    });
    
    // hide  message after 3 seconds
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = "none";
        }, 3000); 
    }
}); 
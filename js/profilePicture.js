window.addEventListener("load", function(event) {
    let fileInput = document.getElementById("pfp-input");
    let saveButton = document.getElementById("pfp-submit-btn");
    let profileImage = document.getElementById("profile-image");
    let successMessage = document.getElementById("success-message");
    
    saveButton.disabled = true;
    saveButton.classList.add("button-disabled");
    
    let originalImageSrc = profileImage.src;
    let hasCustomImage = profileImage.src.indexOf("getPfp.php") > -1;
    
    if (hasCustomImage) {
        profileImage.classList.add("custom-image");
    }
    
    fileInput.addEventListener("change", function() {
        let file = this.files[0];
        
        if (file) {
            let fileType = file.type;
            
            let validTypes = ["image/jpeg", "image/jpg", "image/png"];
            
            if (!validTypes.includes(fileType)) {
                alert("Only JPEG and PNG files are allowed.");
                this.value = "";
                return false;
            }
            
            saveButton.disabled = false;
            saveButton.classList.remove("button-disabled");
            
            //this lets the user see the image before it is uploaded
            let objectURL = URL.createObjectURL(file);
            
            profileImage.src = objectURL;
            profileImage.classList.add("custom-image");
        } else {
            saveButton.disabled = true;
            saveButton.classList.add("button-disabled");
            
            profileImage.src = originalImageSrc;
            
            if (!hasCustomImage) {
                profileImage.classList.remove("custom-image");
            }
        }
    });
    
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = "none";
        }, 3000); 
    }
}); 
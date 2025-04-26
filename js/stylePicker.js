window.addEventListener("load", function(event) {
    let stylePicker = document.getElementById("style-picker");
    let styleForm = document.getElementById("style-form");
    let currentStyleID = document.getElementById("current-style-id").value;
    let themeFormContainer = document.getElementById("theme-form");
    let profileContainer = document.getElementById("profile-container");
    
    let changeThemeBtn = document.getElementById("change-theme-btn");
    changeThemeBtn.addEventListener("click", function() {
        profileContainer.style.display = "none";
        themeFormContainer.style.display = "block";
        fetchStyles();
    });
    
    let cancelThemeBtn = document.getElementById("cancel-theme");
    cancelThemeBtn.addEventListener("click", function() {
        profileContainer.style.display = "flex";
        themeFormContainer.style.display = "none";
    });
    
    function fetchStyles() {
        fetch("getStyles.php")
            .then(response => response.json())
            .then(success)
            .catch(error => console.error("Fetch error:", error));
            
        function success(styles) {
            stylePicker.innerHTML = "";
            
            for (let style of styles) {
                let option = document.createElement("option");
                option.value = style.styleID;
                option.textContent = style.styleID;
                
                if (style.styleID === currentStyleID) {
                    option.selected = true;
                }
                
                stylePicker.appendChild(option);
            }
            
            if (styles.length > 0) {
                for (let style of styles) {
                    if (style.styleID === stylePicker.value) {
                        updatePreviewColors(style);
                        break;
                    }
                }
            }
        }
    }
    
    stylePicker.addEventListener("change", function() {
        let selectedStyleID = this.value;
        
        fetch("getStyles.php")
            .then(response => response.json())
            .then(styleSuccess)
            .catch(error => console.error("Fetch error:", error));
            
        function styleSuccess(styles) {
            for (let style of styles) {
                if (style.styleID === selectedStyleID) {
                    updatePreviewColors(style);
                    break;
                }
            }
        }
    });
    
    function updatePreviewColors(style) {
        let primaryColor = document.getElementById("preview-primary");
        let secondaryColor = document.getElementById("preview-secondary");
        let textboxColor = document.getElementById("preview-textbox");
        let textColor = document.getElementById("preview-text");
        
        primaryColor.style.backgroundColor = style.primary;
        secondaryColor.style.backgroundColor = style.secondary;
        textboxColor.style.backgroundColor = style.textbox;
        textColor.style.backgroundColor = style.text;
    }
    
    styleForm.addEventListener("submit", function(event) {
        event.preventDefault();
        let selectedStyleID = stylePicker.value;
        
        let formData = new FormData();
        formData.append("styleID", selectedStyleID);
        
        fetch("updateUserStyle.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(submitSuccess)
        .catch(submitError);
        
        function submitSuccess(data) {
            if (data.success) {
                let successMsg = document.createElement("p");
                successMsg.textContent = "Style updated successfully! Refreshing...";
                successMsg.className = "style-success";
                styleForm.appendChild(successMsg);
                
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            } else {
                let errorMsg = document.createElement("p");
                errorMsg.textContent = data.message || "Failed to update style";
                errorMsg.className = "style-error";
                styleForm.appendChild(errorMsg);
            }
        }
        
        function submitError(error) {
            console.error("Error updating style:", error);
            let errorMsg = document.createElement("p");
            errorMsg.textContent = "An error occurred while updating style";
            errorMsg.className = "style-error";
            styleForm.appendChild(errorMsg);
        }
    });
}); 
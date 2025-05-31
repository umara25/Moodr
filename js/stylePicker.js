// Wait for the entire page to load before setting up theme selection functionality
window.addEventListener("load", function(event) {
    // Get references to main theme selection elements
    let stylePicker = document.getElementById("style-picker");        // Theme dropdown selector
    let styleForm = document.getElementById("style-form");            // Theme selection form
    let currentStyleID = document.getElementById("current-style-id").value; // User's current theme
    let themeFormContainer = document.getElementById("theme-form");   // Theme selection container
    let profileContainer = document.getElementById("profile-container"); // Main profile container
    
    // Button to open theme selection interface
    let changeThemeBtn = document.getElementById("change-theme-btn");
    changeThemeBtn.addEventListener("click", function() {
        profileContainer.style.display = "none";   // Hide profile content
        themeFormContainer.style.display = "block"; // Show theme selection form
        fetchStyles(); // Load available themes
    });
    
    // Button to cancel theme selection and return to profile
    let cancelThemeBtn = document.getElementById("cancel-theme");
    cancelThemeBtn.addEventListener("click", function() {
        profileContainer.style.display = "flex";   // Restore profile content
        themeFormContainer.style.display = "none"; // Hide theme selection form
    });
    
    /**
     * Fetches available themes from server and populates the dropdown selector
     * Also sets up the initial preview for the user's current theme
     */
    function fetchStyles() {
        // Request available themes from server
        fetch("getStyles.php")
            .then(response => response.json()) // Parse JSON response
            .then(success)                     // Handle successful fetch
            .catch(error => console.error("Fetch error:", error)); // Log any errors
            
        /**
         * Processes the themes array and populates dropdown options
         * @param {Array} styles - Array of theme objects from server
         */
        function success(styles) {
            stylePicker.innerHTML = ""; // Clear existing options
            
            // Create dropdown option for each available theme
            for (let style of styles) {
                let option = document.createElement("option");
                option.value = style.styleID;      // Set theme ID as value
                option.textContent = style.styleID; // Display theme name
                
                // Pre-select user's current theme
                if (style.styleID === currentStyleID) {
                    option.selected = true;
                }
                
                stylePicker.appendChild(option); // Add option to dropdown
            }
            
            // Set up initial color preview for selected theme
            if (styles.length > 0) {
                for (let style of styles) {
                    if (style.styleID === stylePicker.value) {
                        updatePreviewColors(style); // Show preview colors
                        break;
                    }
                }
            }
        }
    }
    
    // Handle theme selection changes in dropdown
    stylePicker.addEventListener("change", function() {
        let selectedStyleID = this.value; // Get newly selected theme ID
        
        // Fetch theme details to update preview
        fetch("getStyles.php")
            .then(response => response.json()) // Parse JSON response
            .then(styleSuccess)                // Handle theme data
            .catch(error => console.error("Fetch error:", error)); // Log any errors
            
        /**
         * Updates preview colors when user selects a different theme
         * @param {Array} styles - Array of theme objects from server
         */
        function styleSuccess(styles) {
            // Find the selected theme and update preview
            for (let style of styles) {
                if (style.styleID === selectedStyleID) {
                    updatePreviewColors(style); // Update color preview
                    break;
                }
            }
        }
    });
    
    /**
     * Updates the color preview elements to show selected theme colors
     * Provides visual feedback before theme is actually applied
     * @param {Object} style - Theme object containing color values
     */
    function updatePreviewColors(style) {
        // Get references to color preview elements
        let primaryColor = document.getElementById("preview-primary");     // Primary color preview
        let secondaryColor = document.getElementById("preview-secondary"); // Secondary color preview
        let textboxColor = document.getElementById("preview-textbox");     // Textbox color preview
        let textColor = document.getElementById("preview-text");           // Text color preview
        
        // Apply theme colors to preview elements
        primaryColor.style.backgroundColor = style.primary;     // Set primary background
        secondaryColor.style.backgroundColor = style.secondary; // Set secondary background
        textboxColor.style.backgroundColor = style.textbox;     // Set textbox background
        textColor.style.backgroundColor = style.text;           // Set text color background
    }
    
    // Handle theme selection form submission
    styleForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission
        let selectedStyleID = stylePicker.value; // Get selected theme ID
        
        // Prepare form data for theme update
        let formData = new FormData();
        formData.append("styleID", selectedStyleID);
        
        // Send theme update request to server
        fetch("updateUserStyle.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Parse JSON response
        .then(submitSuccess)               // Handle successful update
        .catch(submitError);               // Handle update errors
        
        /**
         * Handles successful theme update response
         * @param {Object} data - Server response containing success status
         */
        function submitSuccess(data) {
            if (data.success) {
                // Theme update successful - show success message and reload
                let successMsg = document.createElement("p");
                successMsg.textContent = "Style updated successfully! Refreshing...";
                successMsg.className = "style-success";
                styleForm.appendChild(successMsg);
                
                // Reload page after brief delay to show new theme
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            } else {
                // Theme update failed - show error message
                let errorMsg = document.createElement("p");
                errorMsg.textContent = data.message || "Failed to update style";
                errorMsg.className = "style-error";
                styleForm.appendChild(errorMsg);
            }
        }
        
        /**
         * Handles theme update errors
         * @param {Error} error - Error object from failed request
         */
        function submitError(error) {
            console.error("Error updating style:", error);
            let errorMsg = document.createElement("p");
            errorMsg.textContent = "An error occurred while updating style";
            errorMsg.className = "style-error";
            styleForm.appendChild(errorMsg);
        }
    });
});
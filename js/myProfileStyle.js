// Wait for the entire page to load before applying styles
window.addEventListener("load", function (event) {

    // Fetch user's current theme configuration from the server
    // style.php returns a JSON object with the user's selected color scheme
    fetch("style.php")
        .then(response => response.json()) // Parse JSON response
        .then(success)                     // Apply styles on successful fetch
        .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

    /**
     * Apply theme colors to all profile page elements
     * @param {Object} styleArr - Color scheme object from server containing:
     *                           - primary: main background color
     *                           - secondary: content areas and button backgrounds
     *                           - textbox: text areas and button text color
     *                           - text: color for bold text and labels
     */
    function success(styleArr){
        // Get references to main page elements that need styling
        let body = document.body;                                 // Main page body
        let content = document.getElementById("content");         // Main content container
        let textbox1 = document.getElementById("profile");        // Profile information container
        let pfpButtons = document.querySelectorAll(".pfp-btn");   // Profile picture action buttons
        let mainButtons = document.querySelectorAll(".profile-btn"); // Main profile action buttons
        let bio = document.getElementById("bio-textarea");        // User bio text area
        let Bs = document.getElementsByTagName("b");              // All bold text elements

        // Apply background colors to main layout elements
        body.style["background-color"] = styleArr["primary"];     // Set main page background
        content.style["background-color"] = styleArr["secondary"]; // Set content area background
        textbox1.style["background-color"] = styleArr["textbox"]; // Set profile container background
        
        // Style the bio textarea with contrasting colors for readability
        bio.style["background-color"] = styleArr["secondary"];    // Bio background color
        bio.style["color"] = styleArr["textbox"];                 // Bio text color
        
        // Loop through profile picture buttons and apply theme styling
        for(var i = 0; i < pfpButtons.length; i++) {
            pfpButtons[i].style.backgroundColor = styleArr["secondary"]; // Button background
            pfpButtons[i].style.color = styleArr["textbox"];             // Button text color
        }
        
        // Loop through main profile action buttons and apply theme styling
        for(var i = 0; i < mainButtons.length; i++) {
            mainButtons[i].style.backgroundColor = styleArr["secondary"]; // Button background
            mainButtons[i].style.color = styleArr["textbox"];             // Button text color
        }
        
        // Loop through all bold text elements and apply text color
        for(var i = 0; i < Bs.length; i++) {
            Bs[i].style.color = styleArr["text"]; // Set bold text color for labels and headings
        }
    }
});
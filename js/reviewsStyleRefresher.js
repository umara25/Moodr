// Wait for the entire page to load before setting up event listeners
window.addEventListener("load", function (event) {

    // When user creates a review it refreshes the color scheme to apply to new content
    let submit = document.getElementById("submit"); // Get the review submission button

    // Add click event listener to the submit button
    submit.addEventListener("click", function(event) {
        
        /**
         * Updates CSS styling for dynamically added review content
         * This function re-fetches the current theme and applies it to all review elements
         * Necessary because new reviews are added via AJAX and don't inherit theme styling
         */
        function updateCSS() {
            // Fetch current theme configuration from server
            fetch("style.php")
            .then(response => response.json()) // Parse JSON response
            .then(success)                     // Apply updated styles
            .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

            /**
             * Apply theme colors to review elements (focusing on newly added content)
             * @param {Object} styleArr - Color scheme object containing theme colors
             */
            function success(styleArr) {
                // Get references to review elements that need color updates
                let textbox1 = document.querySelectorAll(".review-content"); // All review content cards
                let textbox3 = document.querySelectorAll(".triangle");       // Speech bubble triangles
                let headers = document.getElementsByTagName("h1");           // All h1 heading elements
                let pars = document.getElementsByTagName("p");               // All paragraph elements

                // Apply background color to all review content cards (including new ones)
                textbox1.forEach(elm => {
                    elm.style["background-color"] = styleArr["textbox"];     // Update card background
                });
                
                // Apply border color to speech bubble triangles (including new ones)
                textbox3.forEach(elm => {
                    elm.style.borderRight = "20px solid " + styleArr["textbox"]; // Update triangle color
                });
                
                // Loop through all headers and apply current theme text color
                for(let i = 0; i < headers.length; i++) {
                    headers[i].style.color = styleArr["text"];              // Update header color
                }
                
                // Loop through paragraphs (starting from index 1 to preserve first paragraph styling)
                for(let i = 1; i < pars.length; i++) {
                    pars[i].style.color = styleArr["text"];                 // Update paragraph color
                }
            }
        }
        
        // Delay the style update by 100ms to allow the new review to be added to DOM first
        // This ensures the newly created review elements are included in the styling update
        setTimeout(updateCSS, 100);
    });

});
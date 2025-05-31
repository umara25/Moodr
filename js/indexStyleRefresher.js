// Wait for the entire page to load before setting up event listeners
window.addEventListener("load", function (event) {

    // When user makes a post it refreshes the color scheme to apply to new content
    let submit = document.getElementById("submit"); // Get the post submission button

    // Add click event listener to the submit button
    submit.addEventListener("click", function(event) {
        
        /**
         * Updates CSS styling for dynamically added content
         * This function re-fetches the current theme and applies it to all text elements
         * Necessary because new posts are added via AJAX and don't inherit theme styling
         */
        function updateCSS() {
            // Fetch current theme configuration from server
            fetch("style.php")
            .then(response => response.json()) // Parse JSON response
            .then(success)                     // Apply updated styles
            .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

            /**
             * Apply theme colors to text elements (focusing on newly added content)
             * @param {Object} styleArr - Color scheme object containing text color
             */
            function success(styleArr) {
                // Get references to text elements that need color updates
                let headers = document.getElementsByTagName("h1"); // All h1 heading elements
                let pars = document.getElementsByTagName("p");     // All paragraph elements
        
                // Loop through all h1 headers and apply current theme text color
                for (let i = 0; i < headers.length; i++) {
                    headers[i].style.color = styleArr["text"]; // Update heading color
                }
                
                // Loop through paragraphs (starting from index 1 to preserve first paragraph styling)
                for (let i = 1; i < pars.length; i++) { 
                    pars[i].style.color = styleArr["text"]; // Update paragraph color
                }
            }
        }
        
        // Delay the style update by 100ms to allow the new post to be added to DOM first
        // This ensures the newly created elements are included in the styling update
        setTimeout(updateCSS, 100);
    });

});
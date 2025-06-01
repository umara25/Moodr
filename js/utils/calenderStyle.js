// Wait for the entire page to load before applying styles
window.addEventListener("load", function (event) {

    // Fetch user's current theme configuration from the server
    // style.php returns a JSON object with the user's selected color scheme
    fetch("style.php")
        .then(response => response.json()) // Parse JSON response
        .then(success)                     // Apply styles on successful fetch
        .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

    /**
     * Apply theme colors to all calendar page elements
     * @param {Object} styleArr - Color scheme object from server containing:
     *                           - primary: main background color
     *                           - secondary: content area background
     *                           - textbox: calendar widget background
     *                           - text: text color for headings
     */
    function success(styleArr){
        // Get references to main page elements that need styling
        let body = document.body;                           // Main page body
        let content = document.getElementById("content");   // Main content container
        let textbox1 = document.getElementById("calender"); // Calendar widget container
        let headers = document.getElementsByTagName("h2");  // All h2 heading elements

        // Apply background colors to main layout elements
        body.style["background-color"] = styleArr["primary"];     // Set main page background
        content.style["background-color"] = styleArr["secondary"]; // Set content area background
        textbox1.style["background-color"] = styleArr["textbox"];  // Set calendar widget background
        
        // Loop through all h2 headers and apply text color
        for(let i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"]; // Set heading text color
        }
    }
});
// Wait for the entire page to load before applying styles
window.addEventListener("load", function (event) {

    // Fetch user's current theme configuration from the server
    // style.php returns a JSON object with the admin user's selected color scheme
    fetch("style.php")
        .then(response => response.json()) // Parse JSON response
        .then(success)                     // Apply styles on successful fetch
        .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

    /**
     * Apply theme colors to all user management page elements
     * @param {Object} styleArr - Color scheme object from server containing:
     *                           - primary: main background color
     *                           - secondary: content area background
     *                           - textbox: table and form backgrounds
     *                           - text: text color for all content
     */
    function success(styleArr){
        console.log(styleArr); // Debug output to verify theme data
        
        // Get references to main page elements that need styling
        let body = document.body;                               // Main page body
        let content = document.getElementById("content");       // Main content container
        let textbox1 = document.getElementById("styleCreate");  // Style creation form
        let textbox2 = document.getElementById("table");        // User management table
        let headers = document.getElementsByTagName("h1");      // All h1 heading elements
        let pars = document.getElementsByTagName("p");          // All paragraph elements
        let rows = document.querySelectorAll("table tr");       // All table rows

        // Add alternating row styling for better table readability
        rows.forEach((row, index) => {
            if ((index + 1) % 2 === 0) { // Every second row
                row.classList.add("odd-row"); // Add CSS class for alternating color
            }
        });

        // Apply background colors to main layout elements
        body.style["background-color"] = styleArr["primary"];     // Set main page background
        content.style["background-color"] = styleArr["secondary"]; // Set content area background
        textbox1.style["background-color"] = styleArr["textbox"];  // Set form background
        textbox2.style["background-color"] = styleArr["textbox"];  // Set table background
        
        // Apply text colors to form and table content
        textbox1.style["color"] = styleArr["text"];              // Set form text color
        textbox2.style["color"] = styleArr["text"];              // Set table text color
        
        // Apply text color to all headers
        for(let i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"];
        }
        for(let i = 1; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"];
        }
    }
});
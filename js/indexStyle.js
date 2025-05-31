// Wait for the entire page to load before applying styles
window.addEventListener("load", function (event) {

    // Fetch user's current theme configuration from the server
    // style.php returns a JSON object with the user's selected color scheme
    fetch("style.php")
        .then(response => response.json()) // Parse JSON response
        .then(success)                     // Apply styles on successful fetch
        .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

    /**
     * Apply theme colors to all homepage elements
     * @param {Object} styleArr - Color scheme object from server containing:
     *                           - primary: main background color
     *                           - secondary: content area background
     *                           - textbox: background for content boxes
     *                           - text: text color for headings and paragraphs
     */
    function success(styleArr){
        // Get references to main page elements that need styling
        let body = document.body;                                 // Main page body
        let content = document.getElementById("content");         // Main content container
        let textbox1 = document.getElementById("user-intro");     // User introduction section
        let textbox2 = document.getElementById("about-us");       // About us information section
        let textbox3 = document.getElementById("announcments");   // Announcements section
        let headers = document.getElementsByTagName("h1");        // All h1 heading elements
        let pars = document.getElementsByTagName("p");            // All paragraph elements

        // Apply background colors to main layout elements
        body.style["background-color"] = styleArr["primary"];     // Set main page background
        content.style["background-color"] = styleArr["secondary"]; // Set content area background
        
        // Apply background colors to content sections
        textbox1.style["background-color"] = styleArr["textbox"]; // User intro section background
        textbox2.style["background-color"] = styleArr["textbox"]; // About us section background
        textbox3.style["background-color"] = styleArr["textbox"]; // Announcements section background
        
        // Loop through all h1 headers and apply text color
        for(let i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"]; // Set heading text color
        }
        
        // Loop through paragraphs (starting from index 1 to skip first paragraph)
        // This preserves any special styling on the first paragraph element
        for(let i = 1; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"]; // Set paragraph text color
        }
    }

});
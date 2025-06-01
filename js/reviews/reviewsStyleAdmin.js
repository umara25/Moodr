// Wait for the entire page to load before applying styles
window.addEventListener("load", function (event) {

    // Fetch user's current theme configuration from the server
    // style.php returns a JSON object with the admin user's selected color scheme
    fetch("style.php")
        .then(response => response.json()) // Parse JSON response
        .then(success)                     // Apply styles on successful fetch
        .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

    /**
     * Apply theme colors to all admin reviews page elements
     * @param {Object} styleArr - Color scheme object from server containing:
     *                           - primary: main background color
     *                           - secondary: content area background
     *                           - textbox: review cards, form, and speech bubble color
     *                           - text: text color for all text content
     */
    function success(styleArr){
        // Get references to main page elements that need styling
        let body = document.body;                                    // Main page body
        let content = document.getElementById("content");            // Main content container
        let textbox1 = document.querySelectorAll(".review-content"); // All review content cards
        let textbox2 = document.getElementById("make-post");         // Admin review creation form
        let textbox3 = document.querySelectorAll(".triangle");       // Speech bubble triangles
        let headers = document.getElementsByTagName("h1");           // All h1 heading elements
        let pars = document.getElementsByTagName("p");               // All paragraph elements

        // Apply background colors to main layout elements
        body.style["background-color"] = styleArr["primary"];        // Set main page background
        content.style["background-color"] = styleArr["secondary"];   // Set content area background
        
        // Apply background color to all review content cards
        textbox1.forEach(elm => {
            elm.style["background-color"] = styleArr["textbox"];     // Set review card background
        });
        
        // Apply border color to speech bubble triangles for visual consistency
        textbox3.forEach(elm => {
            // Set right border to create triangle effect that matches review cards
            elm.style.borderRight = "20px solid " + styleArr["textbox"];
        });
        
        // Apply background color to admin review creation form
        textbox2.style["background-color"] = styleArr["textbox"];    // Admin form background
        
        // Apply text color to all headers (including page title and review titles)
        for(let i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"];              // All header text color
        }
        
        // Apply text color to review content paragraphs (skip first paragraph for special styling)
        for(let i = 1; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"];                 // Review content text color
        }
    }
});
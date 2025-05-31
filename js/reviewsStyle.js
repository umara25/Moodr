// Wait for the entire page to load before applying styles
window.addEventListener("load", function (event) {

    // Fetch user's current theme configuration from the server
    // style.php returns a JSON object with the user's selected color scheme
    fetch("style.php")
        .then(response => response.json()) // Parse JSON response
        .then(success)                     // Apply styles on successful fetch
        .catch(error => console.error("Fetch error:", error)); // Log any fetch errors

    function success(styleArr){
        console.log(styleArr); // Debug output to verify theme data
        
        // Get references to main page elements that need styling
        let body = document.body;                                    // Main page body
        let content = document.getElementById("content");            // Main content container
        let textbox1 = document.querySelectorAll(".review-content"); // All review content cards
        //let textbox2 = document.getElementById("make-post");        // Review creation form (commented out)
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
        
        //textbox2.style["background-color"] = styleArr["textbox"];  // Review form styling (commented out)
        
        // Apply special styling to page title (first header)
        headers[0].style.color = styleArr["textbox"];               // Page title gets textbox color
        
        // Apply text color to review titles and content headers (skip first header)
        for(let i = 1; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"];              // Review title color
        }
        
        // Apply text color to review content paragraphs (skip first paragraph)
        for(let i = 1; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"];                 // Review content text color
        }
    }
});
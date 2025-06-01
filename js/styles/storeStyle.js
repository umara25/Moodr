// Wait for the entire page to load before setting up theme creation functionality
window.addEventListener("load",function(event){ 

    // Get references to form elements for theme creation
    let button = document.getElementById("confirmStyle");   // Submit button for new theme
    let primary = document.getElementById("primary");       // Primary color picker
    let secondary = document.getElementById("secondary");   // Secondary color picker
    let textbox =  document.getElementById("textbox");     // Textbox color picker
    let text = document.getElementById("text");             // Text color picker

    // Add click event listener to the confirm/submit button
    button.addEventListener("click",function(event){ 

        // Construct URL with color values, removing the # prefix from hex colors
        let url = "../php/storeStyle.php?primary="+primary.value.substring(1) + 
        "&secondary="+secondary.value.substring(1)+"&text="+text.value.substring(1) 
        + "&textbox=" + textbox.value.substring(1); // Removes the # from hex color codes

        console.log(url); // Debug output to verify URL construction

        // Send AJAX request to store the new theme
        fetch(url)
        .then(response=>response.text()) // Parse response as text
        .then(success);                  // Handle successful response

    });

    /**
     * Handles the server response after attempting to store a new theme
     * @param {String} text - Server response indicating success or failure
     */
    function success(text){ 
        console.log(text); // Debug output

        let res = document.getElementById("styleResult"); // Get result display element
        
        if(text != -1){ 
            // Theme was inserted/stored successfully
            res.innerHTML = "STYLE STORED SUCCESSFULLY";
        }else{
            // Theme creation failed (likely because it already exists)
            res.innerHTML = "STYLE ALREADY EXISTS";
        }
    }

});
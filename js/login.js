// Wait for the page to fully load before executing
window.addEventListener("load",function(event){ 
    // Get reference to the login form element
    let myForm = document.getElementById("loginform");

    // Add event listener for when the form is submitted
    myForm.addEventListener("submit",function(event){ 

        /** 
         * Clear form after 5 seconds for security
         * This ensures you can't hit back arrow and have 
         * login information saved in the browser
         */
        setTimeout(function(){ 
            myForm.reset(); // Clear all form fields
        },5000); // Wait 5000 milliseconds (5 seconds)
    });
    
});
// Wait for the page to fully load before executing
window.addEventListener("load",function(event){ 

    // Get references to hamburger menu elements
    let hamDiv = document.getElementById("hamburger");  // Get main hamburger div
    let hamImg = hamDiv.querySelector("img");          // Get hamburger img
    let hamContent = document.getElementById("hamburger-content"); // Get hamburger content (navlinks)

    // Track whether the hamburger menu is currently open or closed
    let close = false;

    // Add click event listener to the hamburger icon
    hamImg.addEventListener("click",function(event){ 

        if(close){ // Hamburger nav is open, so close it
            this.src = "../images/hamburger.png"  // Change to hamburger icon
            hamContent.style["display"] = "none"  // Hide the navigation menu
            close = false; // Update state to closed
            
        }
        else{ // Hamburger nav is closed, so open it
            this.src = "../images/hamburgerclose.png"  // Change to X close icon
            hamContent.style["display"] = "block";     // Show the navigation menu
            close = true; // Update state to open

        }
    });
});
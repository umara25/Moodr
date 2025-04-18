window.addEventListener("load",function(event){ 

    let hamDiv = document.getElementById("hamburger");  // Get main hamburger div

    let hamImg = hamDiv.querySelector("img");          // Get hamburger img
    let hamContent = document.getElementById("hamburger-content"); // Get hamburger content (navlinks)

    let review = document.querySelector(".review");         // Get first review on page

    let close = false;

    hamImg.addEventListener("click",function(event){ 

        if(close){ // Hamburger nav is open, so close it
            this.src = "../images/hamburger.png"
            hamContent.style["display"] = "none"
            review.classList.remove("covered");   // Remove from covered class, enables any events
            close = false;
            
        }
        else{ // Hamburgernav is closed, so open it
            this.src = "../images/hamburgerclose.png"
            hamContent.style["display"] = "block";
            review.classList.add("covered");   // Add to covered class, disables any events
            close = true;

        }
    });
});
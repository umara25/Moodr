window.addEventListener("load",function(event){ 

    let hamDiv = document.getElementById("hamburger");  // Get main hamburger div

    let hamImg = hamDiv.querySelector("img");          // Get hamburger img
    let hamContent = document.getElementById("hamburger-content"); // Get hamburger content (navlinks)

    let close = false;

    hamImg.addEventListener("click",function(event){ 

        if(close){ // Hamburger nav is open, so close it
            this.src = "../images/hamburger.png"
            hamContent.style["display"] = "none"
            close = false;
            
        }
        else{ // Hamburgernav is closed, so open it
            this.src = "../images/hamburgerclose.png"
            hamContent.style["display"] = "block";
            close = true;

        }
    });
});
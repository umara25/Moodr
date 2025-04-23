window.addEventListener("load", function (event) {


    fetch("style.php")
        .then(response => response.json())
        .then(success)
        .catch(error => console.error("Fetch error:", error));

    function success(styleArr){
        let body = document.body;
        let content = document.getElementById("content");
        let textbox1 = document.getElementById("profile");
        let pfpButtons = document.querySelectorAll(".pfp-btn");
        let mainButtons = document.querySelectorAll(".profile-btn");
        let bio = document.getElementById("bio-textarea");
        let Bs = document.getElementsByTagName("b");

        body.style["background-color"] = styleArr["primary"];
        content.style["background-color"] = styleArr["secondary"];
        textbox1.style["background-color"] = styleArr["textbox"];
        bio.style["background-color"] = styleArr["secondary"];
        bio.style["color"] = styleArr["textbox"];
        for(var i = 0; i < pfpButtons.length; i++) {
            pfpButtons[i].style.backgroundColor = styleArr["secondary"];
            pfpButtons[i].style.color = styleArr["textbox"];
        }
        for(var i = 0; i < mainButtons.length; i++) {
            mainButtons[i].style.backgroundColor = styleArr["secondary"];
            mainButtons[i].style.color = styleArr["textbox"];
        }
        for(var i = 0; i < Bs.length; i++) {
            Bs[i].style.color = styleArr["text"];
        }
    }
});
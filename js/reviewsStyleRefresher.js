window.addEventListener("load", function (event) {

    //when user makes a post it refreshes the color scheme
    let submit = document.getElementById("submit");

    submit.addEventListener("click", function(event) {
        function updateCSS() {
            fetch("style.php")
            .then(response => response.json())
            .then(success)
            .catch(error => console.error("Fetch error:", error));

            function success(styleArr) {
                let textbox1 = document.querySelectorAll(".review-content");
                let textbox3 = document.querySelectorAll(".triangle");
                let headers = document.getElementsByTagName("h1");
                let pars = document.getElementsByTagName("p");

                textbox1.forEach(elm => {
                    elm.style["background-color"] = styleArr["textbox"];
                });
                textbox3.forEach(elm => {
                    elm.style.borderRight = "20px solid " + styleArr["textbox"];
                });
                for(let i = 0; i < headers.length; i++) {
                    headers[i].style.color = styleArr["text"];
                }
                for(let i = 1; i < pars.length; i++) {
                    pars[i].style.color = styleArr["text"];
                }
            }
        }
        
        setTimeout(updateCSS,100);
    });

});
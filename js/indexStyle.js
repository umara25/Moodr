window.addEventListener("load", function (event) {

    fetch("style.php")
        .then(response => response.json())
        .then(success)
        .catch(error => console.error("Fetch error:", error));

    function success(styleArr){
        console.log(styleArr);
        let body = document.body;
        let content = document.getElementById("content");
        let textbox1 = document.getElementById("user-intro");
        let textbox2 = document.getElementById("make-post");
        let textbox3 = document.getElementById("announcments");
        let headers = document.getElementsByTagName("h1");
        let pars = document.getElementsByTagName("p");
        let labels = document.getElementsByTagName("label");
        let links = document.getElementsByTagName("a");

        body.style["background-color"] = styleArr["primary"];
        content.style["background-color"] = styleArr["secondary"];
        textbox1.style["background-color"] = styleArr["textbox"];
        textbox2.style["background-color"] = styleArr["textbox"];
        textbox3.style["background-color"] = styleArr["textbox"];
        for(var i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"];
        }
        for(var i = 1; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"];
        }
        for(var i = 0; i < labels.length; i++) {
            labels[i].style.color = styleArr["text"];
        }
        for(var i = 6; i < links.length; i++) {
            links[i].style.color = styleArr["text"];
        }
    }


    //when user makes a post it refreshes the color scheme
    let submit = document.getElementById("submit");

    submit.addEventListener("click", function(event) {
        function updateCSS() {
            fetch("style.php")
            .then(response => response.json())
            .then(success)
            .catch(error => console.error("Fetch error:", error));

            function success(styleArr) {
                let headers = document.getElementsByTagName("h1");
                let pars = document.getElementsByTagName("p");
        
                for (let i = 0; i < headers.length; i++) {
                    headers[i].style.color = styleArr["text"];
                }
                for (let i = 1; i < pars.length; i++) { 
                    pars[i].style.color = styleArr["text"];
                }
            }
        }
        
        setTimeout(updateCSS,100);
    });

});
window.addEventListener("load", function (event) {


    fetch("style.php")
        .then(response => response.json())
        .then(success)
        .catch(error => console.error("Fetch error:", error));

    function success(styleArr){
        console.log(styleArr);
        let body = document.body;
        let content = document.getElementById("content");
        let textbox1 = document.getElementById("calender");
        let headers = document.getElementsByTagName("h2");
        let pars = document.getElementsByTagName("p");
        let labels = document.getElementsByTagName("label");

        body.style["background-color"] = styleArr["primary"];
        content.style["background-color"] = styleArr["secondary"];
        textbox1.style["background-color"] = styleArr["textbox"];
        for(var i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"];
        }
        for(var i = 1; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"];
        }
        for(var i = 0; i < labels.length; i++) {
            labels[i].style.color = styleArr["text"];
        }
    }
});
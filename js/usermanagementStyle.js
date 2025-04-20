window.addEventListener("load", function (event) {


    fetch("style.php")
        .then(response => response.json())
        .then(success)
        .catch(error => console.error("Fetch error:", error));

    function success(styleArr){
        console.log(styleArr);
        let body = document.body;
        let content = document.getElementById("content");
        let textbox1 = document.getElementById("styleCreate");
        let textbox2 = document.getElementById("table");
        let headers = document.getElementsByTagName("h1");
        let pars = document.getElementsByTagName("p");
        let labels = document.getElementsByTagName("label");
        let links = document.getElementsByTagName("a");
        let rows = document.querySelectorAll("table tr");

        rows.forEach((row, index) => {
            if ((index + 1) % 2 === 0) {
                row.classList.add("odd-row");
            }
        });

        body.style["background-color"] = styleArr["primary"];
        content.style["background-color"] = styleArr["secondary"];
        textbox1.style["background-color"] = styleArr["textbox"];
        textbox2.style["background-color"] = styleArr["textbox"];
        textbox1.style["color"] = styleArr["text"];
        textbox2.style["color"] = styleArr["text"];
        for(var i = 0; i < headers.length; i++) {
            headers[i].style.color = styleArr["text"];
        }
        for(var i = 0; i < pars.length; i++) {
            pars[i].style.color = styleArr["text"];
        }
        for(var i = 0; i < labels.length; i++) {
            labels[i].style.color = styleArr["text"];
        }
        for(var i = 0; i < links.length; i++) {
            links[i].style.color = styleArr["text"];
        }
    }
});
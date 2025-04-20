window.addEventListener("load", function (event) {


    fetch("style.php")
        .then(response => response.json())
        .then(success)
        .catch(error => console.error("Fetch error:", error));

    function success(styleArr){
        console.log(styleArr);
        let body = document.body;
        let content = document.getElementById("content");
        //let table = document.getElementById("table");

        body.style["background-color"] = styleArr["primary"];
        content.style["background-color"] = styleArr["secondary"];
        // table.style["background-color"] = styleArr["textbox"];
        // table.style["color"] = styleArr["text"];
    }
});
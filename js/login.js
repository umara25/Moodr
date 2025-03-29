window.addEventListener("load",function(event){ 

    let myform = document.getElementById("loginform");
    let namefield = document.getElementById("username");
    let pwdfield = document.getElementById("password");


    function success(text){ 
        let span = document.getElementById("result");
        span.innerHTML = text;
        console.log(text); // debug
    }

    myform.addEventListener("submit",function(event){ 
        let name = namefield.value;
        let pwd = pwdfield.value;
        console.log("hi");

        let url = "php/login.php?user="+name+"&password="+pwd;

        fetch(url)
            .then(response=>response.text())
            .then(success);

    });
    
});
window.addEventListener("load",function(event){ 

    let button = document.getElementById("confirmStyle");

    let primary = document.getElementById("primary");
    let secondary = document.getElementById("secondary");
    let text = document.getElementById("text");



    button.addEventListener("click",function(event){ 


        let url = "../php/storeStyle.php?primary="+primary.value.substring(1) + 
        "&secondary="+secondary.value.substring(1)+"&text="+text.value.substring(1); //Removes the #

        console.log(url);

        fetch(url)
        .then(response=>response.text())
        .then(success);

    });

    function success(text){ 
        console.log(text);

        let res = document.getElementById("styleResult");
        if(text != -1){ 
            res.innerHTML = "STYLE STORED SUCCESSFULLY";
        }else{
            res.innerHTML = "STYLE ALREADY EXISTS";
        }
    }

});
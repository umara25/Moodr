window.addEventListener("load",function(event){ 

    let email = document.getElementById("email");
    let confirm = document.getElementById("confirm");
    let myForm = document.getElementById("newAccountForm");
    let password = document.getElementById("password");


    myForm.addEventListener("submit",function(event){
        if(password.value.length < 8){
            password.value = "";
            password.placeholder = "MUST BE 8 CHARECTERS OR GREATER";
            password.classList.add("invalid");
            event.preventDefault();
        }
        if(email.value !== confirm.value){
            confirm.value = "";
            confirm.placeholder = "MUST MATCH EMAIL ABOVE";
            confirm.classList.add("invalid");
            event.preventDefault();
        }
    })
    
});
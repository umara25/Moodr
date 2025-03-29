window.addEventListener("load",function(event){ 

    let email = document.getElementById("email");
    let confirm = document.getElementById("confirm");
    let myForm = document.getElementById("newAccountForm");


    myForm.addEventListener("submit",function(event){
        if(email.value !== confirm.value){
            event.preventDefault();
        }
    })
    
});
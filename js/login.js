window.addEventListener("load",function(event){ 
    let myForm = document.getElementById("loginform");


    myForm.addEventListener("submit",function(event){ 

        /** 
         * Clear form after 5s, ensures
         * you can't hit back arrow and have 
         * information saved
         */
        setTimeout(function(){ 
            myForm.reset();
        },5000);
    });
    
});
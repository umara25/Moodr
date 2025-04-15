window.addEventListener("load", function (event) {
    let users = document.getElementById("table");

    
    // Event listener for trash icon clicks
    users.addEventListener("click", function (event) {
        let deleteUser = event.target.closest(".delete-user");
        if (deleteUser) { // Checks if the trash icon was clicked
            let sure = confirm("Are you sure you want to delete " + deleteUser.id+"?");
            if(sure){
                // Perform an AJAX request to delete the post
                let params = "user=" + deleteUser.id;
                let config = {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: params,
                };

                fetch("deleteUserHandler.php", config)
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                        if(data!=(-1)){
                            //remove the user from the DOM
                            let userElement = event.target.closest(".user"); //finds the nearest user class that was clicked
                            if (userElement) {
                                userElement.remove();
                            }
                        }else{
                            alert("Admins cannot be deleted.")
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            }
        }

        let makeAdmin = event.target.closest(".make-admin");
        if (makeAdmin) { // Checks if the trash icon was clicked
            let sure = confirm("Are you sure you want to make " + makeAdmin.id + " an admin?");
            if(sure){
                //perform an AJAX request to make admin
                let params = "user=" + makeAdmin.id;
                let config = {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: params,
                };

                fetch("makeAdminHandler.php", config)
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);//debug
                        
                        if(data!=(-1)){
                            //change role in dom
                            let role = document.getElementById("#"+makeAdmin.id); 
                            let userElement = event.target.closest(".user"); //finds the nearest user class that was clicked
                            if (userElement) {
                                role.innerHTML = "admin";
                            }
                        }else{
                            alert("User is already an admin.");
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            }
        }


        let banUser = event.target.closest(".ban-user");
        if (banUser) { // Checks if the trash icon was clicked
            let sure = confirm("Are you sure you want to ban/unban " + banUser.id + "?");
            if(sure){
                //perform an AJAX request to make ban user
                let params = "user=" + banUser.id;
                let config = {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: params,
                };

                fetch("banUserHandler.php", config)
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);//debug
                        let status = document.getElementById("##"+banUser.id); 
                        let userElement = event.target.closest(".user"); //finds the nearest user class that was clicked
                        if(data==="1"){
                            //change status in dom
                            if (userElement) {
                                console.log("got here");
                                status.innerHTML = "inactive";
                                banUser.innerHTML = "UNBAN USER";
                                banUser.classList.add("unbanned");
                            }
                        }else if(data==="2"){
                            //change status in dom
                            if (userElement) {
                                status.innerHTML = "active";
                                banUser.innerHTML = "BAN USER";
                                banUser.classList.remove("unbanned");
                            }
                        }
                        else{
                            alert("Cannot ban Admin.");
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            }
        }

    });

    resetAdmin = document.getElementById("reset-admin");
    resetAdmin.addEventListener("click", function (event) {
        let sure = confirm("Are you sure you want to reset admins?");
            if(sure){
                fetch("resetAdminHandler.php")
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);//debug
                        
                        if(data!=(-1)){
                            //change role in dom
                            let userRole = document.querySelectorAll(".role"); 
                            for(let user of userRole){
                                if(user.id !== "#Admin"){
                                    user.innerHTML = "user";
                                }
                            }
                        }else{
                            alert("Unable to proccess request.");
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            }
    });

    //Styles
    let button = document.getElementById("confirmStyle");
    let primary = document.getElementById("primary");
    let secondary = document.getElementById("secondary");
    let textbox =  document.getElementById("textbox");
    let text = document.getElementById("text");



    button.addEventListener("click",function(event){ 


        let url = "../php/storeStyle.php?primary="+primary.value.substring(1) + 
        "&secondary="+secondary.value.substring(1)+"&text="+text.value.substring(1) 
        + "&textbox=" + textbox.value.substring(1); //Removes the #

        console.log(url);

        fetch(url)
        .then(response=>response.text())
        .then(success);

    });

    function success(text){ 
        console.log(text);

        let res = document.getElementById("styleResult");
        if(text != -1){ 
            // Inserted correctly 
            res.innerHTML = "STYLE STORED SUCCESSFULLY";
        }else{
            // Wasn't able to insert
            res.innerHTML = "STYLE ALREADY EXISTS";
        }
    }


});
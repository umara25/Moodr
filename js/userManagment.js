window.addEventListener("load", function (event) {
    let users = document.getElementById("table");

    
    // Event listener for trash icon clicks
    users.addEventListener("click", function (event) {
        let deleteUser = event.target.closest(".delete-user");
        if (deleteUser) { // Checks if the trash icon was clicked
            let sure = confirm("Are you sure you want to delete " + deleteUser.id+"?")
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
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            }
        }
    });

});
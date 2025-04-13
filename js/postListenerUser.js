window.addEventListener("load", function (event) {
    /*Thumbs down and up reactions?*/
    let announcments = document.getElementById("posts");

    
    // Event listener for trash icon clicks
    announcments.addEventListener("click", function (event) {
        let trashIcon = event.target.closest(".trash-icon");
        if (trashIcon) { // Checks if the trash icon was clicked

            // Perform an AJAX request to delete the post
            let params = "postId=" + trashIcon.id;
            let config = {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: params,
            };

            fetch("deleteposthandler.php", config)
                .then(response => response.text())
                .then(data => {
                    // Remove the post from the DOM
                    let postElement = event.target.closest(".post"); // Finds the nearest post class that was clicked
                    if (postElement) {
                        postElement.remove();
                    }
                })
                .catch(error => console.error("Fetch error:", error));
        }
    });

});
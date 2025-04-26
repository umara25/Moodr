window.addEventListener("load", function (event) {
    let myForm = document.getElementById("make-post-form");
    let announcments = document.getElementById("posts");

    myForm.addEventListener("submit", function (event) {
        event.preventDefault();
        let title = document.getElementById("post-title").value;
        let msg = document.getElementById("post-message").value;

        /**
         * This function should be called when the AJAX call is complete
         * and the text has been extracted from the response.
         * @param {String} text 
         */
        function success(text) {
            let announcments = document.getElementById("posts");
            announcments.innerHTML = text + announcments.innerHTML + "";
            console.log(text); //debug
        }

        let params = "title=" + title + "&msg=" + msg;
        let config = {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" }, // Parameter format
            body: params // POST params are sent in the body
        };

        fetch("posthandler.php", config)
            .then(response => response.text())
            .then(success)
    });

    
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
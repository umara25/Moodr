window.addEventListener("load", function (event) {
    let myForm = document.getElementById("make-post-form");

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
            let post = announcments.querySelector(".post");
            post.querySelector(".trash-icon").addEventListener("click",deletePost);
            // console.log(text); //debug
            myForm.reset();
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

    
    // DELETION
    let deleteIcons = document.querySelectorAll(".trash-icon")

    for(let e of deleteIcons){ 
        e.addEventListener("click",deletePost);
    }


    /**
     * Traverses DOM to delete post the trash icon is inside 
     */
    function deletePost(){ 
        let toDelete = this.closest(".post"); // Get ancenstor of trash-icon 

        if (toDelete) {
            // Node to delete exists   
            let content = toDelete.querySelector(".post-title");    // Get review title
            let temp = content.innerHTML;   // Store copy of the innerHTML 

            // Create confirm / cancel buttons 
            content.innerHTML = (
                "<div class = 'delete'>" +
                "<h1> Are you sure you want to delete this post?</h1>" +
                "<input class = 'confirm-button' type = 'button' value = 'Yes'>" +
                "<input class = 'cancel-button' type = 'button' value = 'No'>" +
                "</div>"
            );

            let confirm = content.querySelector(".confirm-button"); // Get confirm button inside this div
            let cancel = content.querySelector(".cancel-button");   // Get cancel button inside this div

            // console.log(cancel);
            // console.log(confirm);

            /** 
             * Delete review tied to this element 
             */
            confirm.addEventListener("click", function (event) {
                let id = toDelete.id;   // Get ID of parent node
                let url = "../php/deleteposthandler.php" // Handles deleting from Databse
                console.log(toDelete);

                let params = "postId=" + id;
                let config = {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: params,
                };

                fetch(url,config)
                    .then(response => response.text())
                    .then(confirm_delete);
            });

            /** 
             * Reset innerHTML and reset trash icon event listener
             */
            cancel.addEventListener("click", function (event) {
                content.innerHTML = temp;
                toDelete.querySelector(".trash-icon")
                .addEventListener("click", deletePost); // Re-add event listener
            });

            /**
             * Receives the response from deletePostHandler.php
             * @param {Int} response 
             */
            function confirm_delete(response) {

                if (response == 1) {
                    // Successfully deleted
                    toDelete.remove();
                } else {
                    content.innerHTML = temp;
                    content.innerHTML += "<p class = 'error'>Unable to delete review...</p>";
                    toDelete.querySelector(".trash-icon")
                    .addEventListener("click", deletePost);
                }
            }
        }




    }

});
// Wait for the entire page to load before setting up event listeners
window.addEventListener("load", function (event) {
    // Get references to main form elements for post creation
    let myForm = document.getElementById("make-post-form");     // Admin post creation form
    let announcments = document.getElementById("posts");       // Container for displaying posts

    // Add submit event listener to the post creation form
    myForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission to handle via AJAX
        
        // Extract form input values
        let title = document.getElementById("post-title").value;   // Post title input
        let msg = document.getElementById("post-message").value;   // Post message content

        /**
         * Handles successful post creation and updates the DOM
         * This function is called when the AJAX call is complete
         * and the HTML for the new post has been returned from the server
         * @param {String} text - HTML content for the new post returned from server
         */
        function success(text) {
            let announcments = document.getElementById("posts");
            // Prepend new post HTML to the existing posts container
            // This places the newest post at the top of the list
            announcments.innerHTML = text + announcments.innerHTML + "";
            console.log(text); // Debug output to verify post creation
        }

        // Prepare POST parameters for sending to server
        let params = "title=" + title + "&msg=" + msg;
        let config = {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" }, // Parameter format
            body: params // POST params are sent in the body
        };

        // Send AJAX request to create new post
        fetch("posthandler.php", config)
            .then(response => response.text()) // Parse response as text (HTML)
            .then(success)                     // Handle successful creation
    });

    
    // POST DELETION FUNCTIONALITY
    // Get all trash/delete icons for existing posts
    let deleteIcons = document.querySelectorAll(".trash-icon")

    // Add click event listeners to all delete icons
    for(let e of deleteIcons){ 
        e.addEventListener("click",deletePost);
    }

    /**
     * Initiates post deletion process with confirmation dialog
     * Traverses DOM to find the post container and replaces content with confirmation UI
     */
    function deletePost(){ 
        let toDelete = this.closest(".post"); // Get ancestor post container of the clicked trash icon

        if (toDelete) {
            // Post container exists, proceed with deletion confirmation
            let content = toDelete.querySelector(".post-title");    // Get post title element
            let temp = content.innerHTML;   // Store original innerHTML for potential restoration

            // Replace post title with confirmation dialog
            content.innerHTML = (
                "<div class = 'delete'>" +
                "<h1> Are you sure you want to delete this post?</h1>" +
                "<input class = 'confirm-button' type = 'button' value = 'Yes'>" +
                "<input class = 'cancel-button' type = 'button' value = 'No'>" +
                "</div>"
            );

            // Get references to the newly created confirmation buttons
            let confirm = content.querySelector(".confirm-button"); // Yes/confirm button
            let cancel = content.querySelector(".cancel-button");   // No/cancel button

            /** 
             * Handles confirmed deletion by sending AJAX request to server
             * Permanently removes the post from database and DOM
             */
            confirm.addEventListener("click", function (event) {
                let id = toDelete.id;   // Get unique ID of the post to delete
                let url = "../php/deleteposthandler.php" // Server endpoint for post deletion

                // Prepare deletion parameters
                let params = "postId=" + id;
                let config = {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: params,
                };

                // Send deletion request to server
                fetch(url,config)
                    .then(response => response.text()) // Parse response
                    .then(confirm_delete);             // Handle deletion result
            });

            /** 
             * Handles cancellation of deletion
             * Restores original post content and reattaches event listeners
             */
            cancel.addEventListener("click", function (event) {
                content.innerHTML = temp; // Restore original post title content
                // Reattach delete event listener to the trash icon
                toDelete.querySelector(".trash-icon")
                .addEventListener("click", deletePost); // Re-add event listener
            });

            /**
             * Processes server response after deletion attempt
             * @param {String} response - Server response (1 for success, other for failure)
             */
            function confirm_delete(response) {

                if (response == 1) {
                    // Deletion successful - remove post from DOM
                    toDelete.remove();
                } else {
                    // Deletion failed - restore content and show error
                    content.innerHTML = temp; // Restore original content
                    content.innerHTML += "<p class = 'error'>Unable to delete review...</p>";
                    // Reattach delete event listener
                    toDelete.querySelector(".trash-icon")
                    .addEventListener("click", deletePost);
                }
            }
        }
    }

});
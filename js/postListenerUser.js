// Wait for the entire page to load before setting up event listeners
window.addEventListener("load", function (event) {
    /*Future enhancement: Thumbs down and up reactions could be added here*/
    
    // Get reference to the posts container for event delegation
    let announcments = document.getElementById("posts");

    // Use event delegation to handle clicks on dynamically loaded content
    // Event listener for trash icon clicks within the posts container
    announcments.addEventListener("click", function (event) {
        // Check if the clicked element is a trash icon or contains one
        let trashIcon = event.target.closest(".trash-icon");
        
        if (trashIcon) { // Checks if the trash icon was actually clicked
            
            // Perform immediate AJAX request to delete the post (no confirmation for users)
            // Prepare deletion parameters using the trash icon's ID
            let params = "postId=" + trashIcon.id;
            let config = {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: params,
            };

            // Send deletion request to server
            fetch("deleteposthandler.php", config)
                .then(response => response.text()) // Parse server response
                .then(data => {
                    // Remove the post from the DOM immediately upon successful deletion
                    let postElement = event.target.closest(".post"); // Find the post container
                    if (postElement) {
                        postElement.remove(); // Remove post from display
                    }
                })
                .catch(error => console.error("Fetch error:", error)); // Log any network errors
        }
    });

});
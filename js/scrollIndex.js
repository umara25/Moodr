// Wait for the entire page to load before setting up infinite scroll functionality
window.addEventListener("load", function (event) {

    let loading = false;  // Flag to ensure only one AJAX request fires at a time
    let debounce;         // Timeout variable for debouncing scroll events

    // Add scroll event listener to trigger infinite loading
    window.addEventListener("scroll", send_requests);

    /**
     * Handles scroll events and initiates AJAX requests when user reaches bottom of page
     * Uses debouncing to prevent excessive API calls during rapid scrolling
     */
    function send_requests() {

        clearTimeout(debounce); // Clear previous timeout
        
        // Debounce mechanism: only check scroll position after user stops scrolling for 100ms
        // This prevents constant API calls during active scrolling
        debounce = setTimeout(function () {
            const threshold = 300; // Pixels from bottom to trigger loading
            
            // Check if user is near end of page and not already loading data
            // Uses two different methods for cross-browser compatibility
            if ((window.innerHeight + window.scrollY >= document.body.offsetHeight - threshold && !loading) ||
                (window.innerHeight + document.documentElement.scrollTop) >= document.documentElement.scrollHeight - threshold
                && !loading) {
                
                let icon = create_load(); // Show loading indicator
                let url = "../php/scrollIndexHandler.php"; // Server endpoint for more posts
                
                // Fetch additional posts from server
                fetch(url)
                    .then(response => response.json()) // Parse JSON response
                    .then(data => success(data, icon)); // Handle successful response
            }
        }, 100); // 100ms debounce delay
    }

    /**
     * Creates and displays a loading icon while fetching new content
     * Sets loading flag to prevent multiple simultaneous requests
     * @returns {HTMLElement} The loading icon div element
     */
    function create_load() {
        let load = document.createElement("div");           // Create container div for loader
        load.classList.add("load");                         // Add CSS class for loading styling
        
        let posts = document.getElementById("posts");       // Get posts container
        posts.appendChild(load);                           // Add loading icon to page
        loading = true; // Set flag to prevent additional requests while loading
        return load;    // Return element reference for later removal
    }

    /**
     * Removes loading icon after data has been successfully loaded
     * Resets loading flag to allow new requests
     * @param {HTMLElement} load - The loading icon element to remove
     */
    function remove_load(load) {
        load.remove();      // Remove loading icon from DOM
        loading = false;    // Clear loading flag to allow new requests
    }

    /**
     * Processes server response and renders new posts or handles end-of-content
     * @param {Array} arr - Array of post objects from server
     * @param {HTMLElement} icon - Loading icon element to remove/modify
     */
    function success(arr, icon) {
        let postField = document.getElementById("posts"); // Get posts container

        // Check if server returned any new posts
        if (arr !== undefined && arr.length != 0) {
            // New posts available - render each one
            for (let obj of arr) {
                renderPost(obj, postField); // Render individual post
            }
            remove_load(icon); // Remove loading indicator
        } else { 
            // No more posts available - display end message and cleanup
            icon.classList.remove("load");  // Remove loading styling
            icon.classList.add("error");    // Add end-of-content styling
            icon.innerHTML = "<h3 id='noposts'><span>No more posts..</span></h3>";
            
            // Remove scroll event listener to prevent unnecessary API calls
            window.removeEventListener("scroll", send_requests);
        }
    }

    /**
     * Dynamically creates and renders a post element based on post data
     * Constructs complete DOM structure with profile picture, title, content, and timestamp
     * Note: This is a simplified version compared to review rendering (no images or scores)
     * 
     * @param {Object} post - Post object containing:
     *   {username: user, title: post title, msg: post body, 
     *    date: date post posted, pfp: pfp_path, id: postID}
     *   Note: pfp only exists if a valid pfp is stored, else it is empty
     * @param {HTMLElement} element - Container element to append the post to
     */
    function renderPost(post, element) {
        // Create main post container structure
        let postDiv = document.createElement("div");          // Main post container
        postDiv.id = post.id;                                // Set unique postID as element ID
        postDiv.classList.add("post");                       // Add post CSS class

        let postPfpDiv = document.createElement("div");       // Profile picture container
        postPfpDiv.classList.add("post-pfp");                // Add profile picture CSS class

        let postContentDiv = document.createElement("div");   // Main content container
        postContentDiv.classList.add("textbox");             // Add textbox styling class

        let postTitleDiv = document.createElement("div");     // Title/header container
        postTitleDiv.classList.add("post-title");            // Add title CSS class

        let postTextDiv = document.createElement("div");      // Text content container
        postTextDiv.classList.add("post-text");              // Add text CSS class

        // Construct DOM tree hierarchy
        element.appendChild(postDiv); // Add post to the end of posts container

        // Build post structure
        postDiv.appendChild(postPfpDiv);      // Add profile picture section
        postDiv.appendChild(postContentDiv);  // Add main content section
        postContentDiv.appendChild(postTitleDiv); // Add title section
        postContentDiv.appendChild(postTextDiv);  // Add text content section

        // Populate content with actual post data

        // Handle user profile picture (custom or default)
        if (post.pfp) {
            // User has uploaded a custom profile picture
            postPfpDiv.innerHTML = "<img src =" + post.pfp + ">";
        } else {
            // Use default profile picture
            postPfpDiv.innerHTML = "<img src = '../images/defaultpfp.jpg'>";
        }
        
        // Populate title section with post title, username, and timestamp
        postTitleDiv.innerHTML = (
            "<h1> " + post.title + " - " + post.username
            + " <span class = 'timestamp'>" + post.date + "</span></h1>"
        );

        // Populate text section with post message content
        postTextDiv.innerHTML = (
            "<p>" + post.msg + "</p>"
        );
    }

});


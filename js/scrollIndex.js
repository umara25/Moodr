/** 
 * Handles making AJAX requests to scrollHandler.php for posts page
 * i.e Implements infinite scrolling for posts page
 * Does not include DELETE to new posts
 */

window.addEventListener("load", function (event) {

    let loading = false;  // Ensures only 1 event fires at a time
    let debounce;

    // Add scroll event listener
    window.addEventListener("scroll", send_requests);

    /**
    * Sends AJAX requests once end of screen
    */
    function send_requests() {

        clearTimeout(debounce);
        // Help with event firing issue, only do a check once you stop scrolling
        // Scrolling causes the timeout to be reset, so it only does a check after you stop scrolling for 100ms
        debounce = setTimeout(function () {
            const threshold = 300;
            // Check if end of page and you aren't already loading data
            if ((window.innerHeight + window.scrollY >= document.body.offsetHeight - threshold && !loading) ||
                (window.innerHeight + document.documentElement.scrollTop) >= document.documentElement.scrollHeight - threshold
                && !loading) {
                let icon = create_load();
                let url = "../php/scrollIndexHandler.php";
                // console.log(url);

                fetch(url)
                    .then(response => response.json())
                    .then(data => success(data, icon));
            }
        }, 100);

    }



    /**
     * Renders a loading icon on the screen
     * Used when pulling more information
     * returns div containing the load element
     */
    function create_load() {
        let load = document.createElement("div");           // Create div for loader to go inside
        load.classList.add("load");                         // Add it to load class

        let posts = document.getElementById("posts");   // Get posts div

        posts.appendChild(load);
        loading = true; // Pulling data from db, don't allow any events to fire
        return load;
    }


    /**
     * Removes loading icon after data has been pulled
     * @param {HTML Element} load
     */
    function remove_load(load) {
        load.remove();  // Remove load icon
        loading = false;   // Done rendering data, allow events
    }


    /**
     * Receives Array of objects representing posts to render 
     * And HTML element corresponding to load div
     * @param {Array} arr 
     * @param {HTML Element} icon 
     */
    function success(arr, icon) {
        let postField = document.getElementById("posts");

        // Empty array check
        if (arr !== undefined && arr.length != 0) {

            // Render each object in array
            for (let obj of arr) {
                renderReview(obj, postField);
            }
            remove_load(icon);

        } else { // No more posts, so display error message
            icon.classList.remove("load");
            icon.classList.add("error");
            icon.innerHTML = "<h3 id='noposts'><span>No more posts..</span></h3>";
            // setTimeout(function () { remove_load(icon); }, 1000); // Timeout is to ensure they can read the message
            window.removeEventListener("scroll", send_requests);  // Remove event listener, not constantly sending queries to DB
        }


    }


    /**
         * Render post inside element based on post object received
         * {username: user, title: post title, msg: post body, score: post score, 
         * date: date post posted, img: img path, pfp: pfp_path, id: postID}
         * Note: pfp only exists if a valid pfp is stored else it is empty
         * Note: This is slightly modified compared to the one in postListener.js
         * @param {Object} post 
         * @param {HTML Element} element 
         */
    function renderPost(post, element) {
        let postDiv = document.createElement("div");          // Create post div 
        postDiv.id = post.id;                               // Give it the unique postID
        postDiv.classList.add("post");                      // Add it to class post

        // PFP not applied:
        let postPfpDiv = document.createElement("div");       // Create postpfp div
        postPfpDiv.classList.add("post-pfp");               // Add it to class pfp

        let postContentDiv = document.createElement("div");   // Create post-content Div
        postContentDiv.classList.add("textbox");

        let postTitleDiv = document.createElement("div");     // Create post-title div
        postTitleDiv.classList.add("post-title");

        // Not needed for post:
        // let reviewBodyDiv = document.createElement("div");      // Create review Body div
        // reviewBodyDiv.classList.add("review-body");

        let postTextDiv = document.createElement("div");      // Create post-text div
        postTextDiv.classList.add("post-text");


        // Construct DOM Tree
        element.appendChild(postDiv); // Add to end

        postDiv.appendChild(postPfpDiv);
        // postDiv.appendChild(triangleDiv);
        postDiv.appendChild(postContentDiv);

        postContentDiv.appendChild(postTitleDiv);
        // postContentDiv.appendChild(postBodyDiv);


        // Not needed for announcement page:
        // Check if an image was uploaded with review
        // if (review.img !== null && review.img !== undefined) {
        //     let reviewImgDiv = document.createElement("div");       // Create review image div
        //     reviewImgDiv.classList.add("review-img");
        //     reviewBodyDiv.appendChild(reviewImgDiv);
        //     reviewImgDiv.innerHTML = "<img src = " + review.img + ">";  // Render review image
        //     // console.log(review.img);
        // }

        // reviewBodyDiv.appendChild(reviewTextDiv);

        // Start altering innerHTML 
        if (post.pfp) {
            // Pfp exists 
            postPfpDiv.innerHTML = "<img src =" + post.pfp + ">";
        } else {
            // Pfp does not exist, use default
            postPfpDiv.innerHTML = "<img src = '../images/defaultpfp.jpg'>";
        }
        postTitleDiv.innerHTML = (
            "<h1> " + post.title + " - " + post.username
            + " <span class = 'timestamp'>" + post.date + "</span></h1>"
        ); // Render title 

        postTextDiv.innerHTML = (
            "<p>" + post.msg + "</p>"
        ); // Render text

    }


});


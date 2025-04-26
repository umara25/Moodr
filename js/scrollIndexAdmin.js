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
                renderPost(obj, postField);
            }
            remove_load(icon);
            updateCSS();

        } else { // No more posts, so display error message
            icon.classList.remove("load");
            icon.classList.add("error");
            icon.innerHTML = "<h3 id='noposts'><span>No more posts..</span></h3>";
            // setTimeout(function () { remove_load(icon); }, 1000); // Timeout is to ensure they can read the message
            window.removeEventListener("scroll", send_requests);  // Remove event listener, not constantly sending queries to DB
        }


    }

    /**
     * Render posts based on object returned form scrollIndexHandler.php
     * {id: post id, username: username, title: post title, msg: post text, 
     * date: date, pfp: profile photo path}
     * @param {*} post 
     * @param {*} element 
     */
    function renderPost(post,element){ 
        let postDiv = document.createElement("div");
        postDiv.id = post.id;
        postDiv.classList.add("post");

        let postPfpDiv = document.createElement("div");
        postPfpDiv.classList.add("post-pfp");

        let textBoxDiv = document.createElement("div");
        textBoxDiv.classList.add("textbox");

        let postTitleDiv = document.createElement("div");
        postTitleDiv.classList.add("post-title");

        let postTextDiv = document.createElement("div");
        postTextDiv.classList.add("post-text");

        let postTrashDiv = document.createElement("div");
        postTrashDiv.classList.add("trash-icon");
        postTrashDiv.id = post.id;

        // Construct DOM tree

        element.appendChild(postDiv);
        postDiv.append

        postDiv.appendChild(postPfpDiv);
        postDiv.appendChild(textBoxDiv);

        textBoxDiv.appendChild(postTitleDiv);
        textBoxDiv.appendChild(postTextDiv);

        postDiv.appendChild(postTrashDiv);

        // Alter innerHTML

        if (post.pfp) {
            // Pfp exists 
            postPfpDiv.innerHTML = "<img src =" + post.pfp + ">";
        } else {
            // Pfp does not exist, use default
            postPfpDiv.innerHTML = "<img src = '../images/defaultpfp.jpg'>";
        }

        postTitleDiv.innerHTML = (
            "<p><b>" + post.username + " - " + post.title + 
            "<span class = 'timestamp'> " + post.date + " </span></b></p>"
        );

        postTextDiv.innerHTML = "<p>" + post.msg + "</p>";

        postTrashDiv.innerHTML = "<img src='../images/trashicon.png' width='20px' height='20px'>";

        postDiv.querySelector(".trash-icon")
        .addEventListener("click", deletePost);


    }

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

    /**
     * Update CSS of new posts with users custom theme
     */
    function updateCSS() {
        fetch("style.php")
            .then(response => response.json())
            .then(success)
            .catch(error => console.error("Fetch error:", error));

            function success(styleArr){
                if(styleArr != -1){
                    let body = document.body;
                    let content = document.getElementById("content");
                    let textbox1 = document.getElementById("user-intro");
                    let textbox2 = document.getElementById("make-post");
                    let textbox3 = document.getElementById("announcments");
                    let headers = document.getElementsByTagName("h1");
                    let pars = document.getElementsByTagName("p");
                    let labels = document.getElementsByTagName("label");
            
                    body.style["background-color"] = styleArr["primary"];
                    content.style["background-color"] = styleArr["secondary"];
                    textbox1.style["background-color"] = styleArr["textbox"];
                    textbox2.style["background-color"] = styleArr["textbox"];
                    textbox3.style["background-color"] = styleArr["textbox"];
                    for(let i = 0; i < headers.length; i++) {
                        headers[i].style.color = styleArr["text"];
                    }
                    for(let i = 1; i < pars.length; i++) {
                        pars[i].style.color = styleArr["text"];
                    }
                    for(let i = 0; i < labels.length; i++) {
                        labels[i].style.color = styleArr["text"];
                    }
            }
        }

    }

});


/** 
 * Handles making AJAX requests to scrollHandler.php for reviews page
 * i.e Implements infinite scrolling for reviews page
 * Does not include DELETE to new reviews
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
                let url = "../php/scrollReviewHandler.php";
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

        let reviews = document.getElementById("reviews");   // Get reviews div

        reviews.appendChild(load);
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
     * Receives Array of objects representing reviews to render 
     * And HTML element corresponding to load div
     * @param {Array} arr 
     * @param {HTML Element} icon 
     */
    function success(arr, icon) {
        let reviewField = document.getElementById("reviews");

        // Empty array check
        if (arr !== undefined && arr.length != 0) {

            // Render each object in array
            for (let obj of arr) {
                renderReview(obj, reviewField);
            }
            remove_load(icon);
            updateCSS();

        } else { // No more reviews, so display error message
            icon.classList.remove("load");
            icon.classList.add("error");
            icon.innerHTML = "<h3><span style = 'color:white'>No more reviews..</span></h3>";
            // setTimeout(function () { remove_load(icon); }, 1000); // Timeout is to ensure they can read the message
            window.removeEventListener("scroll", send_requests);  // Remove event listener, not constantly sending queries to DB
        }


    }


    /**
         * Render review inside element based on review object received
         * {username: user, title: review title, msg: review body, score: review score, 
         * date: date review posted, img: img path, pfp: pfp_path, id: reviewID}
         * Note: pfp only exists if a valid pfp is stored else it is empty
         * Note: This is slightly modified compared to the one in reviewListener.js
         * @param {Object} review 
         * @param {HTML Element} element 
         */
    function renderReview(review, element) {
        let reviewDiv = document.createElement("div");          // Create review div 
        reviewDiv.id = review.id;                               // Give it the unique reviewID
        reviewDiv.classList.add("review");                      // Add it to class review

        let reviewPfpDiv = document.createElement("div");       // Create review-pfp div
        reviewPfpDiv.classList.add("review-pfp");               // Add it to class review-pfp

        let triangleDiv = document.createElement("div");        // Create triangle div (Gives speech bubble effect)
        triangleDiv.classList.add("triangle");

        let reviewContentDiv = document.createElement("div");   // Create review-content Div
        reviewContentDiv.classList.add("review-content");

        let reviewTitleDiv = document.createElement("div");     // Create review-title div
        reviewTitleDiv.classList.add("review-title");

        let reviewBodyDiv = document.createElement("div");      // Create review Body div
        reviewBodyDiv.classList.add("review-body");

        let reviewTextDiv = document.createElement("div");      // Create review-text div
        reviewTextDiv.classList.add("review-text");


        // Construct DOM Tree
        element.appendChild(reviewDiv); // Add to end

        reviewDiv.appendChild(reviewPfpDiv);
        reviewDiv.appendChild(triangleDiv);
        reviewDiv.appendChild(reviewContentDiv);

        reviewContentDiv.appendChild(reviewTitleDiv);
        reviewContentDiv.appendChild(reviewBodyDiv);


        // Check if an image was uploaded with review
        if (review.img !== null && review.img !== undefined) {
            let reviewImgDiv = document.createElement("div");       // Create review image div
            reviewImgDiv.classList.add("review-img");
            reviewBodyDiv.appendChild(reviewImgDiv);
            reviewImgDiv.innerHTML = "<img src = " + review.img + ">";  // Render review image
            // console.log(review.img);
        }

        reviewBodyDiv.appendChild(reviewTextDiv);

        // Start altering innerHTML 
        if (review.pfp) {
            // Pfp exists 
            reviewPfpDiv.innerHTML = "<img src =" + review.pfp + ">";
        } else {
            // Pfp does not exist, use default
            reviewPfpDiv.innerHTML = "<img src = '../images/defaultpfp.jpg'>";
        }
        reviewTitleDiv.innerHTML = (
            "<h1> " + review.title + " - " + review.username
            + " <span class = 'timestamp'>" + review.date + "</span></h1>"
        ); // Render title 

        reviewTextDiv.innerHTML = (
            "<p>" + review.msg + "</p>" +
            "<p>Score: " + review.score + "/10</p>"
        ); // Render text

    }

    /**
     * Update CSS of new posts with users custom theme
     */
    function updateCSS() {
        fetch("style.php")
            .then(response => response.json())
            .then(success)
            .catch(error => console.error("Fetch error:", error));

        function success(styleArr) {
            if(styleArr != -1){ 
                // console.log("Got Here");
                let textbox1 = document.querySelectorAll(".review-content");
                let textbox3 = document.querySelectorAll(".triangle");
                let headers = document.getElementsByTagName("h1");
                let pars = document.getElementsByTagName("p");

                textbox1.forEach(elm => {
                    elm.style["background-color"] = styleArr["textbox"];
                });
                textbox3.forEach(elm => {
                    elm.style.borderRight = "20px solid " + styleArr["textbox"];
                });
                for (let i = 0; i < headers.length; i++) {
                    headers[i].style.color = styleArr["text"];
                }
                for (let i = 1; i < pars.length; i++) {
                    pars[i].style.color = styleArr["text"];
                }
            }
        }
    }


});


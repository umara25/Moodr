// Wait for the entire page to load before setting up review functionality
window.addEventListener("load",function(event){ 

    /** 
     * Main controller that calls reviewhandler.php using AJAX when Admin creates a review
     * Is included on reviews.php and also handles review deletion functionality
     */

    // Get references to form elements and UI controls
    let myform = document.getElementById("make-review-form");      // Main review creation form
    let rangeSlider = document.getElementById("review-score");     // Score slider input (0-10)
    let rangeField = document.getElementById("score-field");       // Score display field
    let errorField = document.getElementById("review-fail");       // Review creation error display

    // Get references to form toggle elements
    let reviewOpenButton = document.getElementById("make-post-button");  // Button to open review form
    let reviewCloseButton = document.getElementById("close-post-img");   // Button to close review form
    let reviewFormDiv = document.getElementById("make-post");            // Container div for review form

    // Display the review creation form when open button is clicked
    reviewOpenButton.addEventListener("click",function(event){ 
        reviewFormDiv.style.display = "flex";      // Make form visible
        reviewOpenButton.style.display = "none";   // Hide the create review button
    })

    // Hide the review creation form when close button is clicked
    reviewCloseButton.addEventListener("click",function(event){  
        reviewFormDiv.style.display = "none";      // Hide the form
        reviewOpenButton.style.display = "flex";   // Make create review button visible again
    });

    /** 
     * Handle review form submission via AJAX with multipart form data
     * Processes text fields, score, and image file upload
     */
    myform.addEventListener("submit",function(event){ 
        event.preventDefault(); // Prevent default form submission
        errorField.innerHTML = ""; // Clear any previous error messages

        // Extract form field values
        let title = document.getElementById("review-title").value;     // Review title
        let msg = document.getElementById("review-message").value;     // Review content/message
        let score = parseFloat(document.getElementById("review-score").value); // Numeric score (0-10)
        let fileField = document.getElementById("album-cover");        // File input for album cover

        let file = fileField.files[0]; // Get the first uploaded file
        let uploadSize = 5 * (10**6);   // 5MB file size limit in bytes

        // Validate file size to prevent server overload
        if(file){
            if(file.size > uploadSize){
                // File exceeds size limit - show error and clear selection
                let fileLabel = document.getElementById("file-label"); 
                fileLabel.innerHTML = "<span style = 'color:red'> FILE TOO LARGE ; MUST BE LESS THAN 5MB</span>";
                fileField.value = null;  // Clear current file selection
                return; // Exit without submitting
            }
        }

        // Create FormData object for multipart form data (required for file uploads)
        let formData = new FormData();      // Create form data object for multipart form data type
        formData.append('title',title);     // Append review title
        formData.append('msg',msg);         // Append review body/message  
        formData.append('score',score);     // Append numeric review score
        formData.append('image', file);     // Append image file under name 'image'

        // Debug FormData contents (commented out for production)
        // for (let [key, value] of formData.entries()) {
        //     console.log(key,value);
        // }

        // Configure AJAX request for multipart form submission
        let config = {
            method: 'POST',
            // Note: Content-Type header is automatically set for FormData
            // headers: { "Content-Type": "multipart/form-data" }, // Not needed - browser sets this
            body: formData  // Send formData as parameters in HTTP request body
        };

        // Initiate AJAX request to create review
        fetch("../php/reviewhandler.php",config)
        .then(response=>response.json()) // Parse JSON response from server
        // .then(d =>console.log(d))      // Debug response (commented out)
        .then(success);                  // Handle successful response

    });

    /** 
     * Handles score slider interaction - updates display field in real-time
     * Provides immediate visual feedback as user adjusts review score
     */
    rangeSlider.addEventListener("input",function(event){ 
        let num = parseFloat(rangeSlider.value); // Get current slider value
        rangeField.innerHTML = "Score: "+ num + " / 10"; // Update display text
    })

    /**
     * Processes HTTP response from reviewhandler.php and updates UI accordingly
     * Displays new review if successful, otherwise shows error message
     * @param {Object} review - Review object containing:
     *   {username: user, title: review title, msg: review text, score: review score,
     *    date: date it was posted, img: img path, pfp: pfp_path, id: reviewID}
     *   Note: pfp only exists if a valid pfp is stored, else it is empty
     */ 
    function success(review){ 
        myform.reset(); // Clear all form fields
        rangeField.innerHTML = "Score: 0/10"; // Reset score display

        let reviewField = document.getElementById("reviews"); // Get reviews container

        if(review != -1){ 
            // Review was inserted successfully into database
            renderReview(review,reviewField); // Render new review to page immediately
        }else { 
            // Review creation failed - display error message
            errorField.innerHTML = "<h2>ERROR FAILED TO CREATE REVIEW</h2>";
        }
    }

    /**
     * Dynamically creates and renders a review element based on review data
     * Constructs complete DOM structure with profile picture, content, and interactions
     * @param {Object} review - Review object with user data, content, and metadata
     *   {username: user, title: review title, msg: review body, score: review score, 
     *    date: date review posted, img: img path, pfp: pfp_path, id: reviewID}
     *   Note: pfp only exists if a valid pfp is stored, else it is empty
     * @param {HTMLElement} element - Container element to append the review to
     */
    function renderReview(review,element){ 
        // Create main review container structure
        let reviewDiv = document.createElement("div");          // Main review container
        reviewDiv.id = review.id;                               // Set unique reviewID as element ID
        reviewDiv.classList.add("review");                      // Add review CSS class

        let reviewPfpDiv = document.createElement("div");       // Profile picture container
        reviewPfpDiv.classList.add("review-pfp");               // Add profile picture CSS class

        let triangleDiv = document.createElement("div");        // Speech bubble triangle
        triangleDiv.classList.add("triangle");                  // Creates visual speech bubble effect

        let reviewContentDiv = document.createElement("div");   // Main content container
        reviewContentDiv.classList.add("review-content");       

        let reviewTitleDiv = document.createElement("div");     // Title/header container
        reviewTitleDiv.classList.add("review-title");

        let reviewBodyDiv = document.createElement("div");      // Body content container
        reviewBodyDiv.classList.add("review-body");

        let reviewTextDiv = document.createElement("div");      // Text content container
        reviewTextDiv.classList.add("review-text");

        // Construct DOM tree hierarchy
        // Insert before second child (first child is typically an error div)
        element.insertBefore(reviewDiv,element.children[1]);  

        // Build review structure
        reviewDiv.appendChild(reviewPfpDiv);      // Add profile picture section
        reviewDiv.appendChild(triangleDiv);       // Add speech bubble triangle
        reviewDiv.appendChild(reviewContentDiv);  // Add main content section

        reviewContentDiv.appendChild(reviewTitleDiv); // Add title section
        reviewContentDiv.appendChild(reviewBodyDiv);  // Add body section

        // Handle optional album cover image
        if(review.img !== null && review.img !== undefined){ 
            let reviewImgDiv = document.createElement("div");       // Create image container
            reviewImgDiv.classList.add("review-img");               // Add image CSS class
            reviewBodyDiv.appendChild(reviewImgDiv);                // Add to body section
            reviewImgDiv.innerHTML = "<img src = " + review.img + ">"; // Insert image element
        }

        reviewBodyDiv.appendChild(reviewTextDiv); // Add text content to body

        // Populate content with actual review data

        // Handle user profile picture (custom or default)
        if(review.pfp){ 
            // User has uploaded a custom profile picture
            reviewPfpDiv.innerHTML = "<img src =" + review.pfp + ">"; 
        }else{  
            // Use default profile picture
            reviewPfpDiv.innerHTML = "<img src = '../images/defaultpfp.jpg'>";  
        }

        // Populate title section with review title, username, timestamp, and delete icon
        reviewTitleDiv.innerHTML =( 
            "<h1> " + review.title + " - " + review.username 
            + " <span class = 'timestamp'>" + review.date +"</span></h1>"
            + "<img class = 'trash-icon' src = '../images/trashicon.png'>"   
        );

        // Attach delete event listener to the newly created trash icon
        reviewTitleDiv.querySelector(".trash-icon")
        .addEventListener("click",deleteReview);

        // Populate text section with review message and score
        reviewTextDiv.innerHTML =(
             "<p>" + review.msg + "</p>" + 
             "<p>Score: " + review.score + "/10</p>"             
         );
    }

    // REVIEW DELETION FUNCTIONALITY

    // Get all existing trash icons and attach delete event listeners
    let deleteIcon = document.querySelectorAll(".trash-icon");

    for(let e of deleteIcon){ 
        e.addEventListener("click",deleteReview);
    }

    /**
     * Initiates review deletion process with confirmation dialog
     * Traverses DOM to find the review container and handles deletion workflow
     */
    function deleteReview(){ 
        // Find the review container that contains this trash icon
        let toDelete = this.closest(".review"); // Find closest ancestor with class .review

        if(toDelete){ 
            // Review container exists - proceed with deletion confirmation
            let content = toDelete.querySelector(".review-title");    // Get review title element
            let temp = content.innerHTML;   // Store original innerHTML for potential restoration

            // Replace title content with confirmation dialog
            content.innerHTML = (
                "<div class = 'delete'>"+
                "<h1> Are you sure you want to delete this review?</h1>"+
                "<input class = 'confirm-button' type = 'button' value = 'Yes'>" + 
                "<input class = 'cancel-button' type = 'button' value = 'No'>"+ 
                "</div>"
            );

            // Get references to the newly created confirmation buttons
            let confirm = content.querySelector(".confirm-button"); // Yes/confirm button
            let cancel = content.querySelector(".cancel-button");   // No/cancel button

            /** 
             * Handles confirmed deletion by sending request to server
             * Permanently removes review from database and DOM
             */
            confirm.addEventListener("click",function(event){ 
                let id = toDelete.id;   // Get unique review ID
                let url = "../php/deleteReviewHandler.php?id=" + id; // Server endpoint for deletion

                // Send deletion request to server
                fetch(url)
                .then(response=>response.text()) // Parse response as text
                .then(confirm_delete);           // Handle deletion result
            });

            /** 
             * Handles cancellation of deletion
             * Restores original content and reattaches event listeners
             */
            cancel.addEventListener("click",function(event){ 
                content.innerHTML = temp; // Restore original title content
                // Reattach delete event listener to trash icon
                content.querySelector(".trash-icon")
                .addEventListener("click",deleteReview);
            });

            /**
             * Processes server response after deletion attempt
             * @param {String} response - Server response (1 for success, other for failure)
             */
            function confirm_delete(response){ 

                if(response == 1){ 
                    // Deletion successful - remove review from DOM
                    toDelete.remove();
                }else{ 
                    // Deletion failed - restore content and show error
                    content.innerHTML = temp; // Restore original content
                    content.innerHTML += "<p class = 'error'>Unable to delete review...</p>";
                    // Reattach delete event listener
                    content.querySelector(".trash-icon")
                    .addEventListener("click",deleteReview);
                }
            }
        }
    }

});

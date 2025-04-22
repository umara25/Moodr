window.addEventListener("load",function(event){ 

    /** 
     * Calls reviewhandler.php using AJAX in response to Admin creating a review
     * Is included on reviews.php
     * Also handles review deletion 
     */


    let myform = document.getElementById("make-review-form");
    let rangeSlider = document.getElementById("review-score");
    let rangeField = document.getElementById("score-field");
    let errorField = document.getElementById("review-fail");               // Review creation error field


    let reviewOpenButton = document.getElementById("make-post-button");  // Open review form button
    let reviewCloseButton = document.getElementById("close-post-img");   // Close review form button
    let reviewFormDiv = document.getElementById("make-post");            // Div containing review form

    // Display the make review inputs form clicked
    reviewOpenButton.addEventListener("click",function(event){ 
        reviewFormDiv.style.display = "flex";    // Make form visible
        reviewOpenButton.style.display = "none";  // Hide create review 'button'
    })

    // Hide the make review form when clickeed
    reviewCloseButton.addEventListener("click",function(event){  
        reviewFormDiv.style.display = "none";    // Hide form 
        reviewOpenButton.style.display = "flex";  // Make create review 'button' visible

    });


    /** 
     * Handle submitting AJAX request 
     */
    myform.addEventListener("submit",function(event){ 
        event.preventDefault();
        errorField.innerHTML = "";      // Clear review upload error field


        let title = document.getElementById("review-title").value;
        let msg = document.getElementById("review-message").value;
        let score = parseFloat(document.getElementById("review-score").value);
        let fileField = document.getElementById("album-cover");

        let file = fileField.files[0]; //Get first fle uploaded 

        let uploadSize = 5 * (10**6);   // 5MB in bytes

        // Ensure file smaller than upload size
        if(file){
            if(file.size > uploadSize){
                let fileLabel = document.getElementById("file-label"); 
                fileLabel.innerHTML = "<span style = 'color:red'> FILE TOO LARGE ; MUST BE LESS THAN 5MB</span>";
                fileField.value = null;  // Clear current file;
                return;
            }
        }
        // Used to store form data, so it can be sent using fetch
        // Important when using multipart type data
        let formData = new FormData();      //Create form data object for multipart form data type
        formData.append('title',title);     //Append review title
        formData.append('msg',msg);         //Append review  body
        formData.append('score',score);     //Append review score
        formData.append('image', file);     //Append image under name 'image'

        // Debugging, view items inside formData 
        // for (let [key, value] of formData.entries()) {
        //     console.log(key,value);
        // }


        // console.log(file);
        let config = {
            method: 'POST',
            // headers: { "Content-Type": "multipart/form-data" }, // Parameter format, sending file
            body: formData  //Send formData as parameters in HTTP request body
        };

        // Initiate AJAX request
        fetch("../php/reviewhandler.php",config)
        .then(response=>response.json())
        // .then(d =>console.log(d))
        .then(success);

    });

    /** 
     * Handles Score slider, updates based on user input
     */
    rangeSlider.addEventListener("input",function(event){ 
        let num = parseFloat(rangeSlider.value);
        rangeField.innerHTML = "Score: "+ num + " / 10";

    })

    /**
     * Receives HTTP response from reviewhandler.php in form on object
     * Showcases new post if successsful, else displays error message
     * {username: user, title: review title, msg: review text, score: review score, 
     *  date: date it was posted, img: img path, pfp: pfp_path, id: reviewID}
     *  Note: pfp only exists if a valid pfp is stored else it is empty
     * @param {Object} review 
     */ 
    function success(review){ 
        myform.reset(); //Clear form
        rangeField.innerHTML = "Score: 0/10";

        // console.log("Hit here");
        let reviewField = document.getElementById("reviews");

        if(review != -1){ 
            //Inserted successfully 
            // reviewField.innerHTML = "SUCCESS";
            renderReview(review,reviewField); // Render new review to page
        }else { 
            errorField.innerHTML = "<h2>ERROR FAILED TO CREATE REVIEW</h2>";
        }
    }

    /**
     * Render review inside element based on review object received
     * {username: user, title: review title, msg: review body, score: review score, 
     * date: date review posted, img: img path, pfp: pfp_path, id: reviewID}
     * Note: pfp only exists if a valid pfp is stored else it is empty
     * @param {Object} review 
     * @param {HTML Element} element 
     */
    function renderReview(review,element){ 
        let reviewDiv = document.createElement("div");          // Create review div 
        reviewDiv.classList.add("review");                      // Add it to class review
        reviewDiv.id = review.id;                               // Give it the unique reviewID

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
        // element.appendChild(reviewDiv);
        element.insertBefore(reviewDiv,element.children[1]);  // Insert before second child
                                                               // First child is an error div

        reviewDiv.appendChild(reviewPfpDiv);
        reviewDiv.appendChild(triangleDiv);
        reviewDiv.appendChild(reviewContentDiv);

        reviewContentDiv.appendChild(reviewTitleDiv);
        reviewContentDiv.appendChild(reviewBodyDiv);


        // Check if an image was uploaded with review
        if(review.img !== null && review.img !== undefined){ 
            let reviewImgDiv = document.createElement("div");       // Create review image div
            reviewImgDiv.classList.add("review-img");
            reviewBodyDiv.appendChild(reviewImgDiv);
            reviewImgDiv.innerHTML = "<img src = " + review.img + ">";  // Render review image
            // console.log(review.img);
        }

        reviewBodyDiv.appendChild(reviewTextDiv);

        // Start altering innerHTML 

        if(review.pfp){ 
            // Pfp exists 
            reviewPfpDiv.innerHTML = "<img src =" + review.pfp + ">"; 
        }else{  
            // Pfp does not exist, use default
            reviewPfpDiv.innerHTML = "<img src = '../images/defaultpfp.jpg'>";  
        }

        reviewTitleDiv.innerHTML =( 
            "<h1>" + review.title + " - " + review.username 
            + "<span class = 'timestamp'>" + review.date +"</span></h1>"
            + "<img class = 'trash-icon' src = '../images/trashicon.png'>"   
        ); // Render title 

        // Add delete event to trash icon 
        reviewTitleDiv.querySelector(".trash-icon")
        .addEventListener("click",deleteReview);

        reviewTextDiv.innerHTML =(
             "<p>" + review.msg + "</p>" + 
             "<p>Score: " + review.score + "/10</p>"             
         ); // Render text


    }


    // DELETION

    let deleteIcon = document.querySelectorAll(".trash-icon");  // Select all trash icons 

    for(let e of deleteIcon){ 
        e.addEventListener("click",deleteReview);
    }

    /**
     * Traverses DOM to delete review   
     * the trash icon is inside 
     */
    function deleteReview(){ 
        let toDelete = this.closest(".review"); // Traverse DOM and find closest ancestor with class .review
                                                // This is the overarching review

        if(toDelete){ 
            // Node to delete exists   
            let content = toDelete.querySelector(".review-title");    // Get review title
            let temp = content.innerHTML;   // Store copy of the innerHTML 

            // Create confirm / cancel buttons 
            content.innerHTML = (
                "<div class = 'delete'>"+
                "<h1> Are you sure you want to delete this review?</h1>"+
                "<input class = 'confirm-button' type = 'button' value = 'Yes'>" + 
                "<input class = 'cancel-button' type = 'button' value = 'No'>"+ 
                "</div>"
            );

            let confirm = content.querySelector(".confirm-button"); // Get confirm button inside this div
            let cancel = content.querySelector(".cancel-button");   // Get cancel button inside this div

            // console.log(cancel);
            // console.log(confirm);

            /** 
             * Delete review tied to this element 
             */
            confirm.addEventListener("click",function(event){ 
                let id = toDelete.id;   // Get ID of parent node
                let url = "../php/deleteReviewHandler.php?id=" + id; // Handles deleting from Databse

                fetch(url)
                .then(toDelete.remove()); // Delete node

            });

            /** 
             * Reset innerHTML and reset trash icon event listener
             */
            cancel.addEventListener("click",function(event){ 
                content.innerHTML = temp;
                content.querySelector(".trash-icon")
                .addEventListener("click",deleteReview); // Re-add event listener
            });



        }
        console.log(toDelete);
    }


});

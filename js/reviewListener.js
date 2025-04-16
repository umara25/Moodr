window.addEventListener("load",function(event){ 

    /** 
     * Calls reviewhandler.php using AJAX in response to Admin 
     * creating a review
     * Is included on reviews.php
     */


    let myform = document.getElementById("make-review-form");
    let rangeSlider = document.getElementById("review-score");
    let rangeField = document.getElementById("score-field");


    let reviewOpenButton = document.getElementById("make-post-button");  // Open review form button
    let reviewFormDiv = document.getElementById("make-post");            // Div containing review form

    let reviewCloseButton = document.getElementById("close-post-img");  

    // Display the make review inputs when clicked
    reviewOpenButton.addEventListener("click",function(event){ 
        reviewFormDiv.style.display = "flex";    // Make form visible
        reviewOpenButton.style.display = "none";  // Hide create review 'button'
    })

    reviewCloseButton.addEventListener("click",function(event){  
        reviewFormDiv.style.display = "none";    // Hide form 
        reviewOpenButton.style.display = "flex";  // Make create review 'button' visible

    });


    /** 
     * Handle submitting AJAX request 
     */
    myform.addEventListener("submit",function(event){ 
        event.preventDefault();

        let title = document.getElementById("review-title").value;
        let msg = document.getElementById("review-message").value;
        let score = parseFloat(document.getElementById("review-score").value);
        let file = document.getElementById("album-cover").files[0]; //Get first fle uploaded 


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

        // let params = "title=" + title + "&msg=" + msg + "&score="+score + "&file="+file;
        // console.log(params);
        let config = {
            method: 'POST',
            // headers: { "Content-Type": "multipart/form-data" }, // Parameter format, sending file
            body: formData  //Send formData as parameters in HTTP request body
        };


        // Construct AJAX request URL using GET params
        // let url = "../php/reviewhandler.php?title=" +title + "&msg="+msg + "&score="+score;

        // Initiate AJAX request
        fetch("../php/reviewhandler.php",config)
        .then(response=>response.json())
        .then(d =>console.log(d))
        // .then(success);

        // fetch("../php/reviewhandler.php",config)
        // .then(response=>response.json())
        // .then(success);

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
     *  date: date it was posted, img: path to image (if one was sent)}
     * @param {Object} review 
     */ 
    function success(review){ 
        myform.reset(); //Clear form
        rangeField.innerHTML = "Score: 0/10";

        console.log("Hit here");
        let reviewField = document.getElementById("reviews");

        if(review != -1){ 
            //Inserted successfully 
            // reviewField.innerHTML = "SUCCESS";
            renderReview(review,reviewField); // Render new review to page
        }else { 
            reviewField.innerHTML = "<h1> <span style = 'color:red'>ERROR FAILED TO CREATE REVIEW</span></h1>";
        }
    }

    /**
     * Recieves object storing review information 
     * And HTML element you want to write this information to 
     * {username: user, title: review title, msg: review text, score: review score, 
     *  date: date it was posted}
     * @param {Object} review
     * @param {HTML Element} element
     */
    function renderReview(review,element){
        let str = "<div class = 'review'><img src = '../images/defaultpfp.jpg' width = '75px' height = '75px'>";
        str += "<div class = 'textbox'>";
        str += "<p><b>" + review.username + " - " + review.title + "</b></p>";
        str += "<p>" + review.msg + "</p></div>";
        str += "<div class = 'trash-icon'><img src = " + review.img + "width = '20px' height = '20px'>";
        str += "</div></div>";
        element.innerHTML = str;
        // element.innerHTML = review.username + review.title + review.text;
        console.log(review.img);

    }



});
// echo "<span class='post'><img src='../images/defaultpfp.jpg' width='75px' height='75px'>";
// echo "<div class='textbox'>";
// echo "<p><b>$_SESSION[username] - $title </b></p>";
// echo "<p>$message</p></div>";
// echo "<div class='trash-icon' id='$postId'>";
// echo "<img src='../images/trashicon.png' width='20px' height='20px'>";
// echo "</div></div></span>";
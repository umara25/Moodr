// Listens to where the user clicks and responds accordingly for both user/admin main pages.
window.addEventListener("load", function (event) {
    document.getElementById("dashb-btn").addEventListener("click", function (event) {
        window.location.href = 'index.php';
    })

    document.getElementById("cal-btn").addEventListener("click", function (event) {
        window.location.href = 'calendar.php';
    })

    document.getElementById("review-btn").addEventListener("click", function (event) {
        window.location.href = 'reviews.php';
    })

    document.getElementById("loginpage-btn").addEventListener("click", function (event) {
        //Sends you back to login and clears session variables
        window.location.href = 'logouthandler.php'; //Client-side redirect
    })

    document.getElementById("myprofile-btn").addEventListener("click", function (event) {
        window.location.href = 'myprofile.php';
    })

    document.getElementById("usermang-btn").addEventListener("click", function (event) {
        window.location.href = 'usermanagment.php';
    })
});
// Wait for the entire page to load before setting up social media functionality
window.addEventListener("load", function (event) {
    // Get references to social media button elements
    const instaButton = document.getElementById("insta-button");   // Instagram button
    const discordButton = document.getElementById("discord-button"); // Discord button

    // Add click event listener for Instagram button
    instaButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default link behavior
        // Open Moodr's Instagram page in a new tab to preserve user session
        window.open("https://www.instagram.com/macmoodfm/", "_blank");
    });

    // Add click event listener for Discord button
    discordButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default link behavior
        // Open Moodr's Discord server invitation in a new tab
        window.open("https://discord.gg/8crcQw9n", "_blank");
    });
});
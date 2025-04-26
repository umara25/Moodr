window.addEventListener("load", function (event) {
    // Get the Instagram and Discord buttons
    const instaButton = document.getElementById("insta-button");
    const discordButton = document.getElementById("discord-button");

    // Add event listener for Instagram button
    instaButton.addEventListener("click", function (event) {
        event.preventDefault();
        window.open("https://www.instagram.com/macmoodfm/", "_blank"); // Open Instagram in a new tab
    });

    // Add event listener for Discord button
    discordButton.addEventListener("click", function (event) {
        event.preventDefault();
        window.open("https://discord.gg/8crcQw9n", "_blank"); // Open Discord in a new tab
    });
});
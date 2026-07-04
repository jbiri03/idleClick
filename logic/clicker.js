let clicks = 0;

const clickCountElement = document.getElementById("clickCount");
const clickerButton = document.getElementById("clicker");
const saveButton = document.getElementById("saveButton");



clickerButton.addEventListener("click", () => {
    clicks++;
    clickCountElement.textContent = clicks;
    
});


//SAVE BUTTON CLICK EVENT LISTENER
saveButton.addEventListener("click", () => {
    var dataToSend = encodeURIComponent(clickCountElement.textContent);;
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "php/save_game.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error("Error saving game:", xhr.statusText);
            }
        }
    };
        xhr.send("clicks=" + dataToSend);
});








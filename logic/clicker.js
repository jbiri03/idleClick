import{game, upgrades, buyUpgrade } from "./game.js";

const upgradeData = document.getElementById("upgradeData");

game.multiplier = parseInt(upgradeData.dataset.multiplier) || 1;
game.clickPower = parseInt(upgradeData.dataset.clickpower) || 1;
game.cps = parseInt(upgradeData.dataset.cps) || 0;
game.bonus = parseInt(upgradeData.dataset.bonus) || 0;

console.log("Loaded upgrade stats:", game);

let clicks = parseInt(document.getElementById("clickCount1").textContent) || 0;

const clickCountElement = document.getElementById("clickCount1");
const clickCountElement2 = document.getElementById("clickCount2");
const clickerButton = document.getElementById("clicker");
const saveButton = document.getElementById("saveButton");

//LOAD SAVED CLICKS
game.sugar = parseInt(clickCountElement.textContent) || 0;


clickerButton.addEventListener("click", () => {
    //APPLY OOP LOGIC
    game.sugar += game.clickPower * game.multiplier;

    //UPDATE UI
    clickCountElement.textContent = game.sugar;
    clickCountElement2.textContent = game.sugar;
    saveToServer(game.sugar);
});

 
//SAVE BUTTON CLICK EVENT LISTENER
saveButton.addEventListener("click", () => {
    saveToServer(game.sugar);
    saveToServer(game.currency);
    saveToServer(game.multiplier);
    saveToServer(game.clickPower);
    saveToServer(game.cps);
    saveToServer(game.bonus);
});

function saveToServer(value){
    var dataToSend = encodeURIComponent(value);;
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
}




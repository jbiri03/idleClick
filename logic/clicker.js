import { game, upgrades, buyUpgrade } from "./game.js";

const upgradeData = document.getElementById("upgradeData");

game.multiplier = parseFloat(upgradeData.dataset.multiplier) || 1;
game.clickPower = parseFloat(upgradeData.dataset.clickpower) || 1;
game.cps = parseFloat(upgradeData.dataset.cps) || 0;
game.bonus = parseFloat(upgradeData.dataset.bonus) || 0;

console.log("Loaded upgrade stats:", game);

// LOAD SAVED CAKES
game.sugar = parseInt(document.getElementById("clickCount1").textContent) || 0;
game.currency = parseFloat(document.getElementById("cash").textContent) || 0;

const clickCountElement = document.getElementById("clickCount1");
const clickCountElement2 = document.getElementById("clickCount2");
const clickerButton = document.getElementById("clicker");
const saveButton = document.getElementById("saveButton");

// CLICK EVENT
clickerButton.addEventListener("click", () => {

    game.sugar += game.clickPower * game.multiplier;

    clickCountElement.textContent = game.sugar;
    clickCountElement2.textContent = game.sugar;

    saveToServer(); 
});

// SAVE BUTTON
saveButton.addEventListener("click", () => {
    saveToServer(); 
});

// SAVE FUNCTION
function saveToServer() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/save_game.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log("Saved:", xhr.responseText);
            } else {
                console.error("Error saving game:", xhr.statusText);
            }
        }
    };

    xhr.send(
        "clicks=" + encodeURIComponent(game.sugar) +
        "&currency=" + encodeURIComponent(game.currency) +
        "&multiplier=" + encodeURIComponent(game.multiplier) +
        "&clickPower=" + encodeURIComponent(game.clickPower) +
        "&cps=" + encodeURIComponent(game.cps) +
        "&bonus=" + encodeURIComponent(game.bonus)
    );
}

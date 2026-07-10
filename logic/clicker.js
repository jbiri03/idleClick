import { game, upgrades, buyUpgrade } from "./game.js";

/* -------------------------------------------------------
   LOAD UPGRADE DATA (ONLY IF upgradeData EXISTS)
---------------------------------------------------------*/
const upgradeData = document.getElementById("upgradeData");

if (upgradeData) {
    game.multiplier = parseFloat(upgradeData.dataset.multiplier) || 1;
    game.clickPower = parseFloat(upgradeData.dataset.clickpower) || 1;
    game.cps = parseFloat(upgradeData.dataset.cps) || 0;
    game.bonus = parseFloat(upgradeData.dataset.bonus) || 0;
}

/* -------------------------------------------------------
   LOAD PRESTIGE MULTIPLIER (SAFE)
---------------------------------------------------------*/
const prestigeElement = document.getElementById("prestigeMultiplierStat");
game.prestigeMultiplier = prestigeElement
    ? parseFloat(prestigeElement.textContent) || 1
    : 1;

console.log("Loaded game stats:", game);

/* -------------------------------------------------------
   LOAD SAVED CAKES + CURRENCY (ONLY IF ELEMENTS EXIST)
---------------------------------------------------------*/
const clickCountElement = document.getElementById("clickCount1");
const clickCountElement2 = document.getElementById("clickCount2");
const cashElement = document.getElementById("cash");

if (clickCountElement) {
    game.sugar = parseInt(clickCountElement.textContent) || 0;
}

if (cashElement) {
    game.currency = parseFloat(cashElement.textContent) || 0;
}

/* -------------------------------------------------------
   CLICK BUTTON (ONLY IF CLICKER EXISTS)
---------------------------------------------------------*/
const clickerButton = document.getElementById("clicker");

if (clickerButton) {
    clickerButton.addEventListener("click", () => {

        // Apply prestige multiplier to clicking
        game.sugar += game.clickPower * game.multiplier * game.prestigeMultiplier;

        if (clickCountElement) clickCountElement.textContent = game.sugar.toFixed(1);
        if (clickCountElement2) clickCountElement2.textContent = game.sugar.toFixed(1);

        saveToServer();
    });
}

/* -------------------------------------------------------
   SAVE BUTTON (ONLY IF EXISTS)
---------------------------------------------------------*/
const saveButton = document.getElementById("saveButton");

if (saveButton) {
    saveButton.addEventListener("click", () => {
        saveToServer();
    });
}

/* -------------------------------------------------------
   SAVE FUNCTION
---------------------------------------------------------*/
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
        "&bonus=" + encodeURIComponent(game.bonus) +
        "&prestige_multiplier=" + encodeURIComponent(game.prestigeMultiplier)
    );
}

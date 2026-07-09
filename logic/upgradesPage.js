import { game, upgrades, buyUpgrade } from "./game.js";
//BUTTON VARIABLES
const upgradeSugar1 = document.getElementById("upgradeSugar1");
const upgradeClick1 = document.getElementById("upgradeClick1");


// Load saved cakes from PHP
game.sugar = parseInt(document.getElementById("cakeStat")).textContent || 0;
game.currency = parseInt(document.getElementById("money").textContent) || 0;

console.log("Upgrades page loaded.");
console.log("Game state:", game);
console.log("Available upgrades:", upgrades);

// Upgrade buttons
upgradeSugar1.addEventListener("click", () => {
    if(buyUpgrade(upgrades[0])){
        console.log("After buying Sugar upgrade:", game);
        upgradeSugar1.disabled = true;
        upgradeSugar1.textContent = "Purchased";
    }


});

// upgradeClick1.addEventListener("click", () => {
//     if(buyUpgrade(upgrades[1])){
//         console.log("After buying Click upgrade:", game);
//         upgradeClick1.disabled = true;
//         upgradeClick1.textContent = "Purchased";
//     }
// });

import { game, upgrades, buyUpgrade } from "./game.js";

// BUTTON VARIABLES
//CLICK UPGRADES
const upgradeClick1 = document.getElementById("upgradeClick1");
const upgradeClick2 = document.getElementById("upgradeClick2");
const upgradeClick3 = document.getElementById("upgradeClick3");

//MULTIPLIER UPGRADES
const sugarMult1 = document.getElementById("sugarMult1");
const sugarMult2 = document.getElementById("sugarMult2");
const sugarMult3 = document.getElementById("sugarMult3");

const autoBake1 = document.getElementById("autoBake1");
const autoBake2 = document.getElementById("autoBake2");
const autoBake3 = document.getElementById("autoBake3");

// READ PURCHASED STATUS FROM PHP (disabled attribute)
//CLICK UPGRADES
const Click1Purchased = upgradeClick1.hasAttribute("disabled");
const Click2Purchased = upgradeClick2.hasAttribute("disabled");
const Click3Purchased = upgradeClick3.hasAttribute("disabled");

//MULTIPLIER UGPRADES
const Sugar1Purchased = sugarMult1.hasAttribute("disabled");
const Sugar2Purchased = sugarMult2.hasAttribute("disabled");
const Sugar3Purchased = sugarMult3.hasAttribute("disabled");

//AUTO CLICKER UPGRADES
const Baker1Purchased = autoBake1.hasAttribute("disabled");
const Baker2Purchased = autoBake2.hasAttribute("disabled");
const Baker3Purchased = autoBake3.hasAttribute("disabled");


// LOAD FULL GAME STATE FROM PHP
game.sugar = parseInt(document.getElementById("cakeStat").textContent) || 0;
game.currency = parseInt(document.getElementById("money").textContent) || 0;

game.multiplier = parseInt(document.getElementById("multiplierStat").textContent) || 1;
game.clickPower = parseInt(document.getElementById("clickPowerStat").textContent) || 1;
game.cps = parseInt(document.getElementById("cpsStat").textContent) || 0;
game.bonus = parseInt(document.getElementById("bonusStat").textContent) || 0;


console.log("Upgrades page loaded.");
console.log("Game state:", game);
console.log("Available upgrades:", upgrades);

// UPGRADE BUTTON EVENTS
//CLICK UPGRADES
upgradeClick1.addEventListener("click", () => {
    if (buyUpgrade(upgrades[0])) {
        console.log("After buying Click upgrade:", game);
        upgradeClick1.disabled = true;
        upgradeClick1.textContent = "Purchased";

        updateStatsUI();
    }
});

upgradeClick2.addEventListener("click", () => {
    if (buyUpgrade(upgrades[1])) {
        console.log("After buying Click upgrade:", game);
        upgradeClick2.disabled = true;
        upgradeClick2.textContent = "Purchased";

        updateStatsUI();
    }
});

upgradeClick3.addEventListener("click", () => {
    if (buyUpgrade(upgrades[2])) {
        console.log("After buying Click upgrade:", game);
        upgradeClick3.disabled = true;
        upgradeClick3.textContent = "Purchased";

        updateStatsUI();
    }
});

//MULTIPLIERS
sugarMult1.addEventListener("click", () => {
    if (buyUpgrade(upgrades[3])) {
        console.log("After buying Click upgrade:", game);
        sugarMult1.disabled = true;
        sugarMult1.textContent = "Purchased";

        updateStatsUI();
    }
});

sugarMult2.addEventListener("click", () => {
    if (buyUpgrade(upgrades[4])) {
        console.log("After buying Click upgrade:", game);
        sugarMult2.disabled = true;
        sugarMult2.textContent = "Purchased";

        updateStatsUI();
    }
});

sugarMult3.addEventListener("click", () => {
    if (buyUpgrade(upgrades[5])) {
        console.log("After buying Click upgrade:", game);
        sugarMult3.disabled = true;
        sugarMult3.textContent = "Purchased";

        updateStatsUI();
    }
});

//AUTO CLICKERS
autoBake1.addEventListener("click", () => {
    if (buyUpgrade(upgrades[6])) {
        console.log("After buying Click upgrade:", game);
        autoBake1.disabled = true;
        autoBake1.textContent = "Purchased";

        updateStatsUI();
    }
});

autoBake2.addEventListener("click", () => {
    if (buyUpgrade(upgrades[7])) {
        console.log("After buying Click upgrade:", game);
        autoBake2.disabled = true;
        autoBake2.textContent = "Purchased";

        updateStatsUI();
    }
});

autoBake3.addEventListener("click", () => {
    if (buyUpgrade(upgrades[8])) {
        console.log("After buying Click upgrade:", game);
        autoBake3.disabled = true;
        autoBake3.textContent = "Purchased";

        updateStatsUI();
    }
});

//UPDATE UI
function updateStatsUI() {
    // Update hidden spans
    document.getElementById("clickPowerStat").textContent = game.clickPower;
    document.getElementById("multiplierStat").textContent = game.multiplier;

    // Update visible stats
    document.querySelector("#stats ul:nth-of-type(2) li:nth-child(1)").textContent =
        `Click Power: ${game.clickPower}`;

    document.querySelector("#stats ul:nth-of-type(2) li:nth-child(2)").textContent =
        `Multiplier Bonus: ${game.multiplier}`;

    document.querySelector("#stats ul:nth-of-type(2) li:nth-child(3)").textContent =
        `Total Cakes Per Click: ${game.clickPower * game.multiplier}`;
}

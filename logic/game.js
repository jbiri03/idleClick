import { SugarMultiplier, ClickBooster, AutoBaker } from "./Upgrades.js";

/* -------------------------------------------------------
   PAGE CHECK
---------------------------------------------------------*/
const isClickerPage = !!document.getElementById("clicker");

if (!isClickerPage) {
    console.log("Not on clicker page — skipping game initialization.");
}

/* -------------------------------------------------------
   GAME STATE
---------------------------------------------------------*/
let sugarBuffer = 0;

export const game = {
    sugar: 0,
    clickPower: 1,
    multiplier: 1,
    cps: 0,
    currency: 0,
    bonus: 0,

    // ⭐ NEW — permanent prestige multiplier
    prestigeMultiplier: 1
};

/* -------------------------------------------------------
   UPGRADE ARRAY
---------------------------------------------------------*/
export const upgrades = [
    new ClickBooster("Stronger Clicks I", 1000, 4),
    new ClickBooster("Stronger Clicks II", 10000, 5),
    new ClickBooster("Stronger Clicks III", 100000, 10),

    new SugarMultiplier("More Sugar I", 3000, 2),
    new SugarMultiplier("More Sugar II", 30000, 2),
    new SugarMultiplier("More Sugar III", 300000, 2),

    new AutoBaker("Basic Auto Baker I", 5000, 1),
    new AutoBaker("Basic Auto Baker II", 50000, 9),
    new AutoBaker("Basic Auto Baker III", 500000, 25)
];

/* -------------------------------------------------------
   SAVE UPGRADE TO SERVER
---------------------------------------------------------*/
function saveUpgradeToServer(upgradeName) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/save_upgrade.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("upgrade_name=" + encodeURIComponent(upgradeName));
}

/* -------------------------------------------------------
   BUY UPGRADE
---------------------------------------------------------*/
export function buyUpgrade(upgrade) {
    console.log("Attempting to buy:", upgrade.name);

    if (game.currency >= upgrade.cost) {

        game.currency -= upgrade.cost;
        upgrade.applyUpgrade(game);
        upgrade.purchased = true;

        saveUpgradeToServer(upgrade.name);

        if (game.cps > 0) {
            const indicator = document.getElementById("autoBakeIndicator");
            if (indicator) indicator.style.display = "block";
        }

        const moneyElement = document.getElementById("money");
        if (moneyElement) {
            moneyElement.textContent = game.currency;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "php/save_game.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.send(
                "currency=" + encodeURIComponent(Math.floor(game.currency)) +
                "&clicks=" + encodeURIComponent(Math.floor(game.sugar)) +
                "&multiplier=" + encodeURIComponent(game.multiplier) +
                "&clickPower=" + encodeURIComponent(game.clickPower) +
                "&cps=" + encodeURIComponent(game.cps) +
                "&bonus=" + encodeURIComponent(game.bonus) +
                "&prestige_multiplier=" + encodeURIComponent(game.prestigeMultiplier)
            );

            return true;
        }
    }

    console.log("Not enough currency to buy:", upgrade.name);
    return false;
}

/* -------------------------------------------------------
   UI UPDATE
---------------------------------------------------------*/
function updateCakeUI() {
    const elements = document.querySelectorAll("[data-cake]");
    elements.forEach(el => {
        el.textContent = Math.floor(game.sugar);
    });

    const cakeStat = document.getElementById("cakeStat");
    if (cakeStat) cakeStat.textContent = Math.floor(game.sugar);
}

/* -------------------------------------------------------
   AUTO-CLICKER LOOP (ONLY ON CLICKER PAGE)
---------------------------------------------------------*/
if (isClickerPage) {
    setInterval(() => {

        const indicator = document.getElementById("autoBakeIndicator");

        if (game.cps > 0) {

            if (indicator) indicator.style.display = "block";

            // ⭐ APPLY PRESTIGE MULTIPLIER TO AUTO-BAKE
            sugarBuffer += (game.cps * game.prestigeMultiplier) / 10;

            if (sugarBuffer >= 1) {
                const whole = Math.floor(sugarBuffer);
                game.sugar += whole;
                sugarBuffer -= whole;
                updateCakeUI();
            }

        } else {
            if (indicator) indicator.style.display = "none";
        }

    }, 100);
}

/* -------------------------------------------------------
   SAVE TIMER (ONLY ON CLICKER PAGE)
---------------------------------------------------------*/
if (isClickerPage) {
    setInterval(() => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/save_game.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.send(
            "currency=" + encodeURIComponent(game.currency) +
            "&clicks=" + encodeURIComponent(Math.floor(game.sugar)) +
            "&multiplier=" + encodeURIComponent(game.multiplier) +
            "&clickPower=" + encodeURIComponent(game.clickPower) +
            "&cps=" + encodeURIComponent(game.cps) +
            "&bonus=" + encodeURIComponent(game.bonus) +
            "&prestige_multiplier=" + encodeURIComponent(game.prestigeMultiplier)
        );
    }, 1000);
}

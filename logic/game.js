import { SugarMultiplier, ClickBooster, AutoBaker } from "./Upgrades.js";

let sugarBuffer = 0;

export const game = {
    sugar: 0,
    clickPower: 1,
    multiplier: 1,
    cps: 0,
    currency: 0,
    bonus: 0
};

export const upgrades = [
    new ClickBooster("Stronger Clicks I", 1000, 4),
    new ClickBooster("Stronger Clicks II", 10000, 5),
    new ClickBooster("Stronger Clicks III", 100000, 10),

    new SugarMultiplier("More Sugar I", 3000, 2),
    new SugarMultiplier("More Sugar II", 30000, 2),
    new SugarMultiplier("More Sugar III", 300000, 2),

    new AutoBaker("Basic Auto Baker I", 5000, 1),
    new AutoBaker("Basic Auto Baker II", 50000, 10),
    new AutoBaker("Basic Auto Baker III", 500000, 25)
];

// SAVE UPGRADE
function saveUpgradeToServer(upgradeName) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/save_upgrade.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("upgrade_name=" + encodeURIComponent(upgradeName));
}

// BUY UPGRADE
export function buyUpgrade(upgrade) {
    console.log("Attempting to buy:", upgrade.name);
    console.log("Current currency:", game.currency, "Cost:", upgrade.cost);

    if (game.currency >= upgrade.cost) {
        console.log("Purchase successful.");
        game.currency -= upgrade.cost;
        upgrade.applyUpgrade(game);

        upgrade.purchased = true;

        saveUpgradeToServer(upgrade.name);

        const moneyElement = document.getElementById("money");
        if (moneyElement) {
            moneyElement.textContent = game.currency;

            // SAVE GAME STATE
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "php/save_game.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Upgrade saved:", xhr.responseText);
                    } else {
                        console.error("Error saving upgrade:", xhr.statusText);
                    }
                }
            };

            xhr.send(
                "currency=" + encodeURIComponent(Math.floor(game.currency)) +
                "&clicks=" + encodeURIComponent(Math.floor(game.sugar)) +
                "&multiplier=" + encodeURIComponent(game.multiplier) +
                "&clickPower=" + encodeURIComponent(game.clickPower) +
                "&cps=" + encodeURIComponent(game.cps) +
                "&bonus=" + encodeURIComponent(game.bonus)
            );

            return true;
        }

        console.log("Game state after upgrade:", game);
    } else {
        console.log("Not enough currency to buy:", upgrade.name);
        return false;
    }
}

// UNIVERSAL CAKE UI UPDATER (works on ALL pages)
function updateCakeUI() {
    const elements = document.querySelectorAll("[data-cake]");
    elements.forEach(el => {
        el.textContent = Math.floor(game.sugar);
    });

    // Legacy support for pages not updated yet
    const cakeStat = document.getElementById("cakeStat");
    if (cakeStat) cakeStat.textContent = Math.floor(game.sugar);
}

// AUTO-BAKER LOOP (works everywhere)
setInterval(() => {
    if (game.cps > 0) {
        sugarBuffer += game.cps / 10;

        if (sugarBuffer >= 1) {
            const whole = Math.floor(sugarBuffer);
            game.sugar += whole;
            sugarBuffer -= whole;
            updateCakeUI();
        }
    }
}, 100);


// SAVE TIMER (every second)
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
        "&bonus=" + encodeURIComponent(game.bonus)
    );
}, 1000);

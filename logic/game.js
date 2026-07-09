import {SugarMultiplier, ClickBooster, AutoBaker} from "./Upgrades.js";

export const game = {
    sugar: 0,
    clickPower: 1,
    multiplier: 1,
    cps: 0,
    currency: 0,
    bonus: 0
};

export const upgrades = [
    new SugarMultiplier("More Sugar", 3000, 2),
    new ClickBooster("Stronger Clicks", 100, 5),
    new AutoBaker("Basic Auto Baker", 200, 1)
];

export function buyUpgrade(upgrade) {
    console.log("Attempting to buy:", upgrade.name);
    console.log("Current currency:", game.currency, "Cost:", upgrade.cost);

    if (game.currency >= upgrade.cost) {
        console.log("Purchase successful.");
        game.currency -= upgrade.cost;
        upgrade.applyUpgrade(game);   // polymorphism

        const moneyElement = document.getElementById("money");

        //UPDATE UI
        if (moneyElement) {
            moneyElement.textContent = game.currency;

            // SAVE TO DATABASE
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

            xhr.send("currency=" + encodeURIComponent(game.currency) + 
                    "&clicks=" + encodeURIComponent(game.sugar) +
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


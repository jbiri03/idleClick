import { game, upgrades, buyUpgrade } from "./game.js";

if (window.__upgradesPageLoaded) {
    console.log("Upgrades page already initialized — skipping re‑init.");
} else {
    window.__upgradesPageLoaded = true;

    // BUTTON ELEMENTS
    const upgradeClick1 = document.getElementById("upgradeClick1");
    const upgradeClick2 = document.getElementById("upgradeClick2");
    const upgradeClick3 = document.getElementById("upgradeClick3");

    const sugarMult1 = document.getElementById("sugarMult1");
    const sugarMult2 = document.getElementById("sugarMult2");
    const sugarMult3 = document.getElementById("sugarMult3");

    const autoBake1 = document.getElementById("autoBake1");
    const autoBake2 = document.getElementById("autoBake2");
    const autoBake3 = document.getElementById("autoBake3");

    // LOAD GAME STATE FROM HIDDEN SPANS
    game.sugar = parseInt(document.getElementById("cakeStat")?.textContent) || 0;
    game.currency = parseInt(document.getElementById("money")?.textContent) || 0;

    game.multiplier = parseInt(document.getElementById("multiplierStat")?.textContent) || 1;
    game.clickPower = parseInt(document.getElementById("clickPowerStat")?.textContent) || 1;
    game.cps = parseInt(document.getElementById("cpsStat")?.textContent) || 0;
    game.bonus = parseInt(document.getElementById("bonusStat")?.textContent) || 0;

    game.prestigeMultiplier = parseFloat(document.getElementById("prestigeMultiplierStat")?.textContent) || 1;


    console.log("Upgrades page initialized.");
    console.log("Loaded game state:", game);

    // UPGRADE MAP
    const upgradeMap = [
        { btn: upgradeClick1, idx: 0 },
        { btn: upgradeClick2, idx: 1 },
        { btn: upgradeClick3, idx: 2 },

        { btn: sugarMult1, idx: 3 },
        { btn: sugarMult2, idx: 4 },
        { btn: sugarMult3, idx: 5 },

        { btn: autoBake1, idx: 6 },
        { btn: autoBake2, idx: 7 },
        { btn: autoBake3, idx: 8 }
    ];

    // COLLECT RESET AND UNLOCK BUTTONS
    function unlockAfterPrestige() {
        const prestigeReset =
            game.clickPower === 1 &&
            game.multiplier === 1 &&
            game.cps === 0;

        if (prestigeReset) {
            upgradeMap.forEach(({ btn }) => {
                if (!btn) return;
                btn.disabled = false;
                btn.textContent = "Buy Upgrade";
            });
        }
    }

    unlockAfterPrestige();

    // EVENT LISTENERS
    upgradeMap.forEach(({ btn, idx }) => {
        if (!btn) return;

        btn.addEventListener("click", () => {
            if (buyUpgrade(upgrades[idx])) {
                btn.disabled = true;
                btn.textContent = "Purchased";
                updateStatsUI();
            }
        });
    });

    // UI UPDATE
    function updateStatsUI() {
        document.getElementById("clickPowerStat").textContent = game.clickPower;
        document.getElementById("multiplierStat").textContent = game.multiplier;

        const productionList = document.querySelector("#stats ul:nth-of-type(2)");

        productionList.children[0].textContent = `Auto-Bake Rate: ${game.cps.toFixed(1)}`;
        productionList.children[1].textContent = `Click Power: ${game.clickPower}`;
        productionList.children[2].textContent = `Multiplier Bonus: ${game.multiplier}`;
        productionList.children[3].textContent = `Total Cakes Per Click: ${game.clickPower * game.multiplier}`;
    }

    // SEARCH BAR
    const searchInput = document.getElementById("upgradeSearch");

    if (searchInput) {
        searchInput.addEventListener("input", () => {
            const query = searchInput.value.toLowerCase().trim();

            upgradeMap.forEach(({ btn }) => {
                if (!btn) return;

                const text = btn.textContent.toLowerCase();
                const row = btn.closest("tr");

                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }
}

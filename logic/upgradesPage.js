import { game, upgrades, buyUpgrade } from "./game.js";

if (!window.__upgradesPageLoaded) {
    window.__upgradesPageLoaded = true;

    const byId = (id) => document.getElementById(id);

    const elements = {
        cakeStat: byId("cakeStat"),
        money: byId("money"),

        multiplierStat: byId("multiplierStat"),
        clickPowerStat: byId("clickPowerStat"),
        cpsStat: byId("cpsStat"),
        bonusStat: byId("bonusStat"),
        prestigeMultiplierStat: byId("prestigeMultiplierStat"),
        prestigePointsStat: byId("prestigePointsStat"),

        autoBakeRateText: byId("autoBakeRateText"),
        clickPowerText: byId("clickPowerText"),
        multiplierText: byId("multiplierText"),
        totalClickText: byId("totalClickText"),
        prestigeMultiplierText: byId("prestigeMultiplierText"),
        prestigePointsText: byId("prestigePointsText"),

        upgradeSearch: byId("upgradeSearch"),

        upgradeClick1: byId("upgradeClick1"),
        upgradeClick2: byId("upgradeClick2"),
        upgradeClick3: byId("upgradeClick3"),
        sugarMult1: byId("sugarMult1"),
        sugarMult2: byId("sugarMult2"),
        sugarMult3: byId("sugarMult3"),
        autoBake1: byId("autoBake1"),
        autoBake2: byId("autoBake2"),
        autoBake3: byId("autoBake3")
    };

    // LOAD STATE FROM PAGE
    game.sugar = parseFloat(elements.cakeStat?.textContent) || 0;
    game.currency = parseFloat(elements.money?.textContent) || 0;
    game.multiplier = parseFloat(elements.multiplierStat?.textContent) || 1;
    game.clickPower = parseFloat(elements.clickPowerStat?.textContent) || 1;
    game.cps = parseFloat(elements.cpsStat?.textContent) || 0;
    game.bonus = parseFloat(elements.bonusStat?.textContent) || 0;
    game.prestigeMultiplier = parseFloat(elements.prestigeMultiplierStat?.textContent) || 1;
    game.prestigePoints = parseFloat(elements.prestigePointsStat?.textContent) || 0;

    console.log("Upgrades page initialized.");
    console.log("Loaded game state:", game);

    const upgradeMap = [
        { btn: elements.upgradeClick1, idx: 0, name: "Stronger Clicks I" },
        { btn: elements.upgradeClick2, idx: 1, name: "Stronger Clicks II" },
        { btn: elements.upgradeClick3, idx: 2, name: "Stronger Clicks III" },

        { btn: elements.sugarMult1, idx: 3, name: "More Sugar I" },
        { btn: elements.sugarMult2, idx: 4, name: "More Sugar II" },
        { btn: elements.sugarMult3, idx: 5, name: "More Sugar III" },

        { btn: elements.autoBake1, idx: 6, name: "Basic Auto Baker I" },
        { btn: elements.autoBake2, idx: 7, name: "Basic Auto Baker II" },
        { btn: elements.autoBake3, idx: 8, name: "Basic Auto Baker III" }
    ];

    function updateStatsUI() {
        if (elements.money) {
            elements.money.textContent = Math.floor(game.currency);
        }

        if (elements.cakeStat) {
            elements.cakeStat.textContent = Math.floor(game.sugar);
        }

        if (elements.clickPowerStat) {
            elements.clickPowerStat.textContent = game.clickPower;
        }

        if (elements.multiplierStat) {
            elements.multiplierStat.textContent = game.multiplier;
        }

        if (elements.cpsStat) {
            elements.cpsStat.textContent = game.cps;
        }

        if (elements.bonusStat) {
            elements.bonusStat.textContent = game.bonus;
        }

        if (elements.prestigeMultiplierStat) {
            elements.prestigeMultiplierStat.textContent = game.prestigeMultiplier;
        }

        if (elements.prestigePointsStat) {
            elements.prestigePointsStat.textContent = game.prestigePoints;
        }

        if (elements.autoBakeRateText) {
            elements.autoBakeRateText.textContent = game.cps;
        }

        if (elements.clickPowerText) {
            elements.clickPowerText.textContent = game.clickPower;
        }

        if (elements.multiplierText) {
            elements.multiplierText.textContent = game.multiplier;
        }

        if (elements.totalClickText) {
            elements.totalClickText.textContent = game.clickPower * game.multiplier * game.prestigeMultiplier;
        }

        if (elements.prestigeMultiplierText) {
            elements.prestigeMultiplierText.textContent = game.prestigeMultiplier;
        }

        if (elements.prestigePointsText) {
            elements.prestigePointsText.textContent = game.prestigePoints;
        }
    }

    upgradeMap.forEach(({ btn, idx }) => {
        if (!btn) return;

        btn.addEventListener("click", () => {
            if (btn.disabled) return;

            const didBuy = buyUpgrade(upgrades[idx]);

            if (!didBuy) return;

            btn.disabled = true;
            btn.textContent = "Purchased";
            updateStatsUI();
        });
    });

    if (elements.upgradeSearch) {
        elements.upgradeSearch.addEventListener("input", () => {
            const query = elements.upgradeSearch.value.toLowerCase().trim();

            upgradeMap.forEach(({ btn, name }) => {
                if (!btn) return;

                const row = btn.closest("tr");
                if (!row) return;

                const matches = name.toLowerCase().includes(query);
                row.style.display = matches ? "" : "none";
            });
        });
    }

    updateStatsUI();
}
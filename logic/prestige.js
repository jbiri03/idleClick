document.addEventListener("DOMContentLoaded", () => {

    const prestigeButton = document.getElementById("prestigeButton");
    const totalCakesEl = document.getElementById("totalCakesBaked");
    const prestigePointsEl = document.getElementById("prestigePoints");
    const newPrestigeMultiplierEl = document.getElementById("newPrestigeMultiplier");

    //LOAD VALUES
    const currentCakes = parseInt(document.getElementById("cakeStat").textContent) || 0;
    const currentPrestigePoints = parseInt(document.getElementById("prestigePointsStat")?.textContent) || 0;
    const currentPrestigeMultiplier = parseFloat(document.getElementById("prestigeMultiplierStat")?.textContent) || 1;

    //DISPLAY
    totalCakesEl.textContent = currentCakes;

   //1000 CAKES PER POINT
    const earnedPrestige = Math.floor(currentCakes / 1000);

    // Total prestige points after prestige
    const newPrestigePoints = currentPrestigePoints + earnedPrestige;

    // New prestige multiplier
    const newPrestigeMultiplier = 1 + (newPrestigePoints * 0.1);

    // Update UI
    prestigePointsEl.textContent = newPrestigePoints;
    newPrestigeMultiplierEl.textContent = "x" + newPrestigeMultiplier.toFixed(1);

    //DISABLE BUTTON
    if (currentCakes < 1000) {
        prestigeButton.disabled = true;
        prestigeButton.textContent = "Need 1000 Cakes to Prestige";
        prestigeButton.style.backgroundColor = "#777";
        prestigeButton.style.cursor = "not-allowed";
    }

    // Handle prestige button click
    prestigeButton.addEventListener("click", () => {

        // Prevent clicking if disabled
        if (prestigeButton.disabled) return;

        prestigeButton.disabled = true;
        prestigeButton.textContent = "Prestiging...";
        prestigeButton.style.backgroundColor = "#3498db";
        prestigeButton.style.color = "#fff";

        fetch("php/prestige_reset.php")
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    prestigeButton.textContent = "Prestige Complete!";
                    prestigeButton.style.backgroundColor = "#4CAF50";

                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 900);

                } else {
                    prestigeButton.textContent = "Error — Try Again";
                    prestigeButton.style.backgroundColor = "#c0392b";
                }
            })
            .catch(() => {
                prestigeButton.textContent = "Prestige Failed";
                prestigeButton.style.backgroundColor = "#c0392b";
            });
    });

});

document.addEventListener("DOMContentLoaded", () => {
    const resultsBody = document.getElementById("leaderboardResults");
    const topCurrencyBtn = document.getElementById("topCurrencyBtn");
    const topCakesBtn = document.getElementById("topCakesBtn");
    const topPrestigeBtn = document.getElementById("topPrestigeBtn");

    async function loadLeaderboard(type) {
        resultsBody.innerHTML = `<tr><td colspan="4">Loading...</td></tr>`;

        try {
            const response = await fetch(`php/leaderboard_query.php?type=${encodeURIComponent(type)}`);

            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status}`);
            }

            const data = await response.json();
            console.log("Leaderboard response:", data);

            resultsBody.innerHTML = "";

            if (data.error) {
                resultsBody.innerHTML = `<tr><td colspan="4">${data.error}</td></tr>`;
                return;
            }

            if (!Array.isArray(data)) {
                resultsBody.innerHTML = `<tr><td colspan="4">Invalid leaderboard data format.</td></tr>`;
                return;
            }

            if (data.length === 0) {
                resultsBody.innerHTML = `<tr><td colspan="4">No leaderboard data found.</td></tr>`;
                return;
            }

            data.forEach((row) => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${row.username ?? "Unknown"}</td>
                    <td>${row.cakes ?? 0}</td>
                    <td>${row.currency ?? 0}</td>
                    <td>${row.prestige_level ?? 0}</td>
                `;

                resultsBody.appendChild(tr);
            });
        } catch (err) {
            resultsBody.innerHTML = `<tr><td colspan="4">Error loading leaderboard.</td></tr>`;
            console.error("Leaderboard load failed:", err);
        }
    }

    topCurrencyBtn?.addEventListener("click", () => loadLeaderboard("currency"));
    topCakesBtn?.addEventListener("click", () => loadLeaderboard("cakes"));
    topPrestigeBtn?.addEventListener("click", () => loadLeaderboard("prestige"));

    loadLeaderboard("currency");
});
//LEADERBOARD LOGIC
document.addEventListener("DOMContentLoaded", () => {
    const resultsBody = document.getElementById("leaderboardResults");
    const headerRow = document.getElementById("leaderboardHeaderRow");

    const topCurrencyBtn = document.getElementById("topCurrencyBtn");
    const topCakesBtn = document.getElementById("topCakesBtn");
    const topPrestigeBtn = document.getElementById("topPrestigeBtn");

    function setTableHeader(type) {
        if (type === "currency") {
            headerRow.innerHTML = `
                <th>Username</th>
                <th>Currency</th>
            `;
        } else if (type === "cakes") {
            headerRow.innerHTML = `
                <th>Username</th>
                <th>Cakes</th>
            `;
        } else if (type === "prestige") {
            headerRow.innerHTML = `
                <th>Username</th>
                <th>Prestige Level</th>
            `;
        }
    }

    function buildRow(row, type) {
        if (type === "currency") {
            return `
                <td>${row.username ?? "Unknown"}</td>
                <td>${row.currency ?? 0}</td>
            `;
        }

        if (type === "cakes") {
            return `
                <td>${row.username ?? "Unknown"}</td>
                <td>${row.cakes ?? 0}</td>
            `;
        }

        if (type === "prestige") {
            return `
                <td>${row.username ?? "Unknown"}</td>
                <td>${row.prestige_level ?? 0}</td>
            `;
        }

        return "";
    }

    async function loadLeaderboard(type) {
        setTableHeader(type);
        resultsBody.innerHTML = `<tr><td colspan="2">Loading...</td></tr>`;

        try {
            const response = await fetch(`php/leaderboard_query.php?type=${encodeURIComponent(type)}`);

            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status}`);
            }

            const data = await response.json();
            console.log("Leaderboard response:", data);

            resultsBody.innerHTML = "";

            if (data.error) {
                resultsBody.innerHTML = `<tr><td colspan="2">${data.error}</td></tr>`;
                return;
            }

            if (!Array.isArray(data)) {
                resultsBody.innerHTML = `<tr><td colspan="2">Invalid leaderboard data format.</td></tr>`;
                return;
            }

            if (data.length === 0) {
                resultsBody.innerHTML = `<tr><td colspan="2">No leaderboard data found.</td></tr>`;
                return;
            }

            data.forEach((row) => {
                const tr = document.createElement("tr");
                tr.innerHTML = buildRow(row, type);
                resultsBody.appendChild(tr);
            });
        } catch (err) {
            resultsBody.innerHTML = `<tr><td colspan="2">Error loading leaderboard.</td></tr>`;
            console.error("Leaderboard load failed:", err);
        }
    }

    topCurrencyBtn?.addEventListener("click", () => loadLeaderboard("currency"));
    topCakesBtn?.addEventListener("click", () => loadLeaderboard("cakes"));
    topPrestigeBtn?.addEventListener("click", () => loadLeaderboard("prestige"));

    loadLeaderboard("currency");
});
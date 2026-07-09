const sellButton = document.getElementById("sell-button");

//ELEMENTS
let currency = parseFloat(document.getElementById("currency").textContent);
let cakeCount = parseFloat(document.getElementById("cakeCount").textContent);

//LOAD PRICE FROM SESSION
let cakePrice = sessionStorage.getItem("cakePrice");
let nextCakePriceUpdate = parseInt(sessionStorage.getItem("nextCakePriceUpdate"), 10);
const CAKE_PRICE_INTERVAL = 30000;

//INITIALIZE PRICE
if (cakePrice === null || isNaN(nextCakePriceUpdate)) {
    cakePrice = Math.floor(Math.random() * (100 - 10 + 1)) + 10;
    nextCakePriceUpdate = Date.now() + CAKE_PRICE_INTERVAL;

    sessionStorage.setItem("cakePrice", cakePrice);
    sessionStorage.setItem("nextCakePriceUpdate", nextCakePriceUpdate);
} else {
    cakePrice = parseInt(cakePrice, 10);

    if (Date.now() >= nextCakePriceUpdate) {
        cakePrice = Math.floor(Math.random() * (100 - 10 + 1)) + 10;
        nextCakePriceUpdate = Date.now() + CAKE_PRICE_INTERVAL;

        sessionStorage.setItem("cakePrice", cakePrice);
        sessionStorage.setItem("nextCakePriceUpdate", nextCakePriceUpdate);
    }
}

document.getElementById("cakePrice").textContent = cakePrice;

function updateCakePrice() {
    if (Date.now() >= nextCakePriceUpdate) {
        cakePrice = Math.floor(Math.random() * (100 - 10 + 1)) + 10;
        nextCakePriceUpdate = Date.now() + CAKE_PRICE_INTERVAL;

        sessionStorage.setItem("cakePrice", cakePrice);
        sessionStorage.setItem("nextCakePriceUpdate", nextCakePriceUpdate);

        document.getElementById("cakePrice").textContent = cakePrice;
    }
}

sellButton.addEventListener("click", () => {

    // ADD MONEY
    currency += cakeCount * cakePrice;

    // RESET CAKES
    const newCakeCount = 0;

    // UPDATE UI
    document.getElementById("currency").textContent = currency;
    document.getElementById("cakeCount").textContent = newCakeCount;
    document.getElementById("cakeStat").textContent = newCakeCount;


    cakeCount = newCakeCount;

    // SEND TO SERVER
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/sell.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.send(
        "currency=" + encodeURIComponent(currency) +
        "&cakeCount=" + encodeURIComponent(newCakeCount)
    );
});

setInterval(updateCakePrice, 1000);

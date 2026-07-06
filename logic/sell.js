const sellButton = document.getElementById("sell-button");
let currency = parseFloat(document.getElementById("currency").textContent);
let cakeCount = parseFloat(document.getElementById("cakeCount").textContent);

sellButton.addEventListener("click", () => {
    currency += cakeCount; // Update currency with the current cake count

    const newCakeCount = 0; // Reset cake count to 0 after selling
  
    document.getElementById("currency").textContent = currency;
    document.getElementById("cakeCount").textContent = newCakeCount; // Reset cake count to 0 after selling
    document.getElementById("cakeStat").textContent = newCakeCount; // Reset cake stat to 0 after selling

    var dataToSend = encodeURIComponent(currency);
    var clicks = encodeURIComponent(newCakeCount); // Get the current cake count
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "php/sell.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error("Error saving game:", xhr.statusText);
            }
        }
    };
    xhr.send("currency=" + dataToSend + "&cakeCount=" + clicks);
    
});

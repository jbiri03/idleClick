
// CLICKER VARIABLES
let count;
const btnClick = document.getElementById("btnClick");

//CAKE STAT DISPLAY VARIABLES
const display = document.getElementById("cakeCount");
const cakeStat = document.getElementById("cakeStat");

//CODE CLEANUP
let clickCount = localStorage.getItem("count");

//SAVED COUNT ON RELOAD/RETRIEVES COUNT
if (clickCount != null){
    count = clickCount;
    display.textContent = count;
    cakeStat.textContent = count;
}
//STARTS AT 0 
else{
    count = 0;
}

//COUNTER
btnClick.addEventListener("click", ()=> {
count++;
display.textContent = count;
cakeStat.textContent = count;

//SAVE COUNT
let savedCount = count;
localStorage.setItem("count", savedCount);
})

//MONEY VARIABLES
let cash = localStorage.getItem("money");
const income = document.getElementById("money");
const currency = document.getElementById("cash")

//DISPLAY MONEY
income.textContent = cash;
currency.textContent = cash;
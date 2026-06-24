//VARIABLES
let count = Number(localStorage.getItem("count"));
let cash;

//BUTTONS
const sellCakes = document.getElementById("sell")

//DISPLAYS
const income = document.getElementById("money")
const display = document.getElementById("cakeCount");
const cakeStat = document.getElementById("cakeStat")

//SAVED VALUE DISPLAY
display.textContent = count;
cakeStat.textContent = count;

//CODE CLEANUP
let savedMoney = Number(localStorage.getItem("money"));

//SET SAVED MONEY TO DISPLAY
if (savedMoney != null){
    cash = savedMoney;
    income.textContent = cash;
}
else{
    cash = 0;
}

//SELL BUTTON EVENT
sellCakes.addEventListener("click", ()=>{
    cash += count;
    count = 0;

    //SAVE NEW COUNT
    localStorage.setItem("count", count);

    //DISPLAY CURRENT VALUES
    display.textContent = count;
    cakeStat.textContent = count;
    income.textContent = cash;

    //SAVE MONEY
    localStorage.setItem("money", cash);
})

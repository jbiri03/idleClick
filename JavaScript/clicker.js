
let count = 0;
const btnClick = document.getElementById("btnClick");
const display = document.getElementById("cakeCount");
const cakeStat = document.getElementById("cakeStat")

btnClick.addEventListener("click", ()=> {
count++;
display.textContent = count;
cakeStat.textContent = count;
})
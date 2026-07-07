const buttons = [
    document.getElementById('saveButton'), 
    document.getElementById('sellButton'),
    document.getElementById('upgradesButton'),
    document.getElementById('petsButton'),
    document.getElementById('prestigeButton'),
    document.getElementById('inventoryButton'),
    document.getElementById('dailyButton'),
    document.getElementById('clicker'),
];

const loginButton = document.getElementById('loginButton');

fetch('php/check_session.php')
    .then(res => res.json())
    .then(data => {
        if(data.active){
            loginButton.textContent = "Switch User"
        }
        buttons.forEach(btn =>{
            if (btn) {
                btn.disabled =!data.active; //Disable multiple buttons at once

            } 
        })
    });


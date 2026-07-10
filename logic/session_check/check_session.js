const buttons = [
    document.getElementById('saveButton'), 
    document.getElementById('sellButton'),
    document.getElementById('upgradesButton'),
    document.getElementById('prestigeButton'),
    document.getElementById('clicker'),
    document.getElementById('homeButton'),
    document.getElementById('settingsButton'),
    document.getElementById('logOut')
];

const loginButton = document.getElementById('loginButton');
const logOutButton = document.getElementById('logOut');

fetch('php/check_session.php')
    .then(res => res.json())
    .then(data => {

        if(data.active){
            //CHANGE TEXT TO SWITCH USER
            loginButton.textContent = "Switch User";
            
            //SHOW LOG OUT BUTTON IF LOGGED IN
            logOutButton.style.display = "inline-block";
        }

        //DISABLE BUTTONS
        buttons.forEach(btn =>{
            if (btn) {
                btn.disabled =!data.active; //Disable multiple buttons at once

            } 
        })
    });


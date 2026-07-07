let upgradeLevel = 0; // Initialize the upgrade multiplier

const upgrade1 = document.getElementById('upgrade1');
const upgrade2 = document.getElementById('upgrade2');
const upgrade3 = document.getElementById('upgrade3');

upgrade1.addEventListener('click', () => {
    // Implement the logic for Upgrade 1 here
    console.log('Upgrade 1 clicked');
    upgradeLevel += 1; // Increase the upgrade level when Upgrade 1 is clicked
    document.getElementById('upgrade1Level').textContent = upgradeLevel;
});

upgrade2.addEventListener('click', () => {
    // Implement the logic for Upgrade 2 here
    console.log('Upgrade 2 clicked');
    upgradeLevel += 1; // Increase the upgrade level when Upgrade 2 is clicked
    document.getElementById('upgrade2Level').textContent = upgradeLevel;
}); 
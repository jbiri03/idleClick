 //MAIN UPGRADES CLASS
 class Upgrades {
    constructor(name, cost) {
        this.name = name;
        this.cost = cost;
    }
    applyUpgrade(game){
        console.log(`${this.name} applied`);
    }
}

//INHERITED UPGRADE CLASSES
class SugarMultiplier extends Upgrades{
    constructor(name, cost, multiplier){
        super(name, cost);
        this.multiplier = multiplier;
    }
    applyUpgrade(game){
        game.multiplier *= this.multiplier;
    }
}

class ClickBooster extends Upgrades{
    constructor(name, cost, bonus){
        super(name, cost);
        this.bonus = bonus;
    }

    applyUpgrade(game){
        game.clickPower += this.bonus;
    }
}

class AutoBaker extends Upgrades{
    constructor(name, cost, cps){
        super(name, cost);
        this.cps = cps;
    }
    applyUpgrade(game){
        game.cps += this.cps;
    }
}

export { Upgrades, SugarMultiplier, ClickBooster, AutoBaker };

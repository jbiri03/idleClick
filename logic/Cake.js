//CAKE CLASS
class Cake {
	constructor (adjective, color, flavor, frosting){
        this.adjective = adjective;
		this.color = color;
		this.flavor = flavor;
		this.frosting = frosting;
    }

    displayInfo(){
	return `${this.adjective} ${this.color} ${this.flavor} Cake with ${this.frosting} Frosting`;
    }
}

const cakeDetails = document.getElementById("cakeDetails")
const myCake = new Cake('Bold', 'Blue', 'Raspberry', 'Swiss Meringue');

cakeDetails.textContent = myCake.displayInfo();


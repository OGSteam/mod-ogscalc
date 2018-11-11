function diversCosts( project ) {

	let destructible = destructiblesCosts( project );

	return {
		costProjectDiversM: destructible.metal,
		costProjectDiversC: destructible.crystal,
		costProjectDiversD: destructible.deut,
		costProjectDiversT: destructible.time
	}
}

function weaponsCosts( project ) {

	let destructible = destructiblesCosts( project );

	return {
		costProjectWeaponM: destructible.metal,
		costProjectWeaponC: destructible.crystal,
		costProjectWeaponD: destructible.deut,
		costProjectWeaponT: destructible.time
	}
}

function destructiblesCosts( project ) {
	let metal = 0;
	let crystal = 0;
	let deut = 0;
	let time = 0;

	let current = document.getElementById( project.id );
	
	let currentValue = parseInt( current.value, 10 );
	let projectValue = parseInt( project.value, 10 );
	
	if( projectValue > currentValue ) {
		let toBuild = projectValue - currentValue;
		let destructible = costDestructibles( project.classList[3], toBuild )
		metal = destructible.metal;
		crystal = destructible.crystal;
		deut = destructible.deut;
		time = timeConstDestructibles( project.classList[3], toBuild, project.id );
	}

	return {
		metal: metal,
		crystal: crystal,
		deut: deut,
		time: time
	}
}
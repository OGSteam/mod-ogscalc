function technosCosts( project ) {
	let costProjectTechnoM = 0;
	let costProjectTechnoC = 0;
	let costProjectTechnoD = 0;
	let costProjectTechnoE = 0;
	let costProjectTechnoT = 0;

	let current = document.getElementById( project.id );

	let currentValue = parseInt( current.value, 10 );
	let projectValue = parseInt( project.value, 10 );
	
	if( projectValue > currentValue ) {

		let tech = costTechnos( project.id, currentValue, projectValue );
		costProjectTechnoM = precisionRound( tech.metal, -2 );
		costProjectTechnoC = precisionRound( tech.crystal, -2 );
		costProjectTechnoD = precisionRound( tech.deut, -2 );
		costProjectTechnoE = tech.energy;
		costProjectTechnoT = timeConstTechnos( costProjectTechnoM, costProjectTechnoC );
	}		


	return {
		costProjectTechnoM: costProjectTechnoM,
		costProjectTechnoC: costProjectTechnoC,
		costProjectTechnoD: costProjectTechnoD,
		costProjectTechnoE: costProjectTechnoE,
		costProjectTechnoT: costProjectTechnoT
	}
}
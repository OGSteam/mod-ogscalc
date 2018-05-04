function technosCosts( project ) {
	let costProjectTechnoM = 0;
	let costProjectTechnoC = 0;
	let costProjectTechnoD = 0;
	let costProjectTechnoE = 0;
	let costProjectTechnoT = 0;

	let current = document.getElementById( project.id );

	if( project.value > current.value ) {

		let tech = costTechnos( project.id, current.value, project.value );
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
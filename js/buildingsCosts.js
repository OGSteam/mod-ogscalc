function buildingsCosts( project ) {
	let costProjectBuildingM = 0;
	let costProjectBuildingC = 0;
	let costProjectBuildingD = 0;
	let costProjectBuildingE = 0;
	let costProjectBuildingMD = 0;
	let costProjectBuildingCD = 0;
	let costProjectBuildingDD = 0;
	let totalResourceDest = 0;
	let costProjectBuildingT = 0;


	let building = buildingCost( project );
	costProjectBuildingM = building.costProjectBuildingM;
	costProjectBuildingC = building.costProjectBuildingC;
	costProjectBuildingD = building.costProjectBuildingD;
	costProjectBuildingE = building.costProjectBuildingE;
	costProjectBuildingT = building.costProjectBuildingT;
	costProjectBuildingMD = building.costProjectBuildingMD;
	costProjectBuildingCD = building.costProjectBuildingCD;
	costProjectBuildingDD = building.costProjectBuildingDD;
	totalResourceDest = building.totalResourceDest;		

	return {
		costProjectBuildingM: costProjectBuildingM,
		costProjectBuildingC: costProjectBuildingC,
		costProjectBuildingD: costProjectBuildingD,
		costProjectBuildingE: costProjectBuildingE,
		costProjectBuildingMD: costProjectBuildingMD,
		costProjectBuildingCD: costProjectBuildingCD,
		costProjectBuildingDD: costProjectBuildingDD,
		costProjectBuildingT: costProjectBuildingT,
		totalResourceDest: totalResourceDest
	}

}


function buildingCost( project ) {
	let costProjectBuildingM = 0;
	let costProjectBuildingC = 0;
	let costProjectBuildingD = 0;
	let costProjectBuildingE = 0;
	let costProjectBuildingMD = 0;
	let costProjectBuildingCD = 0;
	let costProjectBuildingDD = 0;
	let totalResourceDest = 0;
	let costProjectBuildingT = 0;

	let current = document.getElementById( project.id );
	let currentValue = parseInt( current.value, 10 );
	let projectValue = parseInt( project.value, 10 );
	let position = getPosition( current );
    if( currentValue <= projectValue ) {
    	let buildProject = {};
    	let buildCurrent = {};
    	if( project.classList.contains( "M" ) ) {
    		buildProject = metalMine( projectValue );
    		buildCurrent = metalMine( currentValue );
    	} else if( project.classList.contains( "C" ) ) {
    		buildProject = crystalMine( projectValue );
    		buildCurrent = crystalMine( currentValue );
    	} else if( project.classList.contains( "D" ) ) {
    		buildProject = deutMine( projectValue );
    		buildCurrent = deutMine( currentValue );
    	} else if( project.classList.contains( "CES" ) ) {
    		buildProject = solar( projectValue );
    		buildCurrent = solar( currentValue );
    	} else if( project.classList.contains( "CEF" ) ) {
    		buildProject = fusion( projectValue );
    		buildCurrent = fusion( currentValue );
    	} else {
    		buildProject = building( project.classList[3], projectValue );
    		buildCurrent = building( current.classList[3], currentValue );
    	}
    	//need temp val to calc time
        let tmpProjectM = Math.floor( buildProject.metal );
        let tmpProjectC = Math.floor( buildProject.crystal );
        let tmpProjectD = Math.floor( buildProject.deut );
        let tmpProjectE = Math.floor( buildProject.energy );
        let tmpCurrentM = Math.floor( buildCurrent.metal );
        let tmpCurrentC = Math.floor( buildCurrent.crystal );
        let tmpCurrentD = Math.floor( buildCurrent.deut );
        let tmpCurrentE = Math.floor( buildCurrent.energy );
        costProjectBuildingM += tmpProjectM;
        costProjectBuildingC += tmpProjectC;
        costProjectBuildingD += tmpProjectD;
        costProjectBuildingE += tmpProjectE;
        costProjectBuildingM -= tmpCurrentM;
        costProjectBuildingC -= tmpCurrentC;
        costProjectBuildingD -= tmpCurrentD;
        costProjectBuildingE -= tmpCurrentE;
        if( tmpProjectM !== tmpCurrentM || tmpProjectC !== tmpCurrentC ) {
        	costProjectBuildingT += timeConstBuildings( tmpProjectM-tmpCurrentM, tmpProjectC-tmpCurrentC, position );
    	}
	} else {
		let diffLvl = currentValue - projectValue;	

    	let buildingCurrent = {};
    	let buildingDiff = {};	
    	let buildingDestPrice = {};	

    	if( project.classList.contains( "M" ) ) {
	    	buildingCurrent = metalMine( currentValue );
	    	buildingDiff = metalMine( currentValue - diffLvl );
	    	buildingDestPrice = metalMineDest( currentValue, projectValue );
    	} else if( project.classList.contains( "C" ) ) {
	    	buildingCurrent = crystalMine( current.value );
	    	buildingDiff = crystalMine( current.value - diffLvl );
	    	buildingDestPrice = crystalMineDest( currentValue, projectValue );
    	} else if( project.classList.contains( "D" ) ) {
	    	buildingCurrent = deutlMine( currentValue );
	    	buildingDiff = deutMine( currentValue - diffLvl );
	    	buildingDestPrice = deutMineDest( current.value, project.value );
    	} else if( project.classList.contains( "CES" ) ) {
	    	buildingCurrent = solar( currentValue );
	    	buildingDiff = solar( currentValue - diffLvl );
	    	buildingDestPrice = solarDest( currentValue, projectValue );
    	} else if( project.classList.contains( "CEF" ) ) {
	    	buildingCurrent = fusion( currentValue );
	    	buildingDiff = fusion( currentValue - diffLvl );
	    	buildingDestPrice = fusionDest( currentValue, projectValue );
	    } else {
	    	buildingCurrent = building( current.classList[3], currentValue );
	    	buildingDiff = building( current.classList[3], currentValue - diffLvl );
	    	buildingDestPrice = buildingDest( project.classList[3], currentValue, projectValue );
	    }
    	//need temp val to calc time
    	let tmpProjectM = 0;
    	let tmpProjectC = 0;
    	let currentLvl = parseInt( currentValue, 10 );
    	while( currentLvl > projectValue ) {
    		let TEMPLow = {};
    		let TEMPSup = {};
			if( project.classList.contains( "M" ) ) {
		    	TEMPLow = metalMine( currentLvl - 2 );
		    	TEMPSup = metalMine( currentLvl - 1 );
	    	} else if( project.classList.contains( "C" ) ) {
		    	TEMPLow = crystalMine( currentLvl - 2 );
		    	TEMPSup = crystalMine( currentLvl - 1 );
	    	} else if( project.classList.contains( "D" ) ) {
		    	TEMPLow = deutlMine( currentLvl - 2 );
		    	TEMPSup = deutMine( currentLvl - 1 );
	    	} else if( project.classList.contains( "CES" ) ) {
		    	TEMPLow = solar( currentLvl - 2 );
		    	TEMPSup = solar( currentLvl - 1 );
	    	} else if( project.classList.contains( "CEF" ) ) {
		    	TEMPLow = fusion( currentLvl - 2 );
		    	TEMPSup = fusion( currentLvl - 1 );
		    } else {
		    	TEMPLow = building( current.classList[3], currentLvl - 2 );
		    	TEMPSup = building( current.classList[3], currentLvl - 1 );
		    }
    		costProjectBuildingT += timeConstBuildings( TEMPSup.metal - TEMPLow.metal, TEMPSup.crystal - TEMPLow.crystal, position );		   
    		currentLvl--;
    	}
		costProjectBuildingM += Math.floor( buildingDestPrice.metal );
		costProjectBuildingC += Math.floor( buildingDestPrice.crystal );
		costProjectBuildingD += Math.floor( buildingDestPrice.deut );
		totalResourceDest += costProjectBuildingM;
		totalResourceDest += costProjectBuildingC;
		totalResourceDest += costProjectBuildingD;
        costProjectBuildingMD -= buildingCurrent.metal;
        costProjectBuildingCD -= buildingCurrent.crystal;
        costProjectBuildingDD -= buildingCurrent.deut;		            
        costProjectBuildingMD += buildingDiff.metal;
        costProjectBuildingCD += buildingDiff.crystal;
        costProjectBuildingDD += buildingDiff.deut;
	}

	return {
		costProjectBuildingM: costProjectBuildingM,
		costProjectBuildingC: costProjectBuildingC,
		costProjectBuildingD: costProjectBuildingD,
		costProjectBuildingE: costProjectBuildingE,
		costProjectBuildingMD: costProjectBuildingMD,
		costProjectBuildingCD: costProjectBuildingCD,
		costProjectBuildingDD: costProjectBuildingDD,
		costProjectBuildingT: costProjectBuildingT,
		totalResourceDest: totalResourceDest
	}
}

function getPosition( current ) {

	let position = "";

	if( current.classList.contains( "M" ) ) {
		position = current.id.replace('m','');
	} else if( current.classList.contains( "C" ) ) {
		position = current.id.replace('c','');
	} else if( current.classList.contains( "D" ) ) {
		position = current.id.replace('d','');
	} else if( current.classList.contains( "CES" ) ) {
		position = current.id.replace('ces','');
	} else if( current.classList.contains( "CEF" ) ) {
		position = current.id.replace('cef','');
	} else if( current.classList.contains( "UDR" ) ) {
		position = current.id.replace('udr','');
	} else if( current.classList.contains( "UDN" ) ) {
		position = current.id.replace('udn','');
	} else if( current.classList.contains( "CSP" ) ) {
		position = current.id.replace('csp','');
	} else if( current.classList.contains( "HM" ) ) {
		position = current.id.replace('hm','');
	} else if( current.classList.contains( "HC" ) ) {
		position = current.id.replace('hc','');
	} else if( current.classList.contains( "HD" ) ) {
		position = current.id.replace('hd','');
	} else if( current.classList.contains( "LAB" ) ) {
		position = current.id.replace('lab','');
	} else if( current.classList.contains( "DDR" ) ) {
		position = current.id.replace('ddr','');
	} else if( current.classList.contains( "TER" ) ) {
		position = current.id.replace('ter','');
	} else if( current.classList.contains( "SILO" ) ) {
		position = current.id.replace('silo','');
	} else if( current.classList.contains( "BALU" ) ) {
		position = current.id.replace('balu','');
	} else if( current.classList.contains( "PHA" ) ) {
		position = current.id.replace('pha','');
	} else if( current.classList.contains( "POSA" ) ) {
		position = current.id.replace('posa','');
	}

	return position;
}
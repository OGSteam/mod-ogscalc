var nanitesUser = {};
var uDRUser = {};
var cSPUser = {};
var labUser = 0;
var capaPT = 5000;
var capaGT = 25000;

var metalRate = document.getElementById( "metal_rate" );
var crystalRate = document.getElementById( "crystal_rate" );
var deutRate = 1;

var metalAccount = document.getElementById( "metal_resource" );
var crystalAccount = document.getElementById( "crystal_resource" );
var deutAccount = document.getElementById( "deut_resource" );

//buildings
var sumMetalBuildings = document.getElementById( 'sum_metal_buildings' );
var sumCrystalBuildings = document.getElementById( 'sum_crystal_buildings' );
var sumDeutBuildings = document.getElementById( 'sum_deut_buildings' );
if( view === "planets" ) {
	var sumEnergyBuildings = document.getElementById( 'sum_energy_buildings' );
}
var sumPTBuildings = document.getElementById( 'sum_pt_buildings' );
var sumGTBuildings = document.getElementById( 'sum_gt_buildings' );
var sumPointsBuildings = document.getElementById( 'sum_points_buildings' );
var sumTimeBuildings = document.getElementById( 'sum_time_buildings' );
//divers
var sumMetalDivers = document.getElementById( 'sum_metal_divers' );
var sumCrystalDivers = document.getElementById( 'sum_crystal_divers' );
var sumDeutDivers = document.getElementById( 'sum_deut_divers' );
var sumPTDivers = document.getElementById( 'sum_pt_divers' );
var sumGTDivers = document.getElementById( 'sum_gt_divers' );
var sumPointsDivers = document.getElementById( 'sum_points_divers' );
var sumTimeDivers = document.getElementById( 'sum_time_divers' );
//research
if( view === "planets" ) {
	var sumMetalTechnos = document.getElementById( 'sum_metal_technos' );
	var sumCrystalTechnos = document.getElementById( 'sum_crystal_technos' );
	var sumDeutTechnos = document.getElementById( 'sum_deut_technos' );
	var sumEnergyTechnos = document.getElementById( 'sum_energy_technos' );
	var sumPTTechnos = document.getElementById( 'sum_pt_technos' );
	var sumGTTechnos = document.getElementById( 'sum_gt_technos' );
	var sumPointsTechnos = document.getElementById( 'sum_points_technos' );
	var sumTimeTechnos = document.getElementById( 'sum_time_technos' );
}
//def
var sumMetalWeapons = document.getElementById( 'sum_metal_weapons' );
var sumCrystalWeapons = document.getElementById( 'sum_crystal_weapons' );
var sumDeutWeapons = document.getElementById( 'sum_deut_weapons' );
var sumPTWeapons = document.getElementById( 'sum_pt_weapons' );
var sumGTWeapons = document.getElementById( 'sum_gt_weapons' );
var sumPointsWeapons = document.getElementById( 'sum_points_weapons' );
var sumTimeWeapons = document.getElementById( 'sum_time_weapons' );
//total
var sumMetal = document.getElementById( 'sum_metal' );
var sumCrystal = document.getElementById( 'sum_crystal' );
var sumDeut = document.getElementById( 'sum_deut' );
if( view === "planets" ) {
	var sumEnergy = document.getElementById( 'sum_energy' );
}
var sumPT = document.getElementById( 'sum_pt' );
var sumGT = document.getElementById( 'sum_gt' );
var sumPoints = document.getElementById( 'sum_points' );
var sumTime = document.getElementById( 'sum_time' );

//convert buildings time
var timeMetalBuildings = document.getElementById( 'time_metal_buildings' );
var timeCrystalBuildings = document.getElementById( 'time_crystal_buildings' );
var timeDeutBuildings = document.getElementById( 'time_deut_buildings' );
var timeConvertBuildings = document.getElementById( 'time_convert_buildings' );
if( view === "planets" ) {
	//convert technos time
	var timeMetalTechnos = document.getElementById( 'time_metal_technos' );
	var timeCrystalTechnos = document.getElementById( 'time_crystal_technos' );
	var timeDeutTechnos = document.getElementById( 'time_deut_technos' );
	var timeConvertTechnos = document.getElementById( 'time_convert_technos' );
}
//convert weapons time
var timeMetalWeapons = document.getElementById( 'time_metal_weapons' );
var timeCrystalWeapons = document.getElementById( 'time_crystal_weapons' );
var timeDeutWeapons = document.getElementById( 'time_deut_weapons' );
var timeConvertWeapons = document.getElementById( 'time_convert_weapons' );
//convert divers time
var timeMetalDivers = document.getElementById( 'time_metal_divers' );
var timeCrystalDivers = document.getElementById( 'time_crystal_divers' );
var timeDeutDivers = document.getElementById( 'time_deut_divers' );
var timeConvertDivers = document.getElementById( 'time_convert_divers' );
//convert sum time
var timeMetal = document.getElementById( 'time_metal' );
var timeCrystal = document.getElementById( 'time_crystal' );
var timeDeut = document.getElementById( 'time_deut' );
var timeConvert = document.getElementById( 'time_convert' );

function getLevelsOfUser() {

	if( view === "planets" ) {
		var arrayNanitesNode = document.getElementsByClassName("building current UDN");
		for (var i = 0; i < arrayNanitesNode.length; i++) {
			nanitesUser[arrayNanitesNode[i].id] = arrayNanitesNode[i].value;
		}
		var arrayLabNode = document.getElementsByClassName("building current LAB");
		if( RRILevelUser + 1 >= arrayLabNode.length ) {
			for (var i = 0; i < arrayLabNode.length; i++) {
				labUser += parseInt( arrayLabNode[i].value, 10 );
			}
		} else {
			let varLabs = [];
			for (var i = 0; i < arrayLabNode.length; i++) {
				 varLabs.push( parseInt( arrayLabNode[i].value, 10 ) );
			}
			for (var i = 0; i < RRILevelUser + 1 ; i++) {
				var lvlLabToAdd = Math.max.apply( Math, varLabs );
				var lvlLabToAddIndex = varLabs.indexOf( lvlLabToAdd );
				if( lvlLabToAddIndex > -1 ) {
					varLabs.splice( lvlLabToAddIndex, 1 );
				}
				labUser += lvlLabToAdd;
			}
		}
	}
	var arrayUDRNode = document.getElementsByClassName("building current UDR");		
	for (var i = 0; i < arrayUDRNode.length; i++) {
		uDRUser[arrayUDRNode[i].id] = parseInt( arrayUDRNode[i].value, 10 );
	}
	var arrayCSPNode = document.getElementsByClassName("building current CSP");		
	for (var i = 0; i < arrayCSPNode.length; i++) {
		cSPUser[arrayCSPNode[i].id] = parseInt( arrayCSPNode[i].value, 10 );
	}
}

function showProductionConverted() {
	let metal = parseInt( document.getElementById( "metal_prod" ).textContent.replace(/\./g,''), 10 );
	let crystal = parseInt( document.getElementById( "crystal_prod" ).textContent.replace(/\./g,''), 10 );
	let deut = parseInt( document.getElementById( "deut_prod" ).textContent.replace(/\./g,''), 10 );

	let rateMetal = parseInt( metalRate.value, 10 );
	let rateCrystal = parseInt( crystalRate.value, 10 );

	document.getElementById( "convert_prod" ).textContent = dotSeparateNumber( Math.floor( calcConvertResource( metal, crystal, deut, rateMetal, rateCrystal ) ) );
}

function showResourceConverted() {
	let metal = parseInt( document.getElementById( "metal_resource" ).value, 10 );
	let crystal = parseInt( document.getElementById( "crystal_resource" ).value, 10 );
	let deut = parseInt( document.getElementById( "deut_resource" ).value, 10 );

	let rateMetal = parseInt( metalRate.value, 10 );
	let rateCrystal = parseInt( crystalRate.value, 10 );

	document.getElementById( "convert_resource" ).textContent = dotSeparateNumber( Math.floor( calcConvertResource( metal, crystal, deut, rateMetal, rateCrystal ) ) );
}

function setSumToZero() {

	//buildings
	sumMetalBuildings.textContent = 0;
	sumCrystalBuildings.textContent = 0;
	sumDeutBuildings.textContent = 0;
	if( view === "planets" ) {
		sumEnergyBuildings.textContent = 0;
	}
	sumPTBuildings.textContent = 0;
	sumGTBuildings.textContent = 0;
	sumPointsBuildings.textContent = 0;
	sumTimeBuildings.textContent = convertS( 0 );
	//divers
	sumMetalDivers.textContent = 0;
	sumCrystalDivers.textContent = 0;
	sumDeutDivers.textContent = 0;
	sumPTDivers.textContent = 0;
	sumGTDivers.textContent = 0;
	sumPointsDivers.textContent = 0;
	sumTimeDivers.textContent = convertS( 0 );
	//research
	if( view === "planets" ) {
		sumMetalTechnos.textContent = 0;
		sumCrystalTechnos.textContent = 0;
		sumDeutTechnos.textContent = 0;
		sumEnergyTechnos.textContent = 0;
		sumPTTechnos.textContent = 0;
		sumGTTechnos.textContent = 0;
		sumPointsTechnos.textContent = 0;
		sumTimeTechnos.textContent = convertS( 0 );
	}
	//def
	sumMetalWeapons.textContent = 0;
	sumCrystalWeapons.textContent = 0;
	sumDeutWeapons.textContent = 0;
	sumPTWeapons.textContent = 0;
	sumGTWeapons.textContent = 0;
	sumPointsWeapons.textContent = 0;
	sumTimeWeapons.textContent = convertS( 0 );
	//total
	sumMetal.textContent = 0;
	sumCrystal.textContent = 0;
	sumDeut.textContent = 0;;
	if( view === "planets" ) {
		sumEnergy.textContent = 0;
	}
	sumPT.textContent = 0;
	sumGT.textContent = 0;
	sumPoints.textContent = 0;
	sumTime.textContent = convertS( 0 );

	//convert buildings time
	timeMetalBuildings.textContent = convertH( 0 );
 	timeCrystalBuildings.textContent = convertH( 0 );
 	timeDeutBuildings.textContent = convertH( 0 );
 	timeConvertBuildings.textContent = convertH( 0 );
	//convert technos time
	if( view === "planets" ) {
 		timeMetalTechnos.textContent = convertH( 0 );
 		timeCrystalTechnos.textContent = convertH( 0 );
 		timeDeutTechnos.textContent = convertH( 0 );
 		timeConvertTechnos.textContent = convertH( 0 );
	}
	//convert weapons time
 	timeMetalWeapons.textContent = convertH( 0 );
 	timeCrystalWeapons.textContent = convertH( 0 );
 	timeDeutWeapons.textContent = convertH( 0 );
 	timeConvertWeapons.textContent = convertH( 0 );
 	//convert divers time
	timeMetalDivers.textContent = convertH( 0 );
	timeCrystalDivers.textContent = convertH( 0 );
	timeDeutDivers.textContent = convertH( 0 );
	timeConvertDivers.textContent = convertH( 0 );
	//convert sum time
	timeMetal.textContent = convertH( 0 );
	timeCrystal.textContent = convertH( 0 );
	timeDeut.textContent = convertH( 0 );
	timeConvert.textContent = convertH( 0 );
}

function initHtml() {

	var inputs = document.getElementsByTagName('input');
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].addEventListener("input", function () {
			console.log( this );
			this.value = keepOnlyNumber( this.value );
			console.log( this.value );
			showProductionConverted();
			showResourceConverted();
	    	objectifs();
		});
	}

	setSumToZero();
}

function keepOnlyNumber( value ) {

	for(let i = 0; i < value.length; i++) {
		value = value.replace(/\D/g,"").replace(/^0+/, '');
	}

	if( value.length == 0 ) {
		value = 0;
	}	

	return value;
}

function initOGSCalc() {
	getLevelsOfUser();
	initHtml();
	showProductionConverted();
	showResourceConverted();
}

function objectifs() {
	//building
	let costProjectBuildingM = 0;
	let costProjectBuildingC = 0;
	let costProjectBuildingD = 0;
	let costProjectBuildingE = 0;
	let costProjectBuildingPT = 0;
	let costProjectBuildingGT = 0;
	let totalResourceBuilding = 0;
	let costProjectBuildingPoints = 0;
	let costProjectBuildingT = 0;
	//building dest
	let costProjectBuildingMD = 0;
	let costProjectBuildingCD = 0;
	let costProjectBuildingDD = 0;
	let totalResourceDest = 0;
	//techno
	let costProjectTechnoM = 0;
	let costProjectTechnoC = 0;
	let costProjectTechnoD = 0;
	let costProjectTechnoE = 0;
	let costProjectTechnoPT = 0;
	let costProjectTechnoGT = 0;
	let totalResourceTechno = 0;
	let costProjectTechnoPoints = 0;
	let costProjectTechnoT = 0;
	//divers
	let costProjectDiversM = 0;
	let costProjectDiversC = 0;
	let costProjectDiversD = 0;
	let costProjectDiversPT = 0;
	let costProjectDiversGT = 0;
	let totalResourceDivers = 0;
	let costProjectDiversPoints = 0;
	let costProjectDiversT = 0;
	//weapon
	let costProjectWeaponM = 0;
	let costProjectWeaponC = 0;
	let costProjectWeaponD = 0;
	let costProjectWeaponPT = 0;
	let costProjectWeaponGT = 0;
	let totalResourceWeapon = 0;
	let costProjectWeaponPoints = 0;
	let costProjectWeaponT = 0;
	//sum
	let costProjectM = 0;
	let costProjectC = 0;
	let costProjectD = 0;
	let costProjectE = 0;
	let costProjectPT = 0;
	let costProjectGT = 0;
	let totalResource = 0;
	let costProjectPoints = 0;
	let costProjectT = 0;
	
	let project = document.getElementsByClassName('project');

	for (var i = 0; i < project.length; i++) {

			if( project[i].classList.contains( 'building' ) ) {

				let building = buildingsCosts( project[i] );
				costProjectBuildingM += building.costProjectBuildingM;
				costProjectBuildingC += building.costProjectBuildingC;
				costProjectBuildingD += building.costProjectBuildingD;
				costProjectBuildingE += building.costProjectBuildingE;
				costProjectBuildingT += building.costProjectBuildingT;
				costProjectBuildingMD += building.costProjectBuildingMD;
				costProjectBuildingCD += building.costProjectBuildingCD;
				costProjectBuildingDD += building.costProjectBuildingDD;
				totalResourceDest += building.totalResourceDest;
        	}

        	if( project[i].classList.contains( 'technos' ) ) {

				let techno = technosCosts( project[i] );
				costProjectTechnoM += techno.costProjectTechnoM;
				costProjectTechnoC += techno.costProjectTechnoC;
				costProjectTechnoD += techno.costProjectTechnoD;
				costProjectTechnoE += techno.costProjectTechnoE;
				costProjectTechnoT += techno.costProjectTechnoT;				
        	}

        	if( project[i].classList.contains( 'weapons' ) ) {

        		let weapon = weaponsCosts( project[i] );
				costProjectWeaponM += weapon.costProjectWeaponM;
				costProjectWeaponC += weapon.costProjectWeaponC;
				costProjectWeaponD += weapon.costProjectWeaponD;
				costProjectWeaponT += weapon.costProjectWeaponT;
        	}

        	if( project[i].classList.contains( 'divers' ) ) {

				let divers = diversCosts( project[i] );
				costProjectDiversM += divers.costProjectDiversM;
				costProjectDiversC += divers.costProjectDiversC;
				costProjectDiversD += divers.costProjectDiversD;
				costProjectDiversT += divers.costProjectDiversT;
        	}
	}
	//building
	totalResourceBuilding = - totalResourceDest + costProjectBuildingM + costProjectBuildingC + costProjectBuildingD + costProjectBuildingMD + costProjectBuildingCD + costProjectBuildingDD;

	if( costProjectBuildingM - parseInt( metalAccount.value, 10 ) < 0 ) {
		costProjectBuildingM = 0;
	} else {
		costProjectBuildingM -= parseInt( metalAccount.value, 10 );
	}
	if( costProjectBuildingC - parseInt( crystalAccount.value, 10 ) < 0 ) {
		costProjectBuildingC = 0;
	} else {
		costProjectBuildingC -= parseInt( crystalAccount.value, 10 );
	}
	if( costProjectBuildingD - parseInt( deutAccount.value, 10 ) < 0 ) {
		costProjectBuildingD = 0;
	} else {
		costProjectBuildingD -= parseInt( deutAccount.value, 10 );
	}

	sumMetalBuildings.textContent = dotSeparateNumber( Math.floor(costProjectBuildingM) );
	sumCrystalBuildings.textContent = dotSeparateNumber( Math.floor(costProjectBuildingC) );	
	sumDeutBuildings.textContent = dotSeparateNumber( Math.floor(costProjectBuildingD) );
	if( view === "planets" ) {
		sumEnergyBuildings.textContent = dotSeparateNumber( Math.floor(costProjectBuildingE) );
	}
	sumPTBuildings.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectBuildingM, costProjectBuildingC, costProjectBuildingD ) );
	sumGTBuildings.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectBuildingM, costProjectBuildingC, costProjectBuildingD ) );
	sumPointsBuildings.textContent = dotSeparateNumber( calcPoints( totalResourceBuilding, 0, 0) ); 
	sumTimeBuildings.textContent = convertS( costProjectBuildingT );
	timeMetalBuildings.textContent = calcTimeProduction( metalPerHour, Math.floor(costProjectBuildingM) );
	timeCrystalBuildings.textContent = calcTimeProduction( crystalPerHour, Math.floor(costProjectBuildingC) );
	timeDeutBuildings.textContent = calcTimeProduction( deutPerHour, Math.floor(costProjectBuildingD) );
	timeConvertBuildings.textContent = calcTimeProductionConvert( Math.floor(costProjectBuildingM), Math.floor(costProjectBuildingC), Math.floor(costProjectBuildingD), metalPerHour, crystalPerHour, deutPerHour );
	//techno
	if( view === "planets" ) {
		totalResourceTechno = costProjectTechnoM + costProjectTechnoC + costProjectTechnoD;

		if( costProjectTechnoM - parseInt( metalAccount.value, 10 ) < 0 ) {
			costProjectTechnoM = 0;
		} else {
			costProjectTechnoM -= parseInt( metalAccount.value, 10 );
		}
		if( costProjectTechnoC - parseInt( crystalAccount.value, 10 ) < 0 ) {
			costProjectTechnoC = 0;
		} else {
			costProjectTechnoC -= parseInt( crystalAccount.value, 10 );
		}
		if( costProjectTechnoD - parseInt( deutAccount.value, 10 ) < 0 ) {
			costProjectTechnoD = 0;
		} else {
			costProjectTechnoD -= parseInt( deutAccount.value, 10 );
		}

		sumMetalTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoM) );
		sumCrystalTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoC) );	
		sumDeutTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoD) );
		sumEnergyTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoE) );		
		sumPTTechnos.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectTechnoM, costProjectTechnoC, costProjectTechnoD ) );
		sumGTTechnos.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectTechnoM, costProjectTechnoC, costProjectTechnoD ) );
		sumPointsTechnos.textContent = dotSeparateNumber( calcPoints( totalResourceTechno, 0, 0) ); 
		sumTimeTechnos.textContent = convertS( costProjectTechnoT );
		timeMetalTechnos.textContent = calcTimeProduction( metalPerHour, Math.floor(costProjectTechnoM) );
		timeCrystalTechnos.textContent = calcTimeProduction( crystalPerHour, Math.floor(costProjectTechnoC) );
		timeDeutTechnos.textContent = calcTimeProduction( deutPerHour, Math.floor(costProjectTechnoD) );
		timeConvertTechnos.textContent = calcTimeProductionConvert( Math.floor(costProjectTechnoM), Math.floor(costProjectTechnoC), Math.floor(costProjectTechnoD), metalPerHour, crystalPerHour, deutPerHour );
	}

	//divers
	totalResourceDivers = costProjectDiversM + costProjectDiversC + costProjectDiversD;

	if( costProjectDiversM - parseInt( metalAccount.value, 10 ) < 0 ) {
		costProjectDiversM = 0;
	} else {
		costProjectDiversM -= parseInt( metalAccount.value, 10 );
	}
	if( costProjectDiversC - parseInt( crystalAccount.value, 10 ) < 0 ) {
		costProjectDiversC = 0;
	} else {
		costProjectDiversC -= parseInt( crystalAccount.value, 10 );
	}
	if( costProjectDiversD - parseInt( deutAccount.value, 10 ) < 0 ) {
		costProjectDiversD = 0;
	} else {
		costProjectDiversD -= parseInt( deutAccount.value, 10 );
	}

	sumMetalDivers.textContent = dotSeparateNumber( Math.floor(costProjectDiversM) );
	sumCrystalDivers.textContent = dotSeparateNumber( Math.floor(costProjectDiversC) );	
	sumDeutDivers.textContent = dotSeparateNumber( Math.floor(costProjectDiversD) );
	sumPTDivers.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectDiversM, costProjectDiversC, costProjectDiversD ) );
	sumGTDivers.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectDiversM, costProjectDiversC, costProjectDiversD ) );
	sumPointsDivers.textContent = dotSeparateNumber( calcPoints( totalResourceDivers, 0, 0) ); 
	sumTimeDivers.textContent = convertS( costProjectDiversT );
	timeMetalDivers.textContent = calcTimeProduction( metalPerHour, Math.floor(costProjectDiversM) );
	timeCrystalDivers.textContent = calcTimeProduction( crystalPerHour, Math.floor(costProjectDiversC) );
	timeDeutDivers.textContent = calcTimeProduction( deutPerHour, Math.floor(costProjectDiversD) );
	timeConvertDivers.textContent = calcTimeProductionConvert( Math.floor(costProjectDiversM), Math.floor(costProjectDiversC), Math.floor(costProjectDiversD), metalPerHour, crystalPerHour, deutPerHour );

	//weapons
	totalResourceWeapon = costProjectWeaponM + costProjectWeaponC + costProjectWeaponD;

	if( costProjectWeaponM - parseInt( metalAccount.value, 10 ) < 0 ) {
		costProjectWeaponM = 0;
	} else {
		costProjectWeaponM -= parseInt( metalAccount.value, 10 );
	}
	if( costProjectWeaponC - parseInt( crystalAccount.value, 10 ) < 0 ) {
		costProjectWeaponC = 0;
	} else {
		costProjectWeaponC -= parseInt( crystalAccount.value, 10 );
	}
	if( costProjectWeaponD - parseInt( deutAccount.value, 10 ) < 0 ) {
		costProjectWeaponD = 0;
	} else {
		costProjectWeaponD -= parseInt( deutAccount.value, 10 );
	}
	
	sumMetalWeapons.textContent = dotSeparateNumber( Math.floor(costProjectWeaponM) );
	sumCrystalWeapons.textContent = dotSeparateNumber( Math.floor(costProjectWeaponC) );	
	sumDeutWeapons.textContent = dotSeparateNumber( Math.floor(costProjectWeaponD) );
	sumPTWeapons.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectWeaponM, costProjectWeaponC, costProjectWeaponD ) );
	sumGTWeapons.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectWeaponM, costProjectWeaponC, costProjectWeaponD ) );
	sumPointsWeapons.textContent = dotSeparateNumber( calcPoints( totalResourceWeapon, 0, 0) ); 
	sumTimeWeapons.textContent = convertS( costProjectWeaponT );
	timeMetalWeapons.textContent = calcTimeProduction( metalPerHour, Math.floor(costProjectWeaponM) );
	timeCrystalWeapons.textContent = calcTimeProduction( crystalPerHour, Math.floor(costProjectWeaponC) );
	timeDeutWeapons.textContent = calcTimeProduction( deutPerHour, Math.floor(costProjectWeaponD) );
	timeConvertWeapons.textContent = calcTimeProductionConvert( Math.floor(costProjectWeaponM), Math.floor(costProjectWeaponC), Math.floor(costProjectWeaponD), metalPerHour, crystalPerHour, deutPerHour );

	//sum
	totalResource = totalResourceBuilding + totalResourceTechno + totalResourceDivers + totalResourceWeapon;
	sumMetal.textContent = dotSeparateNumber( Math.floor(costProjectBuildingM) + Math.floor(costProjectTechnoM) + Math.floor(costProjectWeaponM) + Math.floor(costProjectDiversM) );
	sumCrystal.textContent = dotSeparateNumber( Math.floor(costProjectBuildingC) + Math.floor(costProjectTechnoC) + Math.floor(costProjectWeaponC) + Math.floor(costProjectDiversC) );
	sumDeut.textContent = dotSeparateNumber( Math.floor(costProjectBuildingD) + Math.floor(costProjectTechnoD) + Math.floor(costProjectWeaponD) + Math.floor(costProjectDiversD) );
	if( view === "planets" ) {
		sumEnergy = dotSeparateNumber( Math.floor(costProjectBuildingE) + Math.floor(costProjectTechnoE));
	}
	sumPT.textContent = dotSeparateNumber( calcTransport( capaPT, totalResource, 0, 0 ) );
	sumGT.textContent = dotSeparateNumber( calcTransport( capaGT, totalResource, 0, 0 ) );
	sumPoints.textContent = dotSeparateNumber( calcPoints( totalResource, 0, 0) );
	sumTime.textContent = convertS( costProjectBuildingT + costProjectTechnoT + costProjectDiversT + costProjectWeaponT );
	timeMetal.textContent = calcTimeProduction( metalPerHour, Math.floor(costProjectBuildingM) + Math.floor(costProjectTechnoM) + Math.floor(costProjectWeaponM) + Math.floor(costProjectDiversM) );
	timeCrystal.textContent = calcTimeProduction( crystalPerHour, Math.floor(costProjectBuildingC) + Math.floor(costProjectTechnoC) + Math.floor(costProjectWeaponC) + Math.floor(costProjectDiversC) );
	timeDeut.textContent = calcTimeProduction( deutPerHour, Math.floor(costProjectBuildingD) + Math.floor(costProjectTechnoD) + Math.floor(costProjectWeaponD) + Math.floor(costProjectDiversD) );
	timeConvert.textContent = calcTimeProductionConvert( Math.floor(costProjectBuildingM) + Math.floor(costProjectTechnoM) + Math.floor(costProjectWeaponM) + Math.floor(costProjectDiversM), Math.floor(costProjectBuildingC) + Math.floor(costProjectTechnoC) + Math.floor(costProjectWeaponC) + Math.floor(costProjectDiversC), Math.floor(costProjectBuildingD) + Math.floor(costProjectTechnoD) + Math.floor(costProjectWeaponD) + Math.floor(costProjectDiversD), metalPerHour, crystalPerHour, deutPerHour );
}

function calcTransport( capacity, res1, res2, res3 ) {
	let res = res1 + res2 + res3;

	if( res > 0 && capacity > 0 ) {
		res = Math.ceil( res / capacity );
	}

	return res;
}

function calcPoints( res1, res2, res3 ) {
	let res = res1 + res2 + res3;
	if( res !== 0  ) {
		if( res > 0 ) {
			return Math.floor( res / 1000 );
		} else {
			return Math.ceil( res / 1000 );
		}
	}

	return res;
}

function calcConvertResource( metal, crystal, deut, metalRate, crystalRate ) {

	let metalConvert = 0;
	let crystalConvert = 0;

	if( metal > 0 ) {
		metalConvert = metal / metalRate;
	}

	if( crystal > 0 ) {
		crystalConvert = crystal / crystalRate;
	}

	return metalConvert + crystalConvert + deut;
}

function calcTimeProductionConvert( metalResource, crystalResource, deutResource, metalProd, crystalProd, deutProd ) {
	
	let metalResourceConvert = 0;
	let metalProdConvert = 0;
	let crystalResourceConvert = 0;
	let crystalProdConvert = 0;
	let resourcesConvertedInDeut = 0;
	let prodsConvertedInDeut = 0;

	if( metalResource !== 0 ) {
		metalResourceConvert = metalResource / parseInt( metalRate.value, 10 );
		resourcesConvertedInDeut += metalResourceConvert;
	}
	if( metalProd !== 0 ) {
		metalProdConvert = metalProd / parseInt( metalRate.value, 10 );
		prodsConvertedInDeut += metalProdConvert;
	}

	if( crystalResource !== 0 ) {
		crystalResourceConvert = crystalResource / parseInt( crystalRate.value, 10 );
		resourcesConvertedInDeut += crystalResourceConvert;
	}
	if( crystalProd !== 0 ) {
		crystalProdConvert = crystalProd / parseInt( crystalRate.value, 10 );
		prodsConvertedInDeut += crystalProdConvert;
	}

	resourcesConvertedInDeut += deutResource;
	prodsConvertedInDeut += deutProd;

	return calcTimeProduction( Math.floor( prodsConvertedInDeut ), Math.floor( resourcesConvertedInDeut ) );
}

function calcTimeProduction( production, need ) {
	if( need === 0 ) { return 0 };
	return convertH( need / production );
}

function convertS(s) {
  let d, h, m, w;
  m = Math.floor(s / 60);
  s = s % 60;
  h = Math.floor(m / 60);
  m = m % 60;
  d = Math.floor(h / 24);
  h = h % 24;
  w = Math.floor(d / 7);
  d = d % 7;
  s += "";
  m += "";
  h += "";
  if( s.length == 1) { s = "0"+s; }
  if( m.length == 1) { m = "0"+m; }
  if( h.length == 1) { h = "0"+h; }
  return w + "S " + d + "J " + h + ":" + m + ":" + s;
}

function convertH(h) {
	if( h == 0) {return 0; }
	return precisionRound( h / 24, 1 ); 
}

function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}

function dotSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
    }
    return val;
}

initOGSCalc();
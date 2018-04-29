var nanitesUser = {};
var uDRUser = {};
var cSPUser = {};
var labUser = 0;
var capaPT = 5000;
var capaGT = 25000;
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



function getLevelsOfUser() {

	if( view === "planets" ) {
		var arrayNanitesNode = document.getElementsByClassName("building current UDN");
		for (var i = 0; i < arrayNanitesNode.length; i++) {
			nanitesUser[arrayNanitesNode[i].id] = arrayNanitesNode[i].value;
		}
		var arrayLabNode = document.getElementsByClassName("building current LAB");
		if( RRILevelUser + 1 >= arrayLabNode.length ) {
			for (var i = 0; i < arrayLabNode.length; i++) {
				labUser += parseInt( arrayLabNode[i].value );
			}
		} else {
			let varLabs = [];
			for (var i = 0; i < arrayLabNode.length; i++) {
				 varLabs.push( parseInt( arrayLabNode[i].value ) );
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
		uDRUser[arrayUDRNode[i].id] = parseInt( arrayUDRNode[i].value );
	}
	var arrayCSPNode = document.getElementsByClassName("building current CSP");		
	for (var i = 0; i < arrayCSPNode.length; i++) {
		cSPUser[arrayCSPNode[i].id] = parseInt( arrayCSPNode[i].value );
	}
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
}

function initHtml() {

	var inputs = document.getElementsByTagName('input');
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].addEventListener("input", function (e) {
	    	objectifs();
		});
	}

	setSumToZero();
}

function initOGSCalc() {
	getLevelsOfUser();
	initHtml();
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
        		//function in weaponsCosts.js
        		let divers = diversCosts( project[i] );
				costProjectDiversM += divers.costProjectDiversM;
				costProjectDiversC += divers.costProjectDiversC;
				costProjectDiversD += divers.costProjectDiversD;
				costProjectDiversT += divers.costProjectDiversT;
        	}
	}
	//building
	totalResourceBuilding = - totalResourceDest + costProjectBuildingM + costProjectBuildingC + costProjectBuildingD + costProjectBuildingMD + costProjectBuildingCD + costProjectBuildingDD;

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
	
	//techno
	if( view === "planets" ) {
		totalResourceTechno = costProjectTechnoM + costProjectTechnoC + costProjectTechnoD;

		sumMetalTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoM) );
		sumCrystalTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoC) );	
		sumDeutTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoD) );
		sumEnergyTechnos.textContent = dotSeparateNumber( Math.floor(costProjectTechnoE) );		
		sumPTTechnos.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectTechnoM, costProjectTechnoC, costProjectTechnoD ) );
		sumGTTechnos.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectTechnoM, costProjectTechnoC, costProjectTechnoD ) );
		sumPointsTechnos.textContent = dotSeparateNumber( calcPoints( totalResourceTechno, 0, 0) ); 
		sumTimeTechnos.textContent = convertS( costProjectTechnoT );
	}

	//divers
	totalResourceDivers = costProjectDiversM + costProjectDiversC + costProjectDiversD;
	sumMetalDivers.textContent = dotSeparateNumber( Math.floor(costProjectDiversM) );
	sumCrystalDivers.textContent = dotSeparateNumber( Math.floor(costProjectDiversC) );	
	sumDeutDivers.textContent = dotSeparateNumber( Math.floor(costProjectDiversD) );
	sumPTDivers.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectDiversM, costProjectDiversC, costProjectDiversD ) );
	sumGTDivers.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectDiversM, costProjectDiversC, costProjectDiversD ) );
	sumPointsDivers.textContent = dotSeparateNumber( calcPoints( totalResourceDivers, 0, 0) ); 
	sumTimeDivers.textContent = convertS( costProjectDiversT );

	//weapons
	totalResourceWeapon = costProjectWeaponM + costProjectWeaponC + costProjectWeaponD;
	sumMetalWeapons.textContent = dotSeparateNumber( Math.floor(costProjectWeaponM) );
	sumCrystalWeapons.textContent = dotSeparateNumber( Math.floor(costProjectWeaponC) );	
	sumDeutWeapons.textContent = dotSeparateNumber( Math.floor(costProjectWeaponD) );
	sumPTWeapons.textContent = dotSeparateNumber( calcTransport( capaPT, costProjectWeaponM, costProjectWeaponC, costProjectWeaponD ) );
	sumGTWeapons.textContent = dotSeparateNumber( calcTransport( capaGT, costProjectWeaponM, costProjectWeaponC, costProjectWeaponD ) );
	sumPointsWeapons.textContent = dotSeparateNumber( calcPoints( totalResourceWeapon, 0, 0) ); 
	sumTimeWeapons.textContent = convertS( costProjectWeaponT );

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
	sumPoints.textContent = dotSeparateNumber( calcPoints( totalResource, 0, 0) )
	sumTime.textContent = convertS( costProjectBuildingT + costProjectTechnoT + costProjectDiversT + costProjectWeaponT )
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



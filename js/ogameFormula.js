priceBuildings = {};
priceBuildings["M"] = [60, 15, 0, 0];
priceBuildings["C"] = [48, 24, 0, 0];
priceBuildings["D"] = [225, 75, 0, 0];
priceBuildings["CES"] = [75, 30, 0, 0];
priceBuildings["CEF"] = [900, 360, 180, 0];
priceBuildings["UDR"] = [400, 120, 200, 0];
priceBuildings["UDN"] = [1000000, 500000, 100000, 0];
priceBuildings["CSP"] = [400, 200, 100, 0];
priceBuildings["HM"] = [1000, 0, 0, 0];
priceBuildings["HC"] = [1000, 500, 0, 0];
priceBuildings["HD"] = [1000, 1000, 0, 0];
priceBuildings["LAB"] = [200, 400, 200, 0];
priceBuildings["DDR"] = [20000, 40000, 0, 0];
priceBuildings["TER"] = [0, 50000, 100000, 1000];
priceBuildings["SILO"] = [20000, 20000, 1000, 0];
priceBuildings["BALU"] = [20000, 40000, 20000, 0];
priceBuildings["PHA"] = [20000, 40000, 20000, 0];
priceBuildings["POSA"] = [2000000, 1000, 2000000, 0];

priceTechnos = {};
priceTechnos["NRJ"] = [2, 0, 800, 400];
priceTechnos["Laser"] = [2, 200, 100, 0];
priceTechnos["Ions"] = [2, 1000, 300, 100];
priceTechnos["Hyp"] = [2, 0, 4000, 2000];
priceTechnos["Plasma"] = [2, 2000, 4000, 1000];
priceTechnos["RC"] = [2, 400, 0, 600];
priceTechnos["RI"] = [2, 2000, 4000, 600];
priceTechnos["PH"] = [2, 10000, 20000, 6000];
priceTechnos["Esp"] = [2, 200, 1000, 200];
priceTechnos["Ordi"] = [2, 0, 400, 600];
priceTechnos["Astrophysique"] = [1.75, 4000, 8000, 4000];
priceTechnos["RRI"] = [2, 240000, 400000, 160000];
priceTechnos["Graviton"] = [3, 0, 0, 0];
priceTechnos["Armes"] = [2, 800, 200, 0];
priceTechnos["Bouclier"] = [2, 200, 600, 0];
priceTechnos["Protection"] = [2, 1000, 0, 0];

priceDestructibles = {};
priceDestructibles["Sat"] = [0, 2000, 500, 6];
priceDestructibles["LM"] = [2000, 0, 0, 7];
priceDestructibles["LLE"] = [1500, 500, 0, 8];
priceDestructibles["LLO"] = [6000, 2000, 0, 9];
priceDestructibles["CG"] = [20000, 15000, 2000, 10];
priceDestructibles["AI"] = [2000, 6000, 0, 11];
priceDestructibles["LP"] = [50000, 50000, 30000, 12];
priceDestructibles["PB"] = [10000, 10000, 0, 13];
priceDestructibles["GB"] = [50000, 50000, 0, 14];
priceDestructibles["MIC"] = [8000, 0, 2000, 19];
priceDestructibles["MIP"] = [12500, 2500, 10000, 18];

function costDestructibles( destructible, project ) {

	return {
		metal: priceDestructibles[destructible][0] * project,
		crystal: priceDestructibles[destructible][1] * project,
		deut: priceDestructibles[destructible][2] * project
	}
}

function timeConstDestructibles( destructible, project, position ) {

	let planete = position.replace( priceDestructibles[destructible][3], "" );
	let postionNanite = "udn" + planete;
	let postionCsp = "csp" + planete;

	if( view === "planets" ) {
		return Math.max( Math.floor( ( ( priceDestructibles[destructible][0] + priceDestructibles[destructible][1] ) / 5000 ) * ( 2 / ( 1 + cSPUser[postionCsp] ) ) * Math.pow(0.5, nanitesUser[postionNanite]) / speedUni * 3600 ), 1 ) * project;
	} else {
		return Math.max( Math.floor( ( ( priceDestructibles[destructible][0] + priceDestructibles[destructible][1] ) / 5000 ) * ( 2 / ( 1 + cSPUser[postionCsp] ) )  / speedUni * 3600 ), 1 ) * project;
	}
}

function costTechnos( techno, current, project ) {

	let metal = priceTechnos[techno][1] * ( 1 - Math.pow( priceTechnos[techno][0], project )) / ( 1 - priceTechnos[techno][0]);
	metal -= priceTechnos[techno][1] * ( 1 - Math.pow( priceTechnos[techno][0], current )) / ( 1 - priceTechnos[techno][0]);
	let crystal = priceTechnos[techno][2] * ( 1 - Math.pow( priceTechnos[techno][0], project )) / ( 1 - priceTechnos[techno][0]);
	crystal -= priceTechnos[techno][2] * ( 1 - Math.pow( priceTechnos[techno][0], current )) / ( 1 - priceTechnos[techno][0]);
	let deut = priceTechnos[techno][3] * ( 1 - Math.pow( priceTechnos[techno][0], project )) / ( 1 - priceTechnos[techno][0]);
	deut -= priceTechnos[techno][3] * ( 1 - Math.pow( priceTechnos[techno][0], current )) / ( 1 - priceTechnos[techno][0])
	let energy = ( techno === "Graviton" ) ? ( 300000 * ( Math.pow( priceTechnos[techno][0], project ) - Math.pow( priceTechnos[techno][0], project - 1 ) ) / ( priceTechnos[techno][0] - 1 ) ) : 0;

	return {
		metal: metal,
		crystal: crystal,
		deut: deut,
		energy: energy
	}
}

function timeConstTechnos( metal, crystal ) {

	return Math.max( Math.floor( ( metal + crystal ) / ( 1000 * ( 1 + labUser ) ) / speedUni * 3600 ), 1 );
}

function timeConstBuildings( metal, crystal, position ) {

	let postionNanite = "udn" + position;
	let postionUdr = "udr" + position;

	if( view === "planets" ) {
		return Math.max( Math.floor( ( ( metal + crystal ) / ( 2500 * ( 1 + uDRUser[postionUdr] ) * Math.pow(2, nanitesUser[postionNanite] ) ) ) / speedUni * 3600 ), 1 );
	} else {
		return Math.max( Math.floor( ( ( metal + crystal ) / ( 2500 * (1 + uDRUser[postionUdr] ) ) ) / speedUni * 3600 ), 1 );
	}
}

function metalMine( level ) {
	
	return {
		metal: priceBuildings["M"][0] * (1 - Math.pow(1.5, level)) / (1 - 1.5),
		crystal: priceBuildings["M"][1] * (1 - Math.pow(1.5, level)) / (1 - 1.5),
		deut: 0,
		energy: 0
	}
}

function metalMineDest( currentLevel, projectLevel ) {

	return {
		metal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["M"][0] * (1 - Math.pow( 1.5, currentLevel - 1 ) ) / (1 - 1.5) - ( priceBuildings["M"][0] * (1 - Math.pow( 1.5, projectLevel - 1 ) ) / (1 - 1.5) ) ),
		crystal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["M"][1] * (1 - Math.pow( 1.5, currentLevel - 1 ) ) / (1 - 1.5) - ( priceBuildings["M"][1] * (1 - Math.pow( 1.5, projectLevel - 1 ) ) / (1 - 1.5) ) ),
		deut: 0,
		energy: 0
	}
}

function crystalMine( level ) {

	return {
		metal: priceBuildings["C"][0] * (1 - Math.pow(1.6, level)) / (1 - 1.6),
		crystal: priceBuildings["C"][1] * (1 - Math.pow(1.6, level)) / (1 - 1.6),
		deut: 0,
		energy: 0
	}
}

function crystalMineDest( currentLevel, projectLevel ) {

	return {
		metal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["C"][0] * (1 - Math.pow( 1.6, currentLevel - 1 ) ) / (1 - 1.6) - ( priceBuildings["C"][0] * (1 - Math.pow( 1.6, projectLevel - 1 ) ) / (1 - 1.6) ) ),
		crystal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["C"][1] * (1 - Math.pow( 1.6, currentLevel - 1 ) ) / (1 - 1.6) - ( priceBuildings["C"][1] * (1 - Math.pow( 1.6, projectLevel - 1 ) ) / (1 - 1.6) ) ),
		deut: 0,
		energy: 0
	}
}

function deutMine( level ) {

	return {
		metal: priceBuildings["D"][0] * (1 - Math.pow(1.5, level)) / (1 - 1.5),
		crystal: priceBuildings["D"][1] * (1 - Math.pow(1.5, level)) / (1 - 1.5),
		deut: 0,
		energy: 0
	}
}

function deutMineDest( currentLevel, projectLevel ) {

	return {
		metal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["D"][0] * (1 - Math.pow( 1.5, currentLevel - 1 ) ) / (1 - 1.5) - ( priceBuildings["D"][0] * (1 - Math.pow( 1.5, projectLevel - 1 ) ) / (1 - 1.5) ) ),
		crystal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["D"][1] * (1 - Math.pow( 1.5, currentLevel - 1 ) ) / (1 - 1.5) - ( priceBuildings["D"][1] * (1 - Math.pow( 1.5, projectLevel - 1 ) ) / (1 - 1.5) ) ),
		deut: 0,
		energy: 0
	}
}

function solar( level ) {

	return {
		metal: priceBuildings["CES"][0] * (1 - Math.pow(1.5, level)) / (1 - 1.5),
		crystal: priceBuildings["CES"][1] * (1 - Math.pow(1.5, level)) / (1 - 1.5),
		deut: 0,
		energy: 0
	}
}

function solarDest( currentLevel, projectLevel ) {

	return {
		metal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["CES"][0] * (1 - Math.pow( 1.5, currentLevel - 1 ) ) / (1 - 1.5) - ( priceBuildings["CES"][0] * (1 - Math.pow( 1.5, projectLevel - 1 ) ) / (1 - 1.5) ) ),
		crystal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["CES"][1] * (1 - Math.pow( 1.5, currentLevel - 1 ) ) / (1 - 1.5) - ( priceBuildings["CES"][1] * (1 - Math.pow( 1.5, projectLevel - 1 ) ) / (1 - 1.5) ) ),
		deut: 0,
		energy: 0
	}
}

function fusion( level ) {

	return {
		metal: priceBuildings["CEF"][0] * (1 - Math.pow(1.8, level)) / (1 - 1.8),
		crystal: priceBuildings["CEF"][1] * (1 - Math.pow(1.8, level)) / (1 - 1.8),
		deut: priceBuildings["CEF"][2] * (1 - Math.pow(1.8, level)) / (1 - 1.8),
		energy: 0
	}
}

function fusionDest( currentLevel, projectLevel ) {

	return {
		metal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["CEF"][0] * (1 - Math.pow( 1.8, currentLevel - 1 ) ) / (1 - 1.8) - ( priceBuildings["CEF"][0] * (1 - Math.pow( 1.8, projectLevel - 1 ) ) / (1 - 1.8) ) ),
		crystal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["CEF"][1] * (1 - Math.pow( 1.8, currentLevel - 1 ) ) / (1 - 1.8) - ( priceBuildings["CEF"][1] * (1 - Math.pow( 1.8, projectLevel - 1 ) ) / (1 - 1.8) ) ),
		deut: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings["CEF"][2] * (1 - Math.pow( 1.8, currentLevel - 1 ) ) / (1 - 1.8) - ( priceBuildings["CEF"][2] * (1 - Math.pow( 1.8, projectLevel - 1 ) ) / (1 - 1.8) ) ),
		energy: 0
	}
}

function building( building, level ) {

	return {
		metal: priceBuildings[building][0] * - ( 1 - Math.pow( 2, level ) ),
		crystal: priceBuildings[building][1] * - ( 1 - Math.pow( 2, level ) ),
		deut: priceBuildings[building][2] * - ( 1 - Math.pow( 2, level ) ),
		energy: priceBuildings[building][3] * - ( 1 - Math.pow( 2, level ) )
	}
}

function buildingDest( building, currentLevel, projectlevel ) {

	return {
		metal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings[building][0] * - ( 1 - Math.pow( 2, currentLevel - 1 ) ) - ( priceBuildings[building][0] * - ( 1 - Math.pow( 2, projectlevel - 1 ) ) ) ),
		crystal: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings[building][1] * - ( 1 - Math.pow( 2, currentLevel - 1 ) ) - ( priceBuildings[building][1] * - ( 1 - Math.pow( 2, projectlevel - 1 ) ) ) ),
		deut: (1 - 0.04 * IonsLevelUser ) * ( priceBuildings[building][2] * - ( 1 - Math.pow( 2, currentLevel - 1 ) ) - ( priceBuildings[building][2] * - ( 1 - Math.pow( 2, projectlevel - 1 ) ) ) ),
		energy: 0,
	}
}
<?php

/**
* ogscalc.php - Programme principal
* @package Calculatrice universelle
* @author Aeris
* @update xaviernuma - 2016
* @link http://www.ogsteam.fr/
**/

if (!defined('IN_SPYOGAME'))
{ 
	die("Hacking attempt");
}

require_once("views/page_header.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$ta_resultat = $db->sql_query('SELECT `version`, `root` FROM `'.TABLE_MOD.'` WHERE `action`="'.$pub_action.'" AND `active`= 1');

if(!$db->sql_numrows($ta_resultat))
{
	die('Mod désactivé !');
}

list ( $mod_version, $mod_root ) = $db->sql_fetch_row($ta_resultat);

$s_html = ''; 
$user_empire = user_get_empire($user_data['user_id']);
$user_building = $user_empire["building"];
$user_technology = $user_empire["technology"];
$vitesse = $server_config['speed_uni'];
$s_html .= '<script type="text/javascript" src="mod/'.$mod_root.'/formule.js"></script>';
$s_html .= '<script type="text/javascript">';
$s_html .= 'var vitesse_univers = '.$vitesse.';';

$i = 0;
foreach($user_building as $ta_une_planete)
{
	if ($ta_une_planete['planet_name'] <> '')
	{
		$s_html .= "batimentsOGSpy[".$i."]= new Array('".
				$ta_une_planete['planet_name']."','".
				$ta_une_planete['M']."','".
				$ta_une_planete['C']."','".
				$ta_une_planete['D']."','".
				$ta_une_planete['CES']."','".
				$ta_une_planete['CEF']."','".
				$ta_une_planete['UdR']."','".
				$ta_une_planete['UdN']."','".
				$ta_une_planete['CSp']."','".
				$ta_une_planete['HM']."','".
				$ta_une_planete['HC']."','".
				$ta_une_planete['HD']."','".
				$ta_une_planete['Lab']."','".
				$ta_une_planete['Silo']."','".
				$ta_une_planete['Ter']."','".
				$ta_une_planete['BaLu']."','".
				$ta_une_planete['Pha']."','".
				$ta_une_planete['PoSa']."','".
				$ta_une_planete['DdR']."');";
		if($i == 0)
		{
			foreach($ta_une_planete as $key => $value)
			{
				$ta_premiere_planete[$key] = $value;
			}
		}
		$i++;		
	}
}

$s_html .= "technologiesOGSpy = new Array('".
		$user_technology['Esp']."','".
		$user_technology['Ordi']."','".
		$user_technology['Armes']."','".
		$user_technology['Bouclier']."','".
		$user_technology['Protection']."','".
		$user_technology['NRJ']."','".
		$user_technology['Hyp']."','".
		$user_technology['RC']."','".
		$user_technology['RI']."','".
		$user_technology['PH']."','".
		$user_technology['Laser']."','".
		$user_technology['Ions']."','".
		$user_technology['Plasma']."','".
		$user_technology['RRI']."','".
		$user_technology['Astrophysique']."','".
		$user_technology['Graviton']."');";

$s_html .= '</script>';

$s_html .= 		'<input type="hidden" value="mod/'.$mod_root.'/" id="root" />';
$s_html .= 		'<table>';
$s_html .= 			'<tr>';
$s_html .= 				'<td class="c" style="text-align:center">Planète de développement :</td><th>';
$s_html .= 					'<select id="planete" onchange="javascript:chargement(this.options[this.selectedIndex].value);">';

$i = 0;
foreach($user_building as $ta_une_planete)
{
	if($ta_une_planete['planet_name'] <> '')
	{
		$s_html .= 	'<option value="'.$i.'">';
		$s_html .= 		$ta_une_planete['planet_name'].' ['.$ta_une_planete['coordinates'].']';
		$s_html .= 	'</option>';
	}
	$i++;
}
$s_html .= 					'</select>';
$s_html .= 					'</th>';
$s_html .= 			'</tr></table>';

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_technologies\', this)" /> Vos technologies</legend>';
$s_html .= 		'<div id="deroulant_technologies" style="display:block;">';
$s_html .= 		'<table>';
$s_html .= 			'<tr>';
$s_html .= 				'<td class="c" style="text-align:center">Laboratoires de recherche :</td><th><input type="text" id="labopm" size="2" maxlength="2" value="'.$ta_premiere_planete['Lab'].'" onkeyup="javascript:laboEqui()"></th>';
$s_html .= 				'<td class="c" style="text-align:center">Chantier spatial :</td><th><input type="text" id="chantier" size="2" maxlength="2" value="'.$ta_premiere_planete['CSp'].'" onkeyup="javascript:rafraichiChantier()"></th>';
$s_html .= 			'</tr><tr>';
$s_html .= 				'<td class="c" style="text-align:center">Usine de robot :</td><th><input type="text" id="robot" size="2" maxlength="2" value="'.$ta_premiere_planete['UdR'].'" onkeyup="javascript:rafraichiRobot()"></th>';
$s_html .= 				'<td class="c" style="text-align:center">Usine de nanites :</td><th><input type="text" id="nanite" size="2" maxlength="2" value="'.$ta_premiere_planete['UdN'].'" onkeyup="javascript:rafraichiRobot();rafraichiChantier()"></th>';
$s_html .= 			'</tr><tr>';
$s_html .= 				'<td class="c" style="text-align:center">Réseau de recherche intergalactique :</td><th><input type="text" id="reseau" size="2" maxlength="2" value="'.$user_technology['RRI'].'" onkeyup="javascript:laboEqui()"></th>';
$i = 0;
$n_laboratoire_equivalent = 0;
$t_labo = Array();

foreach($user_building as $ta_une_planete)
{
	if ($ta_une_planete['planet_name'] <> '')
	{
		$t_labo[$i] = $ta_une_planete['Lab'];
		$i++;
	}
}

arsort($t_labo);

$i = 0;
foreach($t_labo as $n_un_labo)
{
	if($n_un_labo >= 7)
	{	
		if(($user_technology['RRI'] + 1) > $i)
		{
			$n_laboratoire_equivalent += intval($n_un_labo);
			$i++;
		}
	}
}

$s_html .= 				'<td class="c" style="text-align:center">Laboratoire équivalent :</td><th><input type="text" id="laboequi" size="4" maxlength="2" readonly value="'.$n_laboratoire_equivalent.'"></th>';
$s_html .= 			'</tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_total\', this)" /> Total</legend>';
$s_html .= 		'<div id="deroulant_total" style="display:block;">';
$s_html .= 		'<table>';
$s_html .= 			'<tr><td class="c" style="text-align:center">';
$s_html .= 				'Métal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Cristal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Deutérium requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Durée de construction</td>';
$s_html .= 				'</tr>';
$s_html .= 			'<tr><th>';
$s_html .= 				'<input type="hidden" id="total_metal" value="0" /><input type="text" id="total_metal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="total_cristal" value="0" /><input type="text" id="total_cristal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="total_deuterium" value="0" /><input type="text" id="total_deuterium_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="text" id="total_temps" size="15" readonly value="-">';
$s_html .= 				'<input type="hidden" id="total_sec" value="0"></th>';
$s_html .= 			'</tr><tr><td colspan="7" class="c">';
$s_html .= 				'un total de <span id="total_ressources" style="color:#FF0080;font-weight:bold;">';
$s_html .= 				'0</span> ressources, soit <span id="total_pt" style="color:#0080FF;font-weight:bold;">';
$s_html .= 				'0</span> PT ou <span id="total_gt" style="color:#80FF00;font-weight:bold;">';
$s_html .= 				'0</span> GT';
$s_html .= 			'</td></tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';

// Bâtiments

$s_html_batiments = '';

// 0 = parametre ; 1 = lettre ; 2 = code ; 3 = nom
$ta_batiments[0] = Array ('60, 15, 0, 1.5', 'M', 'mine_metal', 'Mine de métal');
$ta_batiments[1] = Array ('48,24,0,1.6', 'C', 'mine_cristal', 'Mine de cristal');
$ta_batiments[2] = Array ('225,75,0,1.5', 'D', 'synthetiseur_deuterium', 'Synthétiseur de deutérium');
$ta_batiments[3] = Array ('75,30,0,1.5', 'CES', 'centrale_solaire', 'Centrale électrique solaire');
$ta_batiments[4] = Array ('900,360,180,1.8', 'CEF', 'reacteur_fusion', 'Centrale électrique de fusion');
$ta_batiments[5] = Array ('1000,0,0,2', 'HM', 'hangar_metal', 'Hangar de métal');
$ta_batiments[6] = Array ('1000,500,0,2', 'HC', 'hangar_cristal', 'Hangar de cristal');
$ta_batiments[7] = Array ('1000,1000,0,2', 'HD', 'reservoir_deuterium', 'Réservoir de deutérium');
$ta_batiments[8] = Array ('400,120,200,2', 'UdR', 'usine_robots', 'Usine de robots');
$ta_batiments[9] = Array ('1000000,500000,100000,2', 'UdN', 'usine_nanites', 'Usine de nanites');
$ta_batiments[10] = Array ('400,200,100,2', 'CSp', 'chantier_spatial', 'Chantier spatial');
$ta_batiments[11] = Array ('200,400,200,2', 'Lab', 'laboratoire', 'Laboratoire de recherche');
$ta_batiments[12] = Array ('20000,20000,1000,2', 'Silo', 'silo_missiles', 'Silo de missiles');
$ta_batiments[13] = Array ('1000,50000,100000,2', 'Ter', 'terraformeur', 'Terraformeur');

for($i = 0 ; $i < count($ta_batiments) ; $i++)
{
	$s_html_batiments .= '<tr>';
	$s_html_batiments .= 	'<th>'.$ta_batiments[$i][3].'</th>';
	$s_html_batiments .= 	'<th><input type="text" id="'.$ta_batiments[$i][2].'_actuel" size="2" maxlength="2" onkeyup="javascript:batiment(\''.$ta_batiments[$i][2].'\', '.$ta_batiments[$i][0].');" value="'.$ta_premiere_planete[$ta_batiments[$i][1]].'" /></th>';
	$s_html_batiments .= 	'<th><input type="text" id="'.$ta_batiments[$i][2].'_voulu" size="2" maxlength="2" onkeyup="javascript:batiment(\''.$ta_batiments[$i][2].'\', '.$ta_batiments[$i][0].')" value="'.$ta_premiere_planete[$ta_batiments[$i][1]].'" /></th>';
	$s_html_batiments .= 	'<th><input type="hidden" id="'.$ta_batiments[$i][2].'_metal" value="0" /><input type="text" id="'.$ta_batiments[$i][2].'_metal_resultat" size="15" readonly value="0" /></th>';
	$s_html_batiments .= 	'<th><input type="hidden" id="'.$ta_batiments[$i][2].'_cristal" value="0" /><input type="text" id="'.$ta_batiments[$i][2].'_cristal_resultat" size="15" readonly value="0" /></th>';
	$s_html_batiments .= 	'<th><input type="hidden" id="'.$ta_batiments[$i][2].'_deuterium" value="0" /><input type="text" id="'.$ta_batiments[$i][2].'_deuterium_resultat" size="15" readonly value="0" /></th>';
	$s_html_batiments .= 	'<th><input type="text" id="'.$ta_batiments[$i][2].'_temps" size="15" readonly value="-" />';
	$s_html_batiments .= 	'<input type="hidden" id="'.$ta_batiments[$i][2].'_sec" value="0" /></th>';
	$s_html_batiments .= '</tr>';
}

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_batiment\', this)" /> Bâtiments</legend>';
$s_html .= 		'<div id="deroulant_batiment" style="display:block;">';
$s_html .= 		'<table>';
$s_html .= 			'<tr><td class="c" style="text-align:center">';
$s_html .= 				'Nom</td><td class="c" style="text-align:center">';
$s_html .= 				'Niveau actuel</td><td class="c" style="text-align:center">';
$s_html .= 				'Niveau voulu</td><td class="c" style="text-align:center">';
$s_html .= 				'Métal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Cristal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Deutérium requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Durée de construction</td>';
$s_html .= 			'</tr>';
$s_html .= 			$s_html_batiments;
$s_html .= 			'<tr><td class="c" style="text-align:center" colspan="3">';
$s_html .= 				'Total</td><th>';
$s_html .= 				'<input type="hidden" id="batiments_metal" value="0"><input type="text" id="batiments_metal_resultat" size="15" readonly value="0"></th><th>';
$s_html .= 				'<input type="hidden" id="batiments_cristal" value="0"><input type="text" id="batiments_cristal_resultat" size="15" readonly value="0"></th><th>';
$s_html .= 				'<input type="hidden" id="batiments_deuterium" value="0"><input type="text" id="batiments_deuterium_resultat" size="15" readonly value="0"></th><th>';
$s_html .= 				'<input type="text" id="batiments_temps" size="15" readonly value="-">';
$s_html .= 				'<input type="hidden" id="batiments_sec" value="0"></th>';
$s_html .= 			'</tr><tr><td colspan="7" class="c">';
$s_html .= 				'Un total de <span id="batiments_ressources" style="color:#FF0080;font-weight:bold;">';
$s_html .= 				'0</span> ressources, soit <span id="batiments_pt" style="color:#0080FF;font-weight:bold;">';
$s_html .= 				'0</span> PT ou <span id="batiments_gt" style="color:#80FF00;font-weight:bold;">';
$s_html .= 				'0</span> GT';
$s_html .= 			'</td></tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';

// Bâtiments spéciaux

$s_html_batiments_speciaux = '';

// 0 = parametre ; 1 = lettre ; 2 = code ; 3 = nom
$ta_batiments_speciaux[0] = Array ('20000,40000,20000,2', 'BaLu', 'base_lunaire', 'Base lunaire');
$ta_batiments_speciaux[1] = Array ('20000,40000,20000,2', 'Pha', 'phalange_capteurs', 'Phalange de capteur');
$ta_batiments_speciaux[2] = Array ('2000000,4000000,2000000,2', 'PoSa', 'porte_saut_spatial', 'Porte de saut spatial');
$ta_batiments_speciaux[3] = Array ('20000,40000,0,2', 'DdR', 'depot_ravitaillement', 'Dépôt de ravitaillement');

for($i = 0 ; $i < count($ta_batiments_speciaux) ; $i++)
{
	$s_html_batiments_speciaux .= 			'<tr>';
	$s_html_batiments_speciaux .= 				'<th>'.$ta_batiments_speciaux[$i][3].'</th>';
	$s_html_batiments_speciaux .= 				'<th><input type="text" id="'.$ta_batiments_speciaux[$i][2].'_actuel" size="2" maxlength="2" onkeyup="javascript:batimentSpeciaux(\''.$ta_batiments_speciaux[$i][2].'\', '.$ta_batiments_speciaux[$i][0].');" value="'.$ta_premiere_planete[$ta_batiments_speciaux[$i][1]].'" /></th>';
	$s_html_batiments_speciaux .= 				'<th><input type="text" id="'.$ta_batiments_speciaux[$i][2].'_voulu" size="2" maxlength="2" onkeyup="javascript:batimentSpeciaux(\''.$ta_batiments_speciaux[$i][2].'\', '.$ta_batiments_speciaux[$i][0].')" value="'.$ta_premiere_planete[$ta_batiments_speciaux[$i][1]].'" /></th>';
	$s_html_batiments_speciaux .= 				'<th><input type="hidden" id="'.$ta_batiments_speciaux[$i][2].'_metal" value="0" /><input type="text" id="'.$ta_batiments_speciaux[$i][2].'_metal_resultat" size="15" readonly value="0" /></th>';
	$s_html_batiments_speciaux .= 				'<th><input type="hidden" id="'.$ta_batiments_speciaux[$i][2].'_cristal" value="0" /><input type="text" id="'.$ta_batiments_speciaux[$i][2].'_cristal_resultat" size="15" readonly value="0" /></th>';
	$s_html_batiments_speciaux .= 				'<th><input type="hidden" id="'.$ta_batiments_speciaux[$i][2].'_deuterium" value="0" /><input type="text" id="'.$ta_batiments_speciaux[$i][2].'_deuterium_resultat" size="15" readonly value="0" /></th>';
	$s_html_batiments_speciaux .= 				'<th><input type="text" id="'.$ta_batiments_speciaux[$i][2].'_temps" size="15" readonly value="-" />';
	$s_html_batiments_speciaux .= 				'<input type="hidden" id="'.$ta_batiments_speciaux[$i][2].'_sec" value="0" /></th>';
	$s_html_batiments_speciaux .= 			'</tr>';
}

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_speciaux\', this)" /> Bâtiments spéciaux</legend>';
$s_html .= 		'<div id="deroulant_speciaux" style="display:block;">';
$s_html .=		'<table>';
$s_html .= 			'<tr><td class="c" style="text-align:center">';
$s_html .= 				'Nom</td><td class="c" style="text-align:center">';
$s_html .= 				'Niveau actuel</td><td class="c" style="text-align:center">';
$s_html .= 				'Niveau voulu</td><td class="c" style="text-align:center">';
$s_html .= 				'Métal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Cristal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Deutérium requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Durée de construction</td>';
$s_html .= 			'</tr>';
$s_html .=			$s_html_batiments_speciaux;
$s_html .= 			'<tr><td class="c" style="text-align:center" colspan="3">';
$s_html .= 				'Total</td><th>';
$s_html .= 				'<input type="hidden" id="batiments_speciaux_metal" value="0" /><input type="text" id="batiments_speciaux_metal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="batiments_speciaux_cristal" value="0" /><input type="text" id="batiments_speciaux_cristal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="batiments_speciaux_deuterium" value="0" /><input type="text" id="batiments_speciaux_deuterium_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="text" id="batiments_speciaux_temps" size="15" readonly value="-">';
$s_html .= 				'<input type="hidden" id="batiments_speciaux_sec" value="0"></th>';
$s_html .= 			'</tr>';
$s_html .= 			'<tr>';
$s_html .= 				'<td colspan="7" class="c">';
$s_html .= 					'Un total de <span id="batiments_speciaux_ressources" style="color:#FF0080;font-weight:bold;">';
$s_html .= 					'0</span> ressources, soit <span id="batiments_speciaux_pt" style="color:#0080FF;font-weight:bold;">';
$s_html .= 					'0</span> PT ou <span id="batiments_speciaux_gt" style="color:#80FF00;font-weight:bold;">';
$s_html .= 					'0</span> GT';
$s_html .= 				'</td>';
$s_html .= 			'</tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';

// Technologies

$s_html_technologie = '';

// 0 = parametre ; 1 = lettre ; 2 = code ; 3 = nom
$ta_technologies[0] = Array ('200,1000,200,2', 'Esp', 'espionnage', 'Technologie Espionnage');
$ta_technologies[1] = Array ('0,400,600,2', 'Ordi', 'ordinateur', 'Technologie Ordinateur');
$ta_technologies[2] = Array ('800,200,0,2', 'Armes', 'armes', 'Technologie Armes');
$ta_technologies[3] = Array ('200,600,0,2', 'Bouclier', 'bouclier', 'Technologie Bouclier');
$ta_technologies[4] = Array ('1000,0,0,2', 'Protection', 'protection_vaisseaux', 'Technologie Protection des vaisseaux spatiaux');
$ta_technologies[5] = Array ('0,800,400,2', 'NRJ', 'energie', 'Technologie énergie');
$ta_technologies[6] = Array ('0,4000,2000,2', 'Hyp', 'hyperespace', 'Technologie hyperespace');
$ta_technologies[7] = Array ('400,0,600,2', 'RC', 'reacteur_combustion', 'Réacteur à combustion');
$ta_technologies[8] = Array ('2000,4000,600,2', 'RI', 'reacteur_impulsion', 'Réacteur à impulsion');
$ta_technologies[9] = Array ('10000,20000,6000,2', 'PH', 'propulsion_hyperespace', 'Propulsion hyperespace');
$ta_technologies[10] = Array ('200,100,0,2', 'Laser', 'laser', 'Technologie Laser');
$ta_technologies[11] = Array ('1000,300,100,2', 'Ions', 'ion', 'Technologie Ions');
$ta_technologies[12] = Array ('2000,4000,1000,2', 'Plasma', 'plasma', 'Technologie Plasma');
$ta_technologies[13] = Array ('240000,400000,160000,2', 'RRI', 'reseau_recherche', 'Réseau de recherche intergalactique');
$ta_technologies[14] = Array ('4000,8000,4000,1.75', 'Astrophysique', 'expeditions', 'Astrophysique');

for($i = 0 ; $i < count($ta_technologies) ; $i++)
{
	$s_html_technologie .= '<tr>';
	$s_html_technologie .= 	'<th>'.$ta_technologies[$i][3].'</th>';
	$s_html_technologie .= 	'<th><input type="text" id="'.$ta_technologies[$i][2].'_actuel" size="2" maxlength="2" onkeyup="javascript:technologie(\''.$ta_technologies[$i][2].'\', '.$ta_technologies[$i][0].');" value="'.$user_technology[$ta_technologies[$i][1]].'" /></th>';	
	$s_html_technologie .= 	'<th><input type="text" id="'.$ta_technologies[$i][2].'_voulu" size="2" maxlength="2" onkeyup="javascript:technologie(\''.$ta_technologies[$i][2].'\', '.$ta_technologies[$i][0].')" value="'.$user_technology[$ta_technologies[$i][1]].'" /></th>';
	$s_html_technologie .= 	'<th><input type="hidden" id="'.$ta_technologies[$i][2].'_metal" value="0" /><input type="text" id="'.$ta_technologies[$i][2].'_metal_resultat" size="15" readonly value="0" /></th>';
	$s_html_technologie .= 	'<th><input type="hidden" id="'.$ta_technologies[$i][2].'_cristal" value="0" /><input type="text" id="'.$ta_technologies[$i][2].'_cristal_resultat" size="15" readonly value="0" /></th>';
	$s_html_technologie .= 	'<th><input type="hidden" id="'.$ta_technologies[$i][2].'_deuterium" value="0" /><input type="text" id="'.$ta_technologies[$i][2].'_deuterium_resultat" size="15" readonly value="0" /></th>';
	$s_html_technologie .= 	'<th><input type="text" id="'.$ta_technologies[$i][2].'_temps" size="15" readonly value="-" />';
	$s_html_technologie .= 	'<input type="hidden" id="'.$ta_technologies[$i][2].'_sec" value="0" /></th>';
	$s_html_technologie .= '</tr>';
}

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_technologie\', this)" /> Technologies</legend>';
$s_html .= 		'<div id="deroulant_technologie" style="display:block;">';
$s_html .= 		'<table>';
$s_html .= 			'<tr><td class="c" style="text-align:center">';
$s_html .= 				'Nom</td><td class="c" style="text-align:center">';
$s_html .= 				'Niveau actuel</td><td class="c" style="text-align:center">';
$s_html .= 				'Niveau voulu</td><td class="c" style="text-align:center">';
$s_html .= 				'Métal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Cristal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Deutérium requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Durée de construction</td>';
$s_html .= 			'</tr>';
$s_html .=			$s_html_technologie;
$s_html .= 			'<tr><th>';
$s_html .= 				'Technologie Graviton</th><th>';
$s_html .= 				'<input type="text" id="graviton_actuel" size="2" maxlength="2" onkeyup="javascript:graviton();" value="'.$user_technology['Graviton'].'"></th><th>';
$s_html .= 				'<input type="text" id="graviton_voulu" size="2" maxlength="2" onkeyup="javascript:graviton();" value="'.$user_technology['Graviton'].'"></th><th colspan="3">';
$s_html .= 				'Energie : <input type="text" id="graviton" size="15" readonly value="0"></th><th>';
$s_html .= 				'Instantané</th>';
$s_html .= 			'</tr><tr><td class="c" style="text-align:center" colspan="3">';
$s_html .= 				'Total</td><th>';
$s_html .= 				'<input type="hidden" id="technologies_metal" value="0" /><input type="text" id="technologies_metal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="technologies_cristal" value="0" /><input type="text" id="technologies_cristal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="technologies_deuterium" value="0" /><input type="text" id="technologies_deuterium_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="text" id="technologies_temps" size="15" readonly value="-">';
$s_html .= 				'<input type="hidden" id="technologies_sec" value="0"></th>';
$s_html .= 			'</tr><tr><td colspan="7" class="c">';
$s_html .= 				'Un total de <span id="technologies_ressources" style="color:#FF0080;font-weight:bold;">';
$s_html .= 				'0</span> ressources, soit <span id="technologies_pt" style="color:#0080FF;font-weight:bold;">';
$s_html .= 				'0</span> PT ou <span id="technologies_gt" style="color:#80FF00;font-weight:bold;">';
$s_html .= 				'0</span> GT';
$s_html .= 			'</td></tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';

// Vaisseaux

$s_html_vaisseaux = '';

// 0 = parametre ; 1 = code ; 2 = nom
$ta_vaisseaux[0] = Array ('2000,2000,0', 'pt', 'Petit transporteur');
$ta_vaisseaux[1] = Array ('6000,6000,0', 'gt', 'Grand transporteur');
$ta_vaisseaux[2] = Array ('3000,1000,0', 'cle', 'Chasseur léger');
$ta_vaisseaux[3] = Array ('6000,4000,0', 'clo', 'Chasseur lourd');
$ta_vaisseaux[4] = Array ('20000,7000,2000', 'cr', 'Croiseur');
$ta_vaisseaux[5] = Array ('45000,15000,0', 'vb', 'Vaisseau de bataille');
$ta_vaisseaux[6] = Array ('30000,40000,15000', 'traq', 'Traqueur');
$ta_vaisseaux[7] = Array ('50000,25000,15000', 'bb', 'Bombardier');
$ta_vaisseaux[8] = Array ('60000,50000,15000', 'dest', 'Destructeur');
$ta_vaisseaux[9] = Array ('5000000,4000000,1000000', 'edlm', 'Étoile de la mort');
$ta_vaisseaux[10] = Array ('10000,6000,2000', 'recycleur', 'Recycleur');
$ta_vaisseaux[11] = Array ('10000,20000,10000', 'vc', 'Vaisseau de colonisation');
$ta_vaisseaux[12] = Array ('0,1000,0', 'sonde', 'Sonde d`espionnage	');
$ta_vaisseaux[13] = Array ('0,2000,500', 'satellite', 'Satellite solaire');

for($i = 0 ; $i < count($ta_vaisseaux) ; $i++)
{
	$s_html_vaisseaux .= '<tr>';
	$s_html_vaisseaux .= 	'<th>'.$ta_vaisseaux[$i][2].'</th>';
	$s_html_vaisseaux .= 	'<th><input type="text" id="'.$ta_vaisseaux[$i][1].'_voulu" onkeyup="javascript:vaisseaux(\''.$ta_vaisseaux[$i][1].'\', '.$ta_vaisseaux[$i][0].')" value="0" /></th>';
	$s_html_vaisseaux .= 	'<th><input type="hidden" id="'.$ta_vaisseaux[$i][1].'_metal" value="0" /><input type="text" id="'.$ta_vaisseaux[$i][1].'_metal_resultat" size="15" readonly value="0" /></th>';
	$s_html_vaisseaux .= 	'<th><input type="hidden" id="'.$ta_vaisseaux[$i][1].'_cristal" value="0" /><input type="text" id="'.$ta_vaisseaux[$i][1].'_cristal_resultat" size="15" readonly value="0" /></th>';
	$s_html_vaisseaux .= 	'<th><input type="hidden" id="'.$ta_vaisseaux[$i][1].'_deuterium" value="0" /><input type="text" id="'.$ta_vaisseaux[$i][1].'_deuterium_resultat" size="15" readonly value="0" /></th>';
	$s_html_vaisseaux .= 	'<th><input type="text" id="'.$ta_vaisseaux[$i][1].'_temps" size="15" readonly value="-" />';
	$s_html_vaisseaux .= 	'<input type="hidden" id="'.$ta_vaisseaux[$i][1].'_sec" value="0" /></th>';
	$s_html_vaisseaux .= '</tr>';
}

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_vaisseaux\', this)" /> Vaisseaux</legend>';
$s_html .= 		'<div id="deroulant_vaisseaux" style="display:block;">';
$s_html .= 		'<table>';
$s_html .= 			'<tr><td class="c" style="text-align:center">';
$s_html .= 				'Nom</td><td class="c" style="text-align:center">';
$s_html .= 				'Quantité voulue</td><td class="c" style="text-align:center">';
$s_html .= 				'Métal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Cristal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Deutérium requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Durée de construction</td>';
$s_html .= 			'</tr>';
$s_html .=			$s_html_vaisseaux;
$s_html .= 			'<tr><td class="c" style="text-align:center" colspan="2">';
$s_html .= 				'Total</td><th>';
$s_html .= 				'<input type="hidden" id="vaisseaux_metal" value="0" /><input type="text" id="vaisseaux_metal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="vaisseaux_cristal" value="0" /><input type="text" id="vaisseaux_cristal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="vaisseaux_deuterium" value="0" /><input type="text" id="vaisseaux_deuterium_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="text" id="vaisseaux_temps" size="15" readonly value="-">';
$s_html .= 				'<input type="hidden" id="vaisseaux_sec" value="0"></th>';
$s_html .= 			'</tr><tr><td colspan="6" class="c">';
$s_html .= 				'Un total de <span id="vaisseaux_ressources" style="color:#FF0080;font-weight:bold;">';
$s_html .= 				'0</span> ressources, soit <span id="vaisseaux_pt" style="color:#0080FF;font-weight:bold;">';
$s_html .= 				'0</span> PT ou <span id="vaisseaux_gt" style="color:#80FF00;font-weight:bold;">';
$s_html .= 				'0</span> GT';
$s_html .= 			'</td></tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';

// Défense

$s_html_defense = '';

// 0 = parametre ; 1 = code ; 2 = nom
$ta_defense[0] = Array ('2000,0,0', 'lm', 'Lanceur de missiles');
$ta_defense[1] = Array ('1500,500,0', 'ale', 'Artillerie laser légère');
$ta_defense[2] = Array ('6000,2000,0', 'alo', 'Artillerie laser lourde');
$ta_defense[3] = Array ('2000,6000,0', 'canon_ion', 'Artillerie à ions');
$ta_defense[4] = Array ('20000,15000,2000', 'gauss', 'Canon de Gauss');
$ta_defense[5] = Array ('50000,50000,30000', 'lp', 'Lanceur de plasma');
$ta_defense[6] = Array ('10000,10000,0', 'pb', 'Petit bouclier');
$ta_defense[7] = Array ('50000,50000,30000', 'gb', 'Grand bouclier');
$ta_defense[8] = Array ('8000,0,2000', 'min', 'Missile d`interception');
$ta_defense[9] = Array ('12500,2500,10000', 'mip', 'Missile interplanétaire');

for($i = 0 ; $i < count($ta_defense) ; $i++)
{
	$s_html_defense .= '<tr>';
	$s_html_defense .= 	'<th>'.$ta_defense[$i][2].'</th>';
	$s_html_defense .= 	'<th><input type="text" id="'.$ta_defense[$i][1].'_voulu" onkeyup="javascript:defense(\''.$ta_defense[$i][1].'\', '.$ta_defense[$i][0].')" value="0" /></th>';
	$s_html_defense .= 	'<th><input type="hidden" id="'.$ta_defense[$i][1].'_metal" value="0" /><input type="text" id="'.$ta_defense[$i][1].'_metal_resultat" size="15" readonly value="0" /></th>';
	$s_html_defense .= 	'<th><input type="hidden" id="'.$ta_defense[$i][1].'_cristal" value="0" /><input type="text" id="'.$ta_defense[$i][1].'_cristal_resultat" size="15" readonly value="0" /></th>';
	$s_html_defense .= 	'<th><input type="hidden" id="'.$ta_defense[$i][1].'_deuterium" value="0" /><input type="text" id="'.$ta_defense[$i][1].'_deuterium_resultat" size="15" readonly value="0" /></th>';
	$s_html_defense .= 	'<th><input type="text" id="'.$ta_defense[$i][1].'_temps" size="15" readonly value="-" />';
	$s_html_defense .= 	'<input type="hidden" id="'.$ta_defense[$i][1].'_sec" value="0" /></th>';
	$s_html_defense .= '</tr>';
}

$s_html .= '<fieldset>';
$s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_defense\', this)" /> Défense</legend>';
$s_html .= 		'<div id="deroulant_defense" style="display:block;">';
$s_html .= 		'<table>';
$s_html .= 			'<tr><td class="c" style="text-align:center">';
$s_html .= 				'Nom</td><td class="c" style="text-align:center">';
$s_html .= 				'Quantité voulue</td><td class="c" style="text-align:center">';
$s_html .= 				'Métal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Cristal requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Deutérium requis</td><td class="c" style="text-align:center">';
$s_html .= 				'Durée de construction</td>';
$s_html .= 			'</tr>';
$s_html .=			$s_html_defense;
$s_html .= 			'<tr><td class="c" style="text-align:center" colspan="2">';
$s_html .= 				'TOTAL</td><th>';
$s_html .= 				'<input type="hidden" id="defenses_metal" value="0" /><input type="text" id="defenses_metal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="defenses_cristal" value="0" /><input type="text" id="defenses_cristal_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="hidden" id="defenses_deuterium" value="0" /><input type="text" id="defenses_deuterium_resultat" size="15" readonly value="0" /></th><th>';
$s_html .= 				'<input type="text" id="defenses_temps" size="15" readonly value="-">';
$s_html .= 				'<input type="hidden" id="defenses_sec" value="0"></th>';
$s_html .= 			'</tr><tr><td colspan="6" class="c">';
$s_html .= 				'Un total de <span id="defenses_ressources" style="color:#FF0080;font-weight:bold;">';
$s_html .= 				'0</span> ressources, soit <span id="defenses_pt" style="color:#0080FF;font-weight:bold;">';
$s_html .= 				'0</span> PT ou <span id="defenses_gt" style="color:#80FF00;font-weight:bold;">';
$s_html .= 				'0</span> GT';
$s_html .= 			'</td></tr>';
$s_html .= 		'</table>';
$s_html .= 		'</div>';
$s_html .= '</fieldset>';



// $s_html .= '<fieldset>';
// $s_html .= 		'<legend><img src="mod/'.$mod_root.'/moins.png" style="cursor:pointer;" alt="moins" onclick="javascript:f_inverse(\'deroulant_gestion\', this)" /> Gestion</legend>';
// $s_html .= 		'<div id="deroulant_gestion" style="display:block;">';
// $s_html .= 		'<div>';
// $s_html .= 			'<input type="submit" value="Sauvegarder les données" onclick="javascript:sauvegarde();" />';
// $s_html .= 			'<input type="submit" value="Restaurer les données" onclick="javascript:restaure();" />';
// $s_html .= 			'<input type="submit" value="Changelog" onclick="javascript:inverse(\'changelog\');" />';
// $s_html .= 			'<input type="submit" value="Reset" onclick="javascript:f_reset_donnees();" />';
// $s_html .= 		'</div>';
// $s_html .= 		'<div id="changelog" style="display:none;">';
// $s_html .= 			'<h2>Changelog</h2>';
// $s_html .= 			'<p>02/09/2012</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v1.1.0';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Compatibilité OGSpy 3.1.0</li>';
// $s_html .= 					'<li>Optimisation du code</li>';
// $s_html .= 					'<li>Simplification du fonctionnement</li>';
// $s_html .= 					'<li>Séparation des milliers avec un espace</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>18/04/2008</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.5';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Ajout du calcul des transports</li>';
// $s_html .= 					'<li>Ajout du script de désintallation</li>';
// $s_html .= 					'<li>Controle de sécurité pour éviter l\'erreur de "Duplicate Entry"</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>18/04/2008</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.4d';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Fix d\'un bug à l\'installation</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>16/04/2008</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.4c';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Ajout de la technologie expéditions</li>';
// $s_html .= 					'<li>Modification du fichier install</li>';
// $s_html .= 					'<li>Correction du chemin pour atteindre formule.js</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>04/03/2007</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.4';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Ajout du traqueur</li>';
// $s_html .= 					'<li>Correction du bug d\'affichage qui ne permmetait pas de voir les ressources</li>';
// $s_html .= 					'<li>Modification du prix du traqueur</li>';
// $s_html .= 					'<li>Installation des Install/Update qui récupére le n° de version dans le fichier version.txt</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>09/08/2006</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.3';
// $s_html .= 					'<ul type="disc">';
// $s_html .= 					'<li>Correction du problème des prix du terraformeur (merci ben_12)</li>';
// $s_html .= 					'<li>Correction du non-rafraichissement des temps si modifications du niveau de l\'usine de robots et de nanites ou du chantier spatial</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>09/07/2006</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.2';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Correction d\'un bug empêchant le calcul des technologies</li>';
// $s_html .= 					'<li>Correction d\'un problème de calcul de l\'énergie nécessaire au graviton (merci Corwin)</li>';
// $s_html .= 					'<li>Ajout de la fonction reset</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 			'<p>08/07/2006</p>';
// $s_html .= 			'<ol style="list-style-type: none;">';
// $s_html .= 				'<li>v0.1';
// $s_html .= 				'<ul type="disc">';
// $s_html .= 					'<li>Sortie d\'OGSCalc en mod OGSpy</li>';
// $s_html .= 				'</ul>';
// $s_html .= 			'</ol>';
// $s_html .= 		'</div>';
// $s_html .= 		'</div>';
// $s_html .= '</fieldset>';

$s_html .= '<div style="font-size:10px;width:400px;text-align:center;background-image:url(\'skin/OGSpy_skin/tableaux/th.png\');background-repeat:repeat;">OGSCalc ('.$mod_version.')';
$s_html .= 		'<br>Développé par <a href="http://forum.ogsteam.fr/index.php?action=emailuser;sa=email;uid=1">Aéris</a>';
$s_html .= 		'<br>Mise à jour par <a href="mailto:contact@epe-production.org?subject=ogscalc">xaviernuma</a> 2016';
$s_html .= '</div>';

echo $s_html;

require_once("views/page_tail.php");

?>
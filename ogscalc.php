<?php

/**
* ogscalc.php - Programme principal
* @package Calculatrice universelle
* @author Aeris
* @update xaviernuma - 2016
* @update Choubakawa - 2018
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

	require_once( "mod/".$mod_root."/lang/fr/lang.php" );

	/*INIT VALUES*/
	$user_empire = user_get_empire($user_data['user_id']);
	$user_building = $user_empire["building"];
	$user_defence = $user_empire["defence"];
	$user_technology = $user_empire["technology"];
	$user_production = user_empire_production($user_empire,$user_data);

	if (!isset($pub_view) || $pub_view == "") $view = "planets";
	elseif ($pub_view == "planets" || $pub_view == "moons") $view = $pub_view;
	else $view = "planets";
	$start = $view == "planets" ? 101 : 201;

	$nb_planete = find_nb_planete_user($user_data['user_id']);
	$name = $coordinates = $fields = $temperature_min = $temperature_max = $satellite = "";
    
    $prodMetal = 0;
    $prodCrystal = 0;
    $prodDeut = 0;

    for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
        $prodMetal += $user_production['reel'][$i]['M'];
        $prodCrystal += $user_production['reel'][$i]['C'];
        $prodDeut += $user_production['reel'][$i]['D'];
    }
?>
	<!-- VIEW  -->
	<style>
		<?php include( "mod/".$mod_root."/css/".$mod_root.".css" ); ?>
	</style>
    <table width="100%">
        <tr>
            <?php

            $colspan = (($nb_planete + 1)*2) / 2;
            $colspan_planete = floor($colspan);
            $colspan_lune = ceil($colspan);

            if ($view == "planets") {
                echo "<th colspan='$colspan_planete'><a>".$lang['HOME_EMPIRE_PLANET']."</a></th>";
                echo "<td class='c' align='center' colspan='$colspan_lune' onClick=\"window.location = 'index.php?action=ogscalc&amp;view=moons';\"><a style='cursor:pointer'><font color='lime'>".$lang['HOME_EMPIRE_MOON']."</font></a></td>";
            } else {
                echo "<td class='c' align='center' colspan='$colspan_planete' onClick=\"window.location = 'index.php?action=ogscalc&amp;view=planets';\"><a style='cursor:pointer'><font color='lime'>".$lang['HOME_EMPIRE_PLANET']."</font></a></td>";
                echo "<th colspan='$colspan_lune'><a>".$lang['HOME_EMPIRE_MOON']."</a></th>";
            }
            ?>
        </tr>

        <?php

        // verification de compte de planete/lune avec la technologie astro
        $astro = astro_max_planete($user_technology['Astrophysique']);

        if (((find_nb_planete_user($user_data['user_id']) > $astro) || (find_nb_moon_user($user_data['user_id']) > $astro)) && ($user_technology != false)) {
            echo '<tr>';
            echo '<td class="c" colspan="' . ($nb_planete < 10 ? '10' : $nb_planete + 1) . '">';
            echo $lang['HOME_EMPIRE_ERROR'].' ';
            echo (find_nb_planete_user($user_data['user_id']) > $astro) ? $lang['HOME_EMPIRE_ERROR_PLANET'].'<br>' : '';
            echo (find_nb_moon_user($user_data['user_id']) > $astro) ? $lang['HOME_EMPIRE_ERROR_MOON'].'<br>' : '';
            echo '</td>';
            echo '</tr>';
        }

        ?>


        <tr>
            <td class="c" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php echo($lang['OGSCALC_RESOURCES']); ?></td>
        </tr>          
        <tr>
            <th></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_METAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_CRYSTAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_DEUT']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_RESOURCES_RATE']); ?></a></th><th colspan="2"><input type="number" id="metal_rate" value="3" min="1" max="9"> / <input type="number" id="crystal_rate" value="2" min="1" max="9"> / 1</th>
        </tr>           
        <tr>
            <th ><a><?php echo($lang['HOME_SIMU_PRODUCTION']); ?></a></th><th id="metal_prod" colspan="2"><?php echo(number_format($prodMetal, 0, ",", ".")); ?></th><th id="crystal_prod" colspan="2"><?php echo(number_format($prodCrystal, 0, ",", ".")); ?></th><th id="deut_prod" colspan="2"><?php echo(number_format($prodDeut, 0, ",", ".")); ?></th><th id="convert_prod" colspan="2"></th>
        </tr>           
        <tr>
            <th ><a><?php echo($lang['OGSCALC_RESOURCES_ACCOUNT']); ?></a></th><th colspan="2"><input type="number" class="inputOgscalc" id="metal_resource" value="0" min="0" max="999999999999"></th><th colspan="2"><input type="number" class="inputOgscalc" id="crystal_resource" value="0" min="0" max="999999999999"></th><th colspan="2"><input type="number" class="inputOgscalc" id="deut_resource" value="0" min="0" max="999999999999"></th><th id="convert_resource" colspan="2"></th>
        </tr> 
        <tr>
            <td class="c" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php echo($lang['HOME_EMPIRE_SUMMARY']); ?></td>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_NAME']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $name = $user_building[$i]["planet_name"];
                if ($name == "") $name = "&nbsp;";

                echo "\t" . "<th colspan='2'><a>" . $name . "</a></th>" . "\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_COORD']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $coordinates = $user_building[$i]["coordinates"];
                if ($coordinates == "" || ($user_building[$i]["planet_name"] == "" && $view == "moons")) $coordinates = "&nbsp;";
                else $coordinates = "[" . $coordinates . "]";

                echo "\t" . "<th colspan='2'>" . $coordinates . "</th>" . "\n";
            }
            if($view == "planets") {
            ?>
        </tr>
        <tr>
            <td class="c_batiments" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>">
                <?php echo($lang['HOME_EMPIRE_BUILDINGS']); ?>
            </td>
        </tr>
        <tr>
        	<th></th>
        	 <?php        	 
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
            	 echo "\t" . "<th>".$lang['OGSCALC_CURRENT']."</th><th>".$lang['OGSCALC_PROJECT']."</th>" . "\n";
            }
            ?>            
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_MINE_METAL']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $M = $user_building[$i]["M"];
                if ($M == "") $M = "&nbsp;";

                echo "\t" . "<th><input class='building current M' type='number' min='0' max='99' value='" . $M . "' id='m" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project M' type='number' min='0' max='99' value='" . $M . "' id='m" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_MINE_CRYSTAL']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $C = $user_building[$i]["C"];
                if ($C == "") $C = "&nbsp;";

                echo "\t" . "<th><input class='building current C' type='number' min='0' max='99' value='" . $C . "'' id='c" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project C' type='number' min='0' max='99' value='" . $C . "'' id='c" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_MINE_DEUT']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $D = $user_building[$i]["D"];
                if ($D == "") $D = "&nbsp;";

                echo "\t" . "<th><input class='building current D' type='number' min='0' max='99' value='" . $D . "'' id='d" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project D' type='number' min='0' max='99' value='" . $D . "'' id='d" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_SOLAR_PLANT']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $CES = $user_building[$i]["CES"];
                if ($CES == "") $CES = "&nbsp;";

                echo "\t" . "<th><input class='building current CES' type='number' min='0' max='99' value='" . $CES . "' id='ces" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project CES' type='number' min='0' max='99' value='" . $CES . "' id='ces" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_FUSION_PLANT']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $CEF = $user_building[$i]["CEF"];
                if ($CEF == "") $CEF = "&nbsp;";

                echo "\t" . "<th><input class='building current CEF' type='number' min='0' max='99' value='" . $CEF . "' id='cef" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project CEF' type='number' min='0' max='99' value='" . $CEF . "' id='cef" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            } // fin de si view="planets"
            else {
                echo '</tr><tr> <td class="c" colspan="';
                print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2;
                echo '">'.$lang['HOME_EMPIRE_BUILDINGS'].'</td>';
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_ROBOTS_PLANT']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $UdR = $user_building[$i]["UdR"];
                if ($UdR == "") $UdR = "&nbsp;";

                echo "\t" . "<th><input class='building current UDR' type='number' min='0' max='99' value='" . $UdR . "' id='udr" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project UDR' type='number' min='0' max='99' value='" . $UdR . "'id='udr" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            if($view == "planets") {
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_NANITES_PLANT']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $UdN = $user_building[$i]["UdN"];
                if ($UdN == "") $UdN = "&nbsp;";

                echo "\t" . "<th><input class='building current UDN' type='number' min='0' max='99' value='" . $UdN . "' id='udn" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project UDN' type='number' min='0' max='99' value='" . $UdN . "' id='udn" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            } // fin de si view="planets"
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_SHIPYARD']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $CSp = $user_building[$i]["CSp"];
                if ($CSp == "") $CSp = "&nbsp;";

                echo "\t" . "<th><input class='building current CSP' type='number' min='0' max='99' value='" . $CSp . "' id='csp" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project CSP' type='number' min='0' max='99' value='" . $CSp . "' id='csp" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_METALSTORAGE']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $HM = $user_building[$i]["HM"];
                if ($HM == "") $HM = "&nbsp;";

                echo "\t" . "<th><input class='building current HM' type='number' min='0' max='99' value='" . $HM . "' id='hm" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project HM' type='number' min='0' max='99' value='" . $HM . "' id='hm" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_CRYSTALSTORAGE']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $HC = $user_building[$i]["HC"];
                if ($HC == "") $HC = "&nbsp;";

                echo "\t" . "<th><input class='building current HC' type='number' min='0' max='99' value='" . $HC . "' id='hc" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project HC' type='number' min='0' max='99' value='" . $HC . "' id='hc" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_DEUTSTORAGE']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $HD = $user_building[$i]["HD"];
                if ($HD == "") $HD = "&nbsp;";

                echo "\t" . "<th><input class='building current HD' type='number' min='0' max='99' value='" . $HD . "' id='hd" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project HD' type='number' min='0' max='99' value='" . $HD . "' id='hd" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
<?php
	if($view == "planets") { 
?>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_RESEARCHLAB']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $Lab = $user_building[$i]["Lab"];
                $Labs[] = intval($Lab);
                if ($Lab == "") $Lab = "&nbsp;";

                echo "\t" . "<th><input class='building current LAB' type='number' min='0' max='99' value='" . $Lab . "' id='lab" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project LAB' type='number' min='0' max='99' value='" . $Lab . "' id='lab" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            $maxLab = max($Labs);            
            if ( $server_config['ddr'] == 1 )
            {
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_ALLIANCEDEPOT']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $DdR = $user_building[$i]["DdR"];
                if ($DdR == "") $DdR = "&nbsp;";

                echo "\t" . "<th><input class='building current DDR' type='number' min='0' max='99' value='" . $DdR . "' id='ddr" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project DDR' type='number' min='0' max='99' value='" . $DdR . "' id='ddr" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            }//Fin de si $server_config['ddr']
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TERRAFORMER']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $Ter = $user_building[$i]["Ter"];
                if ($Ter == "") $Ter = "&nbsp;";

                echo "\t" . "<th><input class='building current TER' type='number' min='0' max='99' value='" . $Ter . "' id='ter" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project TER' type='number' min='0' max='99' value='" . $Ter . "' id='ter" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_MISSILESSILO']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $Silo = $user_building[$i]["Silo"];
                if ($Silo == "") $Silo = "&nbsp;";

                echo "\t" . "<th><input class='building current SILO' type='number' min='0' max='99' value='" . $Silo . "' id='silo" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project SILO' type='number' min='0' max='99' value='" . $Silo . "' id='silo" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            } // fin de si view="planets"
            else {
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_LUNARSTATION']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $BaLu = $user_building[$i]["BaLu"];
                if ($BaLu == "") $BaLu = "&nbsp;";

                echo "\t" . "<th><input class='building current BALU' type='number' min='0' max='99' value='" . $BaLu . "' id='balu" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project BALU' type='number' min='0' max='99' value='" . $BaLu . "' id='balu" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_LUNARPHALANX']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $Pha = $user_building[$i]["Pha"];
                if ($Pha == "") $Pha = "&nbsp;";

                echo "\t" . "<th><input class='building current PHA' type='number' min='0' max='99' value='" . $Pha . "' id='pha" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project PHA' type='number' min='0' max='99' value='" . $Pha . "' id='pha" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_LUNARJUMPGATE']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $PoSa = $user_building[$i]["PoSa"];
                if ($PoSa == "") $PoSa = "&nbsp;";

                echo "\t" . "<th><input class='building current POSA' type='number' min='0' max='99' value='" . $PoSa . "' id='posa" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='building project POSA' type='number' min='0' max='99' value='" . $PoSa . "' id='posa" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            } // fin de sinon view="planets"
            ?>
        </tr>
        <tr>
            <td class="c_batiments sum" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>">
                <?php echo($lang['OGSCALC_BUILDINGS_SUM']); ?>
            </td>
        </tr>       
        <tr>
        	<th></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_METAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_CRYSTAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_DEUT']); ?></a></th><?php if( $view == "planets" ) { ?><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_ENERGY']); ?></a></th><?php  } ?><th colspan="2"><a><?php echo($lang['GAME_FLEET_PT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_GT']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_POINTS']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME']); ?></a></th>
        </tr>
        <tr>
        	<th><a><?php echo($lang['OGSCALC_SUM']); ?></a></th><th colspan="2" id="sum_metal_buildings"></th><th colspan="2" id="sum_crystal_buildings"></th><th colspan="2" id="sum_deut_buildings"></th><?php if( $view == "planets" ) { ?><th colspan="2" id="sum_energy_buildings"></th><?php  } ?><th colspan="2" id="sum_pt_buildings"></th><th colspan="2" id="sum_gt_buildings"></th><th colspan="2" id="sum_points_buildings"></th><th colspan="2" id="sum_time_buildings"></th>
        </tr>
        <tr>
            <th><a><?php echo($lang['OGSCALC_TIME_PRODUCTION']); ?></a></th><th colspan="2" id="time_metal_buildings"></th><th colspan="2" id="time_crystal_buildings"></th><th colspan="2" id="time_deut_buildings"></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME_PRODUCTION_CONVERT']); ?></a></th><th colspan="2" id="time_convert_buildings"></th>
        </tr>
        <tr>
            <td class="c_satellite" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2; ?>"><?php echo($lang['HOME_EMPIRE_OTHERS']); ?></td>
        </tr>
        <tr>
        	<th></th>
        	 <?php        	 
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
            	 echo "\t" . "<th>".$lang['OGSCALC_CURRENT']."</th><th>".$lang['OGSCALC_PROJECT']."</th>" . "\n";
            }
            ?>            
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_SATELLITES']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $Sat = $user_building[$i]["Sat"];
                if ($Sat == "") $Sat = "&nbsp;";
                else $Sat = number_format($Sat, 0, ',', '');

                echo "\t" . "<th><input type='number' min='0' max='999999999999' class='inputOgscalc divers current Sat' id='6" . ($i + 1 - $start) . "' value='" . $Sat . "'></th>" .
                "<th><input type='number' min='0' max='999999999999' class='inputOgscalc divers project Sat' value='" . $Sat . "' id='6" . ($i + 1 - $start) . "' ></th>" ."\n";
            }            
            ?>
        </tr>
        <tr>
            <td class="c_satellite sum" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2; ?>"><?php echo($lang['OGSCALC_DIVERS_SUM']); ?></td>
        </tr>       
        <tr>
        	<th></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_METAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_CRYSTAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_DEUT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_PT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_GT']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_POINTS']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME']); ?></a></th>
        </tr>
        <tr>
        	<th><a><?php echo($lang['OGSCALC_SUM']); ?></a></th><th colspan="2" id="sum_metal_divers">0</th><th colspan="2" id="sum_crystal_divers"></th><th colspan="2" id="sum_deut_divers"></th><th colspan="2" id="sum_pt_divers"></th><th colspan="2" id="sum_gt_divers"></th><th colspan="2" id="sum_points_divers"></th><th colspan="2" id="sum_time_divers"></th>
        </tr>
        <tr>
            <th><a><?php echo($lang['OGSCALC_TIME_PRODUCTION']); ?></a></th><th colspan="2" id="time_metal_divers"></th><th colspan="2" id="time_crystal_divers"></th><th colspan="2" id="time_deut_divers"></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME_PRODUCTION_CONVERT']); ?></a></th><th colspan="2" id="time_convert_divers"></th>
        </tr>
        <?php if($view == "planets") { ?>
        <tr>
            <td class="c_classement_recherche" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php echo($lang['HOME_EMPIRE_TECHNOS']); ?></td>
        </tr>
        <tr>
        	<th></th>
        	 <?php        	 
            	 echo "\t" . "<th>".$lang['OGSCALC_CURRENT']."</th><th>".$lang['OGSCALC_PROJECT']."</th>" . "\n";
            ?>            
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_SPY']); ?></a></th>
            <?php

                $Esp = $user_technology["Esp"] != "" ? $user_technology["Esp"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Esp' value='".$Esp."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Esp' value='".$Esp."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_COMPUTER']); ?></a></th>
            <?php

                $Ordi = $user_technology["Ordi"] != "" ? $user_technology["Ordi"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Ordi' value='".$Ordi."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Ordi' value='".$Ordi."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_WEAPONS']); ?></a></th>
            <?php

                $Armes = $user_technology["Armes"] != "" ? $user_technology["Armes"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Armes' value='".$Armes."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Armes' value='".$Armes."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_SHIELD']); ?></a></th>
            <?php

                $Bouclier = $user_technology["Bouclier"] != "" ? $user_technology["Bouclier"] : "0";
                
                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Bouclier' value='".$Bouclier."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Bouclier' value='".$Bouclier."'></th>" ."\n";        
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_PROTECTION']); ?></a></th>
            <?php

            	$Protection = $user_technology["Protection"] != "" ? $user_technology["Protection"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Protection' value='".$Protection."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Protection' value='".$Protection."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_ENERGY']); ?></a></th>
            <?php

                $NRJ = $user_technology["NRJ"] != "" ? $user_technology["NRJ"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='NRJ' value='".$NRJ."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='NRJ' value='".$NRJ."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_HYPERSPACE']); ?></a></th>
            <?php

            	$Hyp = $user_technology["Hyp"] != "" ? $user_technology["Hyp"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Hyp' value='".$Hyp."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Hyp' value='".$Hyp."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_COMBUSTION_DRIVE']); ?></a></th>
            <?php

                $RC = $user_technology["RC"] != "" ? $user_technology["RC"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='RC' value='".$RC."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='RC' value='".$RC."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_IMPULSE_DRIVE']); ?></a></th>
            <?php

                $RI = $user_technology["RI"] != "" ? $user_technology["RI"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='RI' value='".$RI."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='RI' value='".$RI."'></th>" ."\n";               
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_HYPER_DRIVE']); ?></a></th>
            <?php

                $PH = $user_technology["PH"] != "" ? $user_technology["PH"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='PH' value='".$PH."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='PH' value='".$PH."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_LASER']); ?></a></th>
            <?php
                
                $Laser = $user_technology["Laser"] != "" ? $user_technology["Laser"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Laser' value='".$Laser."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Laser' value='".$Laser."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_IONS']); ?></a></th>
            <?php

                $Ions = $user_technology["Ions"] != "" ? $user_technology["Ions"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Ions' value='".$Ions."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Ions' value='".$Ions."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_PLASMA']); ?></a></th>
            <?php

                $Plasma = $user_technology["Plasma"] != "" ? $user_technology["Plasma"] : "0";

                echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Plasma' value='".$Plasma."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Plasma' value='".$Plasma."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_RESEARCH_NETWORK']); ?></a></th>
            <?php

	            $RRI = $user_technology["RRI"] != "" ? $user_technology["RRI"] : "0";

	            echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='RRI' value='".$RRI."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='RRI' value='".$RRI."'></th>" ."\n";        
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_ASTRO']); ?></a></th>
            <?php
	            
	            $Astrophysique = $user_technology["Astrophysique"] != "" ? $user_technology["Astrophysique"] : "0";

	            echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Astrophysique' value='".$Astrophysique."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Astrophysique' value='".$Astrophysique."'></th>" ."\n";
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_TECHNOS_GRAVITY']); ?></a></th>
            <?php
	           
	           $Graviton = $user_technology["Graviton"] != "" ? $user_technology["Graviton"] : "0";	                  

	            echo "\t" . "<th><input type='number' min='0' max='99' class='technos current' id='Graviton' value='".$Graviton."'></th>" .
                "<th><input type='number' min='0' max='99' class='technos project' id='Graviton' value='".$Graviton."'></th>" ."\n";         
            
            ?>
        </tr>
        <tr>
            <td class="c_classement_recherche sum" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php echo($lang['OGSCALC_TECHNOS_SUM']); ?></td>
        </tr>        
        <tr>
        	<th></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_METAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_CRYSTAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_DEUT']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_ENERGY']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_PT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_GT']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_POINTS']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME']); ?></a></th>
        </tr>
        <tr>
        	<th><a><?php echo($lang['OGSCALC_SUM']); ?></a></th><th colspan="2" id="sum_metal_technos"></th><th colspan="2" id="sum_crystal_technos"></th><th colspan="2" id="sum_deut_technos"></th><th colspan="2" id="sum_energy_technos"></th><th colspan="2" id="sum_pt_technos"></th><th colspan="2" id="sum_gt_technos"></th><th colspan="2" id="sum_points_technos"></th><th colspan="2" id="sum_time_technos"></th>
        </tr>
        <tr>
            <th><a><?php echo($lang['OGSCALC_TIME_PRODUCTION']); ?></a></th><th colspan="2" id="time_metal_technos"></th><th colspan="2" id="time_crystal_technos"></th><th colspan="2" id="time_deut_technos"></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME_PRODUCTION_CONVERT']); ?></a></th><th colspan="2" id="time_convert_technos"></th>
        </tr>
        <?php } // fin de si view="planets" ?>
        <tr>
            <td class="c_defense" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php echo($lang['HOME_EMPIRE_WEAPONS_TITLE']); ?></td>

        </tr>
        <tr>
        	<th></th>
        	 <?php        	 
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
            	 echo "\t" . "<th>".$lang['OGSCALC_CURRENT']."</th><th>".$lang['OGSCALC_PROJECT']."</th>" . "\n";
            }
            ?>            
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_MISSILES']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $LM = $user_defence[$i]["LM"];
                if ($LM == "") $LM = "&nbsp;";
                else $LM = number_format($LM, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current LM' type='number' min='0' max='999999999999' value='" . $LM . "' id='7" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project LM' type='number' min='0' max='999999999999' value='" . $LM . "' id='7" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_LLASERS']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $LLE = $user_defence[$i]["LLE"];
                if ($LLE == "") $LLE = "&nbsp;";
                else $LLE = number_format($LLE, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current LLE' type='number' min='0' max='999999999999' value='" . $LLE . "' id='8" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project LLE' type='number' min='0' max='999999999999' value='" . $LLE . "' id='8" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_HLASERS']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $LLO = $user_defence[$i]["LLO"];
                if ($LLO == "") $LLO = "&nbsp;";
                else $LLO = number_format($LLO, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current LLO' type='number' min='0' max='999999999999' value='" . $LLO . "' id='9" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project LLO' type='number' min='0' max='999999999999' value='" . $LLO . "' id='9" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_GAUSS']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $CG = $user_defence[$i]["CG"];
                if ($CG == "") $CG = "&nbsp;";
                else $CG = number_format($CG, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current CG' type='number' min='0' max='999999999999' value='" . $CG . "' id='10" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project CG' type='number' min='0' max='999999999999' value='" . $CG . "' id='10" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_IONS']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $AI = $user_defence[$i]["AI"];
                if ($AI == "") $AI = "&nbsp;";
                else $AI = number_format($AI, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current AI' type='number' min='0' max='999999999999' value='" . $AI . "' id='11" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project AI' type='number' min='0' max='999999999999' value='" . $AI . "' id='11" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_PLASMA']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $LP = $user_defence[$i]["LP"];
                if ($LP == "") $LP = "&nbsp;";
                else $LP = number_format($LP, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current LP' type='number' min='0' max='999999999999' value='" . $LP . "' id='12" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project LP' type='number' min='0' max='999999999999' value='" . $LP . "' id='12" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_SMALLSHIELD']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $PB = $user_defence[$i]["PB"];
                if ($PB == "") $PB = "&nbsp;";

                echo "\t" . "<th><input class='inputOgscalc weapons current PB' type='number' min='0' max='999999999999' value='" . $PB . "' id='13" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project PB' type='number' min='0' max='999999999999' value='" . $PB . "' id='13" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_LARGESHIELD']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $GB = $user_defence[$i]["GB"];
                if ($GB == "") $GB = "&nbsp;";

                echo "\t" . "<th><input class='inputOgscalc weapons current weapons GB' type='number' min='0' max='999999999999' value='" . $GB. "' id='14" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project GB' type='number' min='0' max='999999999999' value='" . $GB. "' id='14" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            if($view == "planets") {
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_ANTI']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $MIC = $user_defence[$i]["MIC"];
                if ($MIC == "") $MIC = "&nbsp;";
                else $MIC = number_format($MIC, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current MIC' type='number' min='0' max='999999999999' value='" . $MIC . "' id='19" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc project weapons MIC' type='number' min='0' max='999999999999' value='" . $MIC . "' id='19" . ($i + 1 - $start) . "'></th>" ."\n";
            }
            ?>
        </tr>
        <tr>
            <th><a><?php echo($lang['HOME_EMPIRE_WEAPONS_INTER']); ?></a></th>
            <?php
            for ($i = $start; $i <= $start + $nb_planete - 1; $i++) {
                $MIP = $user_defence[$i]["MIP"];
                if ($MIP == "") $MIP = "&nbsp;";
                else $MIP = number_format($MIP, 0, ',', '');

                echo "\t" . "<th><input class='inputOgscalc weapons current MIP' type='number' min='0' max='999999999999' value='" . $MIP . "' id='18" . ($i + 1 - $start) . "'></th>" .
                "<th><input class='inputOgscalc weapons project MIP' type='number' min='0' max='999999999999' value='" . $MIP . "' id='18" . ($i + 1 - $start) . "'></th>" ."\n";
            }

            } // fin de si view="planets"
            ?>
        </tr>
        <tr>
            <td class="c_defense sum" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php echo($lang['OGSCALC_WEAPONS_SUM']); ?></td>
        </tr>
        <tr>
        	<th></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_METAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_CRYSTAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_DEUT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_PT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_GT']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_POINTS']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME']); ?></a></th>
        </tr>
        <tr>
        	<th><a><?php echo($lang['OGSCALC_SUM']); ?></a></th><th colspan="2" id="sum_metal_weapons"></th><th colspan="2" id="sum_crystal_weapons"></th><th colspan="2" id="sum_deut_weapons"></th><th colspan="2" id="sum_pt_weapons"></th><th colspan="2" id="sum_gt_weapons"></th><th colspan="2" id="sum_points_weapons"></th><th colspan="2" id="sum_time_weapons"></th>
        </tr>
        <tr>
            <th><a><?php echo($lang['OGSCALC_TIME_PRODUCTION']); ?></a></th><th colspan="2" id="time_metal_weapons"></th><th colspan="2" id="time_crystal_weapons"></th><th colspan="2" id="time_deut_weapons"></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME_PRODUCTION_CONVERT']); ?></a></th><th colspan="2" id="time_convert_weapons"></th>
        </tr>
        <tr>
            <td class="c sumGlobal" colspan="<?php print ($nb_planete < 10) ? '10' : ($nb_planete + 1) * 2 ?>"><?php if($view == "planets") { echo($lang['OGSCALC_PLANETS_SUM']); } else { echo($lang['OGSCALC_MOONS_SUM']); } ?></td>
        </tr>       
        <tr>
        	<th></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_METAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_CRYSTAL']); ?></a></th><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_DEUT']); ?></a></th><?php if( $view == "planets" ) { ?><th colspan="2"><a><?php echo($lang['HOME_EMPIRE_ENERGY']); ?></a></th><?php  } ?><th colspan="2"><a><?php echo($lang['GAME_FLEET_PT']); ?></a></th><th colspan="2"><a><?php echo($lang['GAME_FLEET_GT']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_POINTS']); ?></a></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME']); ?></a></th>
        </tr>
        <tr>
        	<th><a><?php echo($lang['OGSCALC_SUM']); ?></a></th><th colspan="2" id="sum_metal"></th><th colspan="2" id="sum_crystal"></th><th colspan="2" id="sum_deut"></th><?php if( $view == "planets" ) { ?><th colspan="2" id="sum_energy"></th><?php  } ?><th colspan="2" id="sum_pt"></th><th colspan="2" id="sum_gt"></th><th colspan="2" id="sum_points"></th><th colspan="2" id="sum_time"></th>
        </tr>
        <tr>
            <th><a><?php echo($lang['OGSCALC_TIME_PRODUCTION']); ?></a></th><th colspan="2" id="time_metal"></th><th colspan="2" id="time_crystal"></th><th colspan="2" id="time_deut"></th><th colspan="2"><a><?php echo($lang['OGSCALC_TIME_PRODUCTION_CONVERT']); ?></a></th><th colspan="2" id="time_convert"></th>
        </tr>
    </table>


	<!-- MANAGE PRICES  -->
	<script type="text/javascript">
		var speedUni = "<?php echo $server_config['speed_uni']; ?>";
		var IonsLevelUser = "<?php echo $Ions = $user_technology['Ions'] != '' ? $user_technology['Ions'] : '0'; ?>";
		var RRILevelUser = "<?php echo $Ions = $user_technology['RRI'] != '' ? $user_technology['RRI'] : '0'; ?>";
		var view = "<?php echo $view; ?>"; 
        var productionUser = <?php echo json_encode($user_production['reel']); ?>;
        var metalPerHour = parseInt(<?php echo $prodMetal; ?>, 10);
        var crystalPerHour = parseInt(<?php echo $prodCrystal; ?>, 10);
        var deutPerHour = parseInt(<?php echo $prodDeut; ?>, 10);
	</script>
	<script type="text/javascript" src="mod/<?php echo $mod_root; ?>/js/ogameFormula.js"></script>
	<script type="text/javascript" src="mod/<?php echo $mod_root; ?>/js/buildingsCosts.js"></script>
	<script type="text/javascript" src="mod/<?php echo $mod_root; ?>/js/technosCosts.js"></script>
	<script type="text/javascript" src="mod/<?php echo $mod_root; ?>/js/destructiblesCosts.js"></script>
	<script type="text/javascript" src="mod/<?php echo $mod_root; ?>/js/<?php echo $mod_root; ?>.js"></script>

	<?php
	/**
	 * @param $txt
	 * @param $nb_planete
	 * @return string
	 */
	function read_th($txt, $nb_planete)
	{
	    $retour = "";
	    if ($nb_planete > 9) {
	        for ($i = 10; $i <= $nb_planete; $i++) {
	            $retour = $retour . $txt;

	        }
	    }
	    return $retour;
	}




	require_once("views/page_tail.php");

	?>
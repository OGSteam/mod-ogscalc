<?php

/**
* functions.php - Fichier des fonctions spécifiques à OGSCalc
* @package Calculatrice universelle
* @author Aeris
* @update xaviernuma - 2015
* @update Athar - 2018
* @link http://www.ogsteam.fr/
**/

/**
 * Récupération du nombre de planètes de l'utilisateur
 * Pour éviter tout bug (aucune planète) ; valeur minimale de 1
 * @param $id
 * @return int|the
 */
function ogscalc_find_nb_planete_user_real($id)
{
    global $db, $user_data;


    $request = "SELECT planet_id ";
    $request .= " FROM " . TABLE_USER_BUILDING;
    $request .= " WHERE user_id = " . $user_data["user_id"];
    $request .= " AND planet_id < 199 ";
    $request .= " ORDER BY planet_id";

    $result = $db->sql_query($request);

    //mini 1 pour éviter un du mod et d'affichage
    if ($db->sql_numrows($result) <= 1) {
        return 1;
    }

    return $db->sql_numrows($result);

}

/**
 * Récupération du nombre de lunes de l'utilisateur
 * Pour éviter tout bug (aucune lune) on s'assure que le résultat soit bien à 0 ; valeur minimale de 0
 * @param $id
 * @return int|the
 */
function ogscalc_find_nb_moon_user($id)
{
    global $db, $user_data;


    $request = "select planet_id ";
    $request .= " from " . TABLE_USER_BUILDING;
    $request .= " where user_id = " . $user_data["user_id"];
    $request .= " and planet_id > 199 ";
    $request .= " order by planet_id";

    $result = $db->sql_query($request);

    //mini 0 pour eviter bug affichage
    if ($db->sql_numrows($result) <= 0) {
        return 0;
    }

    return $db->sql_numrows($result);

}

?>
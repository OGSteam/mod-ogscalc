<?php

/**
* ogscalc.php - Fichier de désinstallation
* @package Calculatrice universelle
* @author Aeris
* @update xaviernuma - 2015
* @update Athar - 2018
* @link http://www.ogsteam.fr/
**/

if (!defined('IN_SPYOGAME'))
{
    exit('Hacking Attempt!');
}

global $db, $table_prefix;
 
$mod_uninstall_name  = 'OGSCalc';
$mod_uninstall_table = NULL;

uninstall_mod($mod_uninstall_name, $mod_uninstall_table);

?>
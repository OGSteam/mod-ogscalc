<?php

/**
* ogscalc.php - Fichier de désinstallation
* @package Calculatrice universelle
* @author Aeris
* @update xaviernuma - 2012
* @link http://www.ogsteam.fr/
**/

if (!defined('IN_SPYOGAME'))
{
    exit('Hacking Attempt!');
}

global $db, $table_prefix;
 
$mod_uninstall_name  = 'OGSCalc'; 
 
uninstall_mod ($mod_uninstall_name, $mod_uninstall_table);
?>
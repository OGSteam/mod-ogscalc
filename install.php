<?php

/**
* install.php - Fichier d'installation
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

$mod_folder = 'ogscalc';

if (install_mod($mod_folder)) 
{
	// Si besoin de créer des tables, à faire ici
}
else 
{
	echo '<script>alert(\'Un problème a eu lieu pendant l\\\'installation du mod, corrigez les problèmes survenus et réessayez.\');</script>';
}

?>
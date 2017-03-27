<?php
##############################################################################
## FICHIER DE CONFIG DU MODULE												##
##																			##
## name : le nom affiché dans le menu										##
## path : le chemin d'accès au module										##
## allowed_levels : niveaux d'utilisateur autorisés à accéder au module ;	##
##	1 = administrateur														##
##	2 = salarié																##
##############################################################################

$module_details = array(
	'name' 					=> 'Contacts', 
	'description' 			=> 'Visualiser les contacts',
	'icon' 					=> 'icon-set-adveris_61.png', 
	'path' 					=> 'modules/contacts/index.php', 
	'allowed_levels' 		=> array(1, 2), 
	'position' 				=> 4
);

##############################################################################
## INITIALISATION DU MODULE													##
##############################################################################
$handler_contacts	= new Handler_Contacts();
?>
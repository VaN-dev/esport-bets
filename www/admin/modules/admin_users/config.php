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
	'name' 					=> 'Admins', 
	'description'			=> 'Gérer les administrateurs',
	'icon' 					=> 'admins.png',
	'path' 					=> 'modules/admin_users/index.php', 
	'allowed_levels' 		=> array(1), 
	'jquery-ui-menu-class' 	=> 'ui-icon-locked', 
	'position' 				=> 99
);
?>
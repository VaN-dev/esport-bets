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
	'name' 					=> 'Pages', 
	'description' 			=> 'Modifier le contenu des pages de votre site',
	'icon' 					=> 'pages.png', 
	'path' 					=> 'modules/pages/index.php', 
	'allowed_levels' 		=> array(1, 2), 
	'position' 				=> 2
);

##############################################################################
## INITIALISATION DU MODULE													##
##############################################################################
$handler_pages	= new Handler_Pages();
?>
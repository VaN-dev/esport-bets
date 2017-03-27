<?php
##############################################################
## Définitions des chemins d'accès à / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE('ROOT_PATH', '../');
DEFINE('ADMIN_PATH', ROOT_PATH . 'admin/');
require_once(ROOT_PATH . 'init.php');


// Odds Infos
if(isset($_POST['action']) && $_POST['action'] == 'odds-infos') {
	$odds = new Odds(mysql_real_escape_string($_POST['id']));
	echo json_encode($odds);
}

// Match Infos
if(isset($_POST['action']) && $_POST['action'] == 'match-infos') {
	$match = new Match(mysql_real_escape_string($_POST['id']));
	echo json_encode($match);
}
?>
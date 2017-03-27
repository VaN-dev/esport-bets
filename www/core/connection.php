<?php
##############################################################
##	LOCAL													##
##############################################################
if($_SERVER['SERVER_NAME'] == 'esport-bets.localhost')
{
	DEFINE('BDD_HOST', 'localhost');
	DEFINE('BDD_NAME', 'esport-bets');
	DEFINE('BDD_USER', 'root');
	DEFINE('BDD_PASS', 'b2c3d4e5');
}

##############################################################
##	DISTANT													##
##############################################################
else
{
	// Remote Settings
	DEFINE('BDD_HOST', 'mysql51-83.perso');
	DEFINE('BDD_NAME', 'gamingvalley');
	DEFINE('BDD_USER', 'gamingvalley');
	DEFINE('BDD_PASS', 'yFliO6Tg');
}
?>
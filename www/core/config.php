<?php
##############################################################
##	THEME JQUERY											##
##############################################################
DEFINE('UI_THEME', 'adveris');

##############################################################
##	NOM DU COOKIE											##
##############################################################
DEFINE('COOKIE_NAME', 'ebets');

##############################################################
##	PREFIXE DES TABLES										##
##############################################################
DEFINE('DB_PREFIX', 'ebets_');

##############################################################
##	TABLES CORE												##
##############################################################
DEFINE('TABLES__ADMIN_USERS', 				DB_PREFIX . 'admin_users');
DEFINE('TABLES__ADMIN_USERS_LEVELS', 		DB_PREFIX . 'admin_users_levels');
DEFINE('TABLES__CONFIG', 					DB_PREFIX . 'config');

##############################################################
##	TABLES SPECIFIQUES										##
##############################################################
DEFINE('TABLES__BETS', 						DB_PREFIX . 'bets');
DEFINE('TABLES__COMMENTS', 					DB_PREFIX . 'comments');
DEFINE('TABLES__GAMES', 					DB_PREFIX . 'games');
DEFINE('TABLES__MATCHES', 					DB_PREFIX . 'matches');
DEFINE('TABLES__ODDS', 						DB_PREFIX . 'odds');
DEFINE('TABLES__OPPONENTS', 				DB_PREFIX . 'opponents');
DEFINE('TABLES__USERS', 					DB_PREFIX . 'users');
?>
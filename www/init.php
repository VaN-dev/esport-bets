<?php
##############################################################
##	RECUPERATION DU MICROTIME A L'INITIALISATION			##
##############################################################
$page_infos['microtime_start'] = microtime(true);

##############################################################
##	INI_SET													##
##############################################################
ini_set('safe_mode', 'On');

##############################################################
##	CHARGEMENT DES FICHIERS DE CONF							##
##############################################################
require_once(ROOT_PATH . 'core/connection.php');
require_once(ROOT_PATH . 'core/config.php');

##############################################################
##	RECUPERATION DE LA PAGE ACTUELLE						##
##############################################################
$current_page = basename($_SERVER['SCRIPT_FILENAME']);

##############################################################
##	CHARGEMENT DES TABLEAUX DE DONNEES						##
##############################################################
require_once(ROOT_PATH . 'core/arrays/array.scripts.php');

##############################################################
##	INITIALISATION DES TABLEAUX DE SCRIPTS/STYLES			##
##############################################################
$scripts_to_load = array();
$styles_to_load = array();

##############################################################
##	AUTOLOAD												##
##############################################################
function __autoload($class_name)
{
    require_once (ROOT_PATH . 'core/classes/class.' . strtolower($class_name) . '.php');
}

##############################################################
##	CHARGEMENT DES CLASSES									##
##############################################################
require_once(ROOT_PATH . 'core/classes/class.sql.php');
require_once(ROOT_PATH . 'core/classes/class.session.php');
require_once(ROOT_PATH . 'core/classes/class.object_model.php');
require_once(ROOT_PATH . 'core/classes/class.config.php');

require_once(ROOT_PATH . 'core/classes/class.handler_notices.php');
require_once(ROOT_PATH . 'core/classes/class.notice.php');

##############################################################
## DECLARATION DES CLASSES									##
##############################################################
$sql 								= new Sql();
$site 								= new Object_Model();
$session							= new Session();
$handler_notices					= new Handler_Notices();

##############################################################
##	CONNEXION SQL											##
##############################################################
//$connection = $sql->connection(BDD_HOST, BDD_USER, BDD_PASS);
$sql->select_db(BDD_NAME);

##############################################################
##	DEFINITION DE LA CONFIG SQL								##
##############################################################
$sql->sql_query( 'SET NAMES "utf8"' );
$sql->sql_query( 'SET lc_time_names = "en_US"' );

##############################################################
##	DEFINITION DE LA VARIABLE LOCALE						##
##############################################################
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

##############################################################
## RECUPERATION DE LA CONFIG								##
##############################################################
$config = new Config(1);

##############################################################
## INITIALISATION DE LA LANGUE								##
##############################################################
if (!isset($_SESSION['lang']))
{
	$_SESSION['lang'] = $config->site_default_lang;
}
DEFINE('LANG', $_SESSION['lang']);

##############################################################
## GESTION DE LA MAINTENANCE								##
##############################################################
if ($config->site_maintenance == 1 && $current_page != 'maintenance.php')
{
	header('Location: maintenance.php');
	exit();
}
elseif ($config->site_maintenance == 0 && $current_page == 'maintenance.php')
{
	header('Location: index.php');
	exit();
}

##############################################################
## OUVERTURE DE SESSION										##
##############################################################
if ((isset($_POST['action']) && $_POST['action'] == 'login') || (isset($_POST['subaction']) && $_POST['subaction'] == 'login'))
{
	$session->attempt_login($_POST['mail'], sha1($_POST['password']));
	if ($session->is_authed())
	{
		header('Location: ' . $config->site_url);
		exit();
	}
	else
	{
		
		header('Location: login.php');
		exit();
	}
}

##############################################################
## FERMETURE DE SESSION										##
##############################################################
if (isset($_GET['action']) && $_GET['action'] == 'logout')
{
	$session->destroy_session();
	header('Location: ' . $config->site_url);
	exit();
}

##############################################################
## TEST DE SESSION											##
##############################################################
if( $session->is_authed() ) {
	$session_user = new User( $_SESSION['USER']['id'] );
}

##############################################################
## DEBUG													##
##############################################################
// $site->print_r_pre($config);
// $site->print_r_pre($_POST);
// $site->print_r_pre($_SESSION['USER']);
// $site->print_r_pre($session_user);
// $site->print_r_pre($_SESSION['temp_user_id']);
?>
<?php
##############################################################
##	RECUPERATION DU MICROTIME A L'INITIALISATION			##
##############################################################
$page_infos['microtime_start'] = microtime(true);

##############################################################
##	CHARGEMENT DES FICHIERS DE CONF							##
##############################################################
require_once(ROOT_PATH . 'core/connection.php');
require_once(ROOT_PATH . 'core/config.php');

##############################################################
##	RECUPERATION DE LA PAGE ACTUELLE						##
##############################################################
$current_page = basename($_SERVER["SCRIPT_FILENAME"]);

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

require_once(ROOT_PATH . 'core/classes/class.handler_admin_users.php');
require_once(ROOT_PATH . 'core/classes/class.admin_user.php');

##############################################################
## DECLARATION DES CLASSES									##
##############################################################
$sql 								= new Sql();
$site 								= new Object_Model();
$session							= new Session();

$handler_notices					= new Handler_Notices();
$handler_admin_users 				= new Handler_Admin_Users();

##############################################################
##	CONNEXION SQL											##
##############################################################
$connection = $sql->connection(BDD_HOST, BDD_USER, BDD_PASS);
$sql->select_db( BDD_NAME );

##############################################################
##	DEFINITION DE LA CONFIG SQL								##
##############################################################
mysql_query('SET NAMES "utf8"');
mysql_query('SET lc_time_names = "fr_FR"');

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
if (!isset($_SESSION['lang']) )
{
	$_SESSION['lang'] = $config->site_default_lang;
}
DEFINE('LANG', $_SESSION['lang']);

##############################################################
## OUVERTURE DE SESSION	PAR FORMULAIRE						##
##############################################################
if (isset($_POST["action"]) && $_POST["action"] == 'login')
{
	$_SESSION['tmp_login'] = $_POST["user_login"];
	if (!isset($_POST['user_remember']))
	{
		$_POST['user_remember'] = false;
	}
	$session->attempt_admin_login($_POST["user_login"], sha1( $_POST["user_password"] ), $_POST['user_remember']);
	if ($session->is_admin_authed())
	{
		header('Location: index.php');
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
	$session->destroy_admin_session();
	header('Location: index.php');
	exit();
}

##############################################################
## TEST DE SESSION											##
##############################################################
$pages_allowed_without_session = array('login.php', 'password.retrieve.php' );
if (!$session->is_admin_authed() && !in_array( $current_page, $pages_allowed_without_session))
{
	header('Location: ' . ADMIN_PATH . 'login.php');
	exit();
}


##############################################################
## TEST DE SESSION											##
##############################################################
if ($session->is_admin_authed())
{
	$session_admin_user = new Admin_User($_SESSION['ADMIN-USER']['id']);
}


##############################################################
## RECUPERATION DES MODULES									##
##############################################################
$modules = array();

$modules_to_exclude = array('index', 'config');

$path_to_modules = ADMIN_PATH . 'modules';
if ($handle = opendir( $path_to_modules ))
{
    while (($item = readdir($handle)) !== false )
    {
        if (is_dir($path_to_modules . '/' . $item) && $item != '.' && $item != '..' && !in_array($item, $modules_to_exclude) )
        {
			if (file_exists($path_to_modules . '/' . $item . '/config.php'))
			{
				require_once($path_to_modules . '/' . $item . '/config.php');
				$modules[$item] = $module_details;
			}
		}
    }
}
$modules = $site->array_sort_by_column($modules, 'position');
unset($module_details);

##############################################################
## DEBUG													##
##############################################################
//$site->print_r_pre($config);
//$site->print_r_pre($_POST);
//$site->print_r_pre($_SESSION);
//$site->print_r_pre($session_admin_user);
//$site->print_r_pre($modules);
?>
<?php
##############################################################
## Définitions des chemins d'accès à / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE( 'ADMIN_PATH', '../../' );
DEFINE( 'ROOT_PATH', ADMIN_PATH . '../' );
require_once( ADMIN_PATH . 'init.php' );
require( 'config.php' );

##############################################################
## Définitions des scripts/styles à charger pour le module	##
##############################################################
$scripts_to_load = 	array('jquery.datatables');
$styles_to_load = 	array('jquery.datatables');

##############################################################
## Traitement du module										##
##############################################################
if(isset($_POST['action']) && $_POST['action'] == 'insert')
{
	// Instanciation de l'objet
	$item = new Admin_User();

	// Définition des propriétés
	$item->login 			= $_POST['login'];
	$item->password 		= $_POST['password'];
	$item->level			= $_POST['level'];
	$item->prenom 			= $_POST['prenom'];
	$item->nom				= $_POST['nom'];
	$item->statut 			= $_POST['statut'];

	// Insert
	if($item->is_unique())
	{
		$item->create();

		// Génération de la notice
		$notice = new Notice('success', 'Utilisateur modifié.');
		$notice->sessionize();	
	}
	else
	{
		// Génération de la notice
		$notice = new Notice('failure', 'Cet identifiant est déjà utilisé.');
		$notice->sessionize();	
	}

	// Redirection
	header('Location: index.php');
	exit();
}

if(isset($_POST['action']) && $_POST['action'] == 'update')
{
	// Instanciation de l'objet
	$item = new Admin_User(mysql_real_escape_string($_POST['id']));

	// Définition des propriétés
	$item->login 		= $_POST['login'];
	$item->password 	= $_POST['password'];
	$item->level		= $_POST['level'];
	$item->prenom 		= $_POST['prenom'];
	$item->nom 			= $_POST['nom'];
	$item->statut 		= $_POST['statut'];

	// Update
	if($item->is_unique())
	{
		$item->edit();
	
		// Génération de la notice
		$notice = new Notice('success', 'Utilisateur modifié.');
		$notice->sessionize();	
	}
	else
	{
		// Génération de la notice
		$notice = new Notice('failure', 'Cet identifiant est déjà utilisé.');
		$notice->sessionize();	
	}

	// Redirection
	header('Location: index.php');
	exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'delete')
{
	// Instanciation de l'objet
	$item = new Admin_User(mysql_real_escape_string($_GET['id']));

	// Delete
	$item->delete();

	// Génération de la notice
	$notice = new Notice('success', 'Utilisateur supprimé.');
	$notice->sessionize();	

	// Redirection
	header('Location: index.php');
	exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'switch_statut')
{
	if(is_numeric($_GET['id']))
	{
		// Instanciation de l'objet
		$item = new Admin_User(mysql_real_escape_string($_GET['id']));
		
		// Switch
		$item->switch_statut();
		
		// Génération de la notice
		$notice = new Notice('success', 'Statut modifié.');
		$notice->sessionize();
	}
	else
	{
		$notice = new Notice('failure', 'L\'identifiant de l\'élément n\'est pas correct.');
		$notice->sessionize();
	}

	// Redirection
	header('Location: index.php');
	exit();
}

require_once (ADMIN_PATH . 'includes/inc.header.php');
?>
</head>

<body>

<div id="site">

	<?php require(ADMIN_PATH . 'includes/inc.top.php'); ?>
	<?php require_once(ADMIN_PATH . 'includes/inc.menu.php'); ?>

	<div id="main">
		<div id="content">
			<?php
			$handler_notices->display_all();
			$handler_notices->clear();
			?>

			<h1>Administrateurs</h1>

			<?php
			// Accès autorisé, on affiche le module
			if(in_array($session_admin_user->level, $module_details['allowed_levels'])) {

				// par défaut, on affiche la liste
				if(!isset($_GET['action'])) $_GET['action'] = 'list'; 

				// Sécurisation de la variable $_GET["action"]
				$actions = array('insert', 'update', 'delete', 'list');

				// Si l'action demande est permise
				if(in_array($_GET['action'], $actions))
				{
					$clean['action'] = mysql_real_escape_string($_GET['action']);

					// On affiche la page demandée, en fonction de $clean['action'], version sécurisée de $_GET['action']
					switch($clean['action'])
					{
						// ADD
						case 'insert' :
							require_once( 'form.php' );
							break;

						// EDIT
						case 'update' :
							require_once( 'form.php' );
							break;

						// LIST
						case 'list' :
							require_once( 'list.php' );
							break;
					}
				}
				// Sinon, message d'erreur et retour à l'accueil
				else
				{
					echo '<div class="notice notice-failure">Cette action n\'est pas permise. <a href="index.php">Retour à la liste.</a></div>';
				}

			}
			// Accès refusé !
			else
			{
				echo '<div class="notie notice-failure">Accès interdit.</div>';
			}
			?>

		</div>
		<div class="clear-both"></div>
	</div>
	<?php require_once( ADMIN_PATH . 'includes/inc.footer.php' ); ?>
</div>

</body>
</html>

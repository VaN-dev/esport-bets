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
if(isset($_GET['action']) && $_GET['action'] == 'delete')
{
	// Instanciation de l'objet
	$item = new Contact(mysql_real_escape_string($_GET['id']));

	// Delete
	$item->delete();

	// Génération de la notice
	$notice = new Notice('success', 'Contact supprimé.');
	$notice->sessionize();	

	// Redirection
	header('Location: index.php');
	exit();
}

require_once( 'config.php' );
require_once( ADMIN_PATH . 'includes/inc.header.php' );
?>

</head>

<body>
<div id="site">

	<?php require_once( ADMIN_PATH . 'includes/inc.top.php' ); ?>
	<?php require_once( ADMIN_PATH . 'includes/inc.menu.php' ); ?>
	
	<div id="main">
		<div id="content">
		<?php
		$handler_notices->display_all();
		$handler_notices->clear();
		?>

			<h1>Contacts</h1>

			<?php
			// Accès autorisé, on affiche le module
			if(in_array($session_admin_user->level, $module_details['allowed_levels']))
			{
				// par défaut, on affiche la liste
				if(!isset($_GET['action'])) $_GET['action'] = 'list'; 

				// Sécurisation de la variable $_GET["action"]
				$actions = array('display', 'delete', 'list');

				// Si l'action demandée est permise
				if(in_array($_GET['action'], $actions))
				{
					$clean['action'] = mysql_real_escape_string($_GET['action']);
					
					// On affiche la page demandée, en fonction de $clean['action'], version sécurisée de $_GET['action']
					switch($clean['action'])
					{
						// ADD
						case 'display' :
							require_once( 'display.php' );
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

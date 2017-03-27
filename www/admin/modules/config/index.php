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
$scripts_to_load = 	array('jquery.tinymce');
$styles_to_load = array();

##############################################################
## Traitement du module										##
##############################################################

if(isset($_POST['action']) && $_POST['action'] == 'edit')
{
	$config = new Config(1);

	// site
	$config->site_name					= mysql_real_escape_string($_POST['site_name']);
	$config->site_url					= mysql_real_escape_string($_POST['site_url']);
	$config->site_short_url				= substr(mysql_real_escape_string($_POST['site_url']), 7);
	if(strlen($_POST['site_title']) <= 66)
	{
		$config->site_title					= mysql_real_escape_string($_POST['site_title']);
	}
	else
	{
		$notice = new Notice('warning', 'Le champ <strong>Titre du site</strong> est trop long. Sa modification a été ignorée.');
		$notice->sessionize();
	}
	if(strlen($_POST['site_description']) <= 156)
	{
		$config->site_description			= mysql_real_escape_string($_POST['site_description']);
	}
	else
	{
		$notice = new Notice('warning', 'Le champ <strong>Description du site</strong> est trop long. Sa modification a été ignorée.');
		$notice->sessionize();
	}
	$config->site_keywords					= mysql_real_escape_string($_POST['site_keywords']);
	$config->site_build_xml					= mysql_real_escape_string($_POST['site_build_xml']);
	$config->site_maintenance				= mysql_real_escape_string($_POST['site_maintenance']);
	$config->site_maintenance_message 		= mysql_real_escape_string($_POST['site_maintenance_message']);

	// contact
	$config->contact_mail					= mysql_real_escape_string($_POST['contact_mail']);
	$config->contact_tel					= mysql_real_escape_string($_POST['contact_tel']);
	$config->contact_fax					= mysql_real_escape_string($_POST['contact_fax']);

	// company
	$config->company_name					= mysql_real_escape_string($_POST['company_name']);
	$config->company_address				= mysql_real_escape_string($_POST['company_address']);
	$config->company_zipcode				= mysql_real_escape_string($_POST['company_zipcode']);
	$config->company_city					= mysql_real_escape_string($_POST['company_city']);
	$config->company_country				= mysql_real_escape_string($_POST['company_country']);
	$config->company_legal_status			= mysql_real_escape_string($_POST['company_legal_status']);
	$config->company_capital				= mysql_real_escape_string($_POST['company_capital']);
	$config->company_siren					= mysql_real_escape_string($_POST['company_siren']);
	$config->company_responsable			= mysql_real_escape_string($_POST['company_responsable']);

	// google
	$config->analytics_key					= mysql_real_escape_string($_POST['analytics_key']);
	$config->map_key						= '';
	$config->map_latitude					= '';
	$config->map_longitude					= '';

	// social
	$config->social_facebook				= mysql_real_escape_string($_POST['social_facebook']);
	$config->social_twitter					= mysql_real_escape_string($_POST['social_twitter']);

	// layout
	$config->layout_carousel_homepage_type	= mysql_real_escape_string($_POST['layout_carousel_homepage_type']);
	$config->layout_carousel_homepage_id	= mysql_real_escape_string($_POST['layout_carousel_homepage_id']);
	$config->layout_carousel_galeries		= mysql_real_escape_string($_POST['layout_carousel_galeries']);

	$config->edit();

	// Traitement du flux XML
	if($config->site_build_xml == 1)
	{
		$site->build_xml_actualites(ROOT_PATH . 'xml/actualites.xml');
	}
	else
	{
		if(file_exists(ROOT_PATH . 'xml/actualites.xml'))
		{
			unlink(ROOT_PATH . 'xml/actualites.xml');
		}
	}

	$notice = new Notice('success', 'Modifications enregistrées.');
	$notice->sessionize();

	header('Location: index.php');
	exit();
}

require_once (ADMIN_PATH . 'includes/inc.header.php');
?>

<script type="text/javascript">
$(document).ready(function() {

	// Compteur site_title
	$('#site_title').keyup(function() {
		$('#site_title_chars').html(66 - $(this).val().length);
		
	});
	
	// Compteur site_description
	$('#site_description').keyup(function() {
		$('#site_description_chars').html(156 - $(this).val().length);
		
	}).keydown(function(e) {
		if(e.keyCode != 8) {
			if($(this).val().length >= 156) {
				return false;
			}
		}
	});
	
	// Toggle Maintenance
	$('input[name=site_maintenance]').change(function() {
		if($(this).val() == 1) {
			$('#input-container-site_maintenance_message').show();
		}
		else {
			$('#input-container-site_maintenance_message').hide();
		}
	});
	
});
</script>
</head>

<body>
<div id="site">

	<?php require(ADMIN_PATH . 'includes/inc.top.php'); ?>
	<?php require_once(ADMIN_PATH . 'includes/inc.menu.php'); ?>
	
	<div id="main">
		<div id="content" class="clearfix">
			<?php
			$handler_notices->display_all();
			$handler_notices->clear();
			?>

			<h1>Configuration</h1>

			<?php
			// Vérification du droit d'accès
			//$access = is_access_granted($_SESSION["admin_user"]["user_level"], $clean["module"]);
			$access = 1;

			// Accès autorisé, on affiche le module
			if($access == 1)
			{
				// par défaut, on affiche l'édition
				if(!isset($_GET["action"]))
				{
					$_GET["action"] = "edit";
				}

				// Sécurisation de la variable $_GET["action"]
				$actions = array("edit");

				// Si l'action demande est permise
				if(in_array($_GET["action"], $actions))
				{
					$clean["action"] = mysql_real_escape_string($_GET["action"]);
					
					// On affiche la page demandée, en fonction de $clean["action"], version sécurisée de $_GET["action"]
					switch($clean["action"])
					{
						// EDIT
						case "edit" :
							require "edit.php";
							break;
					}
				}
				// Sinon, message d'erreur et retour à l'accueil
				else
				{
					echo '<div class="notice notice-failure">Cette action n\'est pas permise. <a href="index.php">Retour à l\'accueil.</a></div>';
				}
			}
			// Accès refusé !
			else
			{
				echo '<div class="notice notice-failure">Accès interdit.</div>';
			}
			?>
		</div>
	</div>
	<?php require(ADMIN_PATH . 'includes/inc.footer.php'); ?>
</div>
</body>
</html>
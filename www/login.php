<?php
##############################################################
## Définitions des chemins d'accès à / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE('ROOT_PATH', '');
DEFINE('ADMIN_PATH', ROOT_PATH . 'admin/');
require_once(ROOT_PATH . 'init.php');

$cryptinstall = ROOT_PATH . "core/tools/cryptographp/cryptographp.fct.php";
include $cryptinstall; 

##############################################################
## Définitions des scripts/styles à charger pour la page	##
##############################################################
$scripts_to_load = array();
$styles_to_load = array();

##############################################################
## Traitements PHP											##
##############################################################


##############################################################
## Chargement du header										##
##############################################################
require_once(ROOT_PATH . 'includes/inc.head.php');
?>
</head>

<body>

<div id="wrap">

	<?php
	require_once(ROOT_PATH . 'includes/inc.top.php');
	?>
	
	<div id="main" class="container clear-top">
		
		<div class="row">
			
			<div class="span12">
			
				<h1>Log in</h1>
					
				<?php
				// Affichage des notices
				$handler_notices->display_all();
				$handler_notices->clear();
				?>
				
				<?php require(ROOT_PATH . 'includes/inc.forms.auth.php'); ?>
				
			</div>
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

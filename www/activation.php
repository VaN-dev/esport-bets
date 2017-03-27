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
if(isset($_GET['mail']) && isset($_GET['key'])) {
	
	$user = new User();
	
	$user->mail				= mysql_real_escape_string($_GET['mail']);
	$user->activation_key	= mysql_real_escape_string($_GET['key']);
	
	if($user->attempt_activation() === true) {
		
		$notice = new Notice('success', 'Congratulations, you successfully activated your account.');
		$notice->sessionize();
		
		header('Location: login.php');
		exit();
		
	}
	else {
		$notice = new Notice('failure', 'Your e-mail and your activation key don\'t match. Click on the link in the e-mail you received when you registered.');
		$notice->sessionize();
		
		header('Location: activation.php');
		exit();
	}

}

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
			
				<h1>Account activation</h1>
					
				<?php
				// Affichage des notices
				$handler_notices->display_all();
				$handler_notices->clear();
				?>
					
			</div>
			
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

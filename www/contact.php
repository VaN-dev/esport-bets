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
			


			<form class="well span8">
				
				<?php
			// Affichage des notices
			$handler_notices->display_all();
			$handler_notices->clear();
			?>
				
				<h1>Contact us</h1>
				
				<div class="row">
					<div class="span3">
						<label>First Name</label>
						<input type="text" class="span3" placeholder="Your First Name">
						<label>Last Name</label>
						<input type="text" class="span3" placeholder="Your Last Name">
						<label>Email Address</label>
						<input type="text" class="span3" placeholder="Your email address">
						<label>Subject</label>
						<select id="subject" name="subject" class="span3">
							<option value="na" selected="">Choose One:</option>
							<option value="service">General Customer Service</option>
							<option value="suggestions">Suggestions</option>
							<option value="product">Product Support</option>
						</select>
					</div>
					<div class="span5">
						<label>Message</label>
						<textarea name="message" id="message" class="input-xlarge span5" rows="10"></textarea>
					</div>
				
					<button type="submit" class="btn btn-primary pull-right">Send</button>
				</div>
		</form>

			
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

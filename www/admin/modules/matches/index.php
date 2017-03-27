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
$scripts_to_load = 	array();
$styles_to_load = 	array();

##############################################################
## Traitement du module										##
##############################################################
if(isset($_POST['action']) && $_POST['action'] == 'insert') {

	// Instanciation de l'objet
	$match = new Match();
	
	// Définition des propriétés
	$match->start 		= $_POST['start'] . ':00';
	$match->end 		= $_POST['end'] . ':00';
	$match->game_id 	= $_POST['game_id'];
	$match->featured 	= $_POST['featured'];
	$match->statut		= $_POST['statut'];
	
	// Création
	$match->create();
	
	// Création des côtes
	if(isset($_POST['odds'])) {
		$odds = new Odds();
		$odds->match_id = $match->id;
		$odds->opponent_id = $_POST['opponent_1_id'];
		$odds->value = $_POST['odds']['new_odds_1'];
		$odds->create();
		
		$odds = new Odds();
		$odds->match_id = $match->id;
		$odds->opponent_id = $_POST['opponent_2_id'];
		$odds->value = $_POST['odds']['new_odds_2'];
		$odds->create();
	}
	
	// Message
	$notice = new Notice('success', 'Match succesfully saved.');
	$notice->sessionize();
	
	// Redirection
	header('Location: index.php');
	exit();

}

if(isset($_POST['action']) && $_POST['action'] == 'update') {

	// Instanciation de l'objet
	$match = new Match(mysql_real_escape_string($_POST['id']));
	
	// Définition des propriétés
	$match->start = $_POST['start'] . ':00';
	$match->end = $_POST['end'] . ':00';
	$match->game_id = $_POST['game_id'];
	$match->featured 	= $_POST['featured'];
	$match->statut	= $_POST['statut'];
	
	// Création
	$match->edit();
	
	// Création des côtes
	if(isset($_POST['odds'])) {
		$odds = new Odds();
		$odds->match_id = $match->id;
		$odds->opponent_id = $_POST['opponent_1_id'];
		$odds->value = $_POST['odds']['new_odds_1'];
		$odds->create();
		
		$odds = new Odds();
		$odds->match_id = $match->id;
		$odds->opponent_id = $_POST['opponent_2_id'];
		$odds->value = $_POST['odds']['new_odds_2'];
		$odds->create();
	}
	
	// Message
	$notice = new Notice('success', 'Match succesfully saved.');
	$notice->sessionize();
	
	// Redirection
	header('Location: index.php');
	exit();

}

if(isset($_POST['action']) && $_POST['action'] == 'close') {
	
	// Instanciation de l'objet
	$match = new Match(mysql_real_escape_string($_POST['id']));
	
	// Définition des propriétés
	$match->winner_id = $_POST['winner_id'];
	
	// Fermeture du pari
	$match->close();
	
	// Fermeture des paris
	$match->close_bets();
	
	// Récomponse des paris
	$match->reward_betters();
	
	// Message
	$notice = new Notice('success', 'Match succesfully closed.');
	$notice->sessionize();
	
	// Redirection
	header('Location: index.php');
	exit();
	
}

if(isset($_GET['action']) && $_GET['action'] == 'reopen') {
	
	// Instanciation de l'objet
	$match = new Match(mysql_real_escape_string($_GET['id']));
	
	// Fermeture du pari
	$match->reopen();
	
	// Message
	$notice = new Notice('success', 'Match succesfully re-opened.');
	$notice->sessionize();
	
	// Redirection
	header('Location: index.php');
	exit();
	
}

##############################################################
## Chargement du header										##
##############################################################
require_once (ADMIN_PATH . 'includes/inc.head.php');
?>
<script type="text/javascript">
$(function () {
	
	// Add odds
	$('#add-odds').click(function (e) {
		var html = '<tr>' +
			'<td></td>' +
			'<td><input type="text" name="odds[new_odds_1]" class="input-small" /></td>' +
			'<td><input type="text" name="odds[new_odds_2]" class="input-small" /></td>' +
		'</tr>';
		$('#table-odds').prepend(html);
	});
	
	if($(".datepicker").size() > 0) {
		$(".datepicker").datetimepicker({
			format: 'yyyy-mm-dd hh:ii',
			language: 'fr'
		});
	}
	
	// Modal Close
	$('.btn-close').click(function (e) {
		$('#id').val( $(this).data('id') );
		
		$.ajax({
			dataType: "json",
			url: '<?php echo ROOT_PATH; ?>ajax/data.php',
			type: "POST",
			data: { action: "match-infos", id: $(this).data('id') }
		})
		.done(function(data) {
			var opponents = [];
			$.each( data.opponents, function( key, opponent ) {
				console.log(opponent);
				opponents.push( "<option value='" + opponent.opponent_id + "'>" + opponent.name + "</option>" );
			});
			$('#winner_id').html(opponents.join( "" ));
		});
	});
});
</script>
</head>

<body>

<?php
require_once(ADMIN_PATH . 'includes/inc.top.php');

require_once(ADMIN_PATH . 'includes/inc.menu.php');
?>

<div class="main">
	
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

<?php
require_once(ADMIN_PATH . 'includes/inc.footer.php');
?>

</body>
</html>

<?php
##############################################################
## Définitions des chemins d'accès à / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE('ROOT_PATH', '');
DEFINE('ADMIN_PATH', ROOT_PATH . 'admin/');
require_once(ROOT_PATH . 'init.php');

##############################################################
## Définitions des scripts/styles à charger pour la page	##
##############################################################
$scripts_to_load = array();
$styles_to_load = array();

##############################################################
## Traitements PHP											##
##############################################################
if(isset($_GET['id']) && !empty($_GET['id'])) {
	
	$match = new Match($sql->escape($_GET['id']));
	if(isset($match) && isset($match->id)) {
		$game = new Game($match->game_id);
	}
}

if(isset($_POST['action']) && $_POST['action'] == 'bet') {
	
	if($_POST['stake'] <= $session_user->points) {
	
		// Instanciation de l'objet
		$bet = new Bet();
		
		// Définition des propriétés
		$bet->user_id	= $session_user->id;
		$bet->datetime = mysql_real_escape_string($_POST['datetime']);
		$bet->match_id	= mysql_real_escape_string($_POST['match_id']);
		$bet->opponent_id	= mysql_real_escape_string($_POST['opponent_id']);
		$bet->stake	= mysql_real_escape_string($_POST['stake']);
		$bet->statut = 1;
		
		$bet->odds_id = $bet->getLatestOddsIdFromMatchAndOpponent();
		
		// Création
		$bet->create();
		
		// Delete points
		$session_user->points = $session_user->points - $bet->stake;
		$session_user->update_points();
		
		// Message
		$notice = new Notice('success', 'Your bet was succesfully saved.');
		$notice->sessionize();
		
	}
	else {
		$notice = new Notice('danger', 'You can\'t bet more than your current points, ' . $session_user->points . '.');
		$notice->sessionize();
	}
	
	// Redirection
	header('Location: match.php?id=' . $bet->match_id);
	exit();
	
}

if(isset($_POST['action']) && $_POST['action'] == 'post_comment') {

    if (isset($session_user)) {
        // Instanciation de l'objet
        $comment = new Comment();

        // Définition des propriétés
        $comment->user_id	= $session_user->id;
        $comment->match_id	= $_POST['match_id'];
        $comment->content	= $_POST['content'];
        $comment->statut	= 1;

        // Création
        $comment->create();

        // Message
        $notice = new Notice('success', 'Your comment was successfully posted.');
        $notice->sessionize();
    } else {
        $notice = new Notice('danger', 'You must be authed to post a comment.');
        $notice->sessionize();
    }

	
	// Redirection
	header('Location: match.php?id=' . $_POST['match_id']);
	exit();
	
}

##############################################################
## Chargement du header										##
##############################################################
require_once(ROOT_PATH . 'includes/inc.head.php');
?>
<script type="text/javascript"> 
$(function() {

	// Modal Bet
	$('.btn-odds').click(function (e) {
		var d = new Date();
		var datetime = d.getFullYear() + '-' + str_pad(d.getMonth()+1, 2) + '-' + str_pad(d.getDate(), 2) + ' ' + str_pad(d.getHours(), 2) + ':' + str_pad(d.getMinutes(), 2) + ':' + str_pad(d.getSeconds(), 2);
		$('#datetime').val( datetime );
		$('#match-id').val( $(this).data('match-id') );
		$('#opponent-id').val( $(this).data('opponent-id') );
		
		$.ajax({
			dataType: "json",
			url: '<?php echo ROOT_PATH; ?>ajax/data.php',
			type: "POST",
			data: { action: "odds-infos", id: $(this).data('id') }
		})
		.done(function(data) {
			$('#odds-container').text(data.value);
		});
	});
	
	// Earnings
	$('#stake').keyup(function (e) {
		var earnings = $(this).val() * $('#odds-container').text();
		$('#earnings-container').text( earnings );
	});
	
});
</script>
</head>

<body>

<div id="wrap">

	<?php
	require_once(ROOT_PATH . 'includes/inc.top.php');
	?>
	
	<div id="main" class="container clear-top">
		
		<?php
		if(isset($match) && isset($match->id)) {
			?>
		
		<h1><?php echo $match->opponents[0]->name; ?> vs <?php echo $match->opponents[1]->name; ?></h1>
		
		<?php
		// Affichage des notices
		$handler_notices->display_all();
		$handler_notices->clear();
		?>
		
		<!-- Modal -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Your bet</h3>
			</div>
			<form class="bet-form" method="post">
				<div class="modal-body" id="modal-bet">
					<label for="stake">Stake:</label> <input type="text" name="stake" id="stake" /><span class="currency-container">GP</span>
					<br class="clear-both" />
					<label for="odds">Odds:</label> <span id="odds-container"></span>
					<br class="clear-both" />
					<label for="odds">Earnings:</label> <span id="earnings-container"></span><span class="currency-container">GP</span>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
					<button class="btn btn-primary">Bet now</button>
				</div>
				<input type="hidden" name="datetime" id="datetime" value="" />
				<input type="hidden" name="match_id" id="match-id" value="" />
				<input type="hidden" name="opponent_id" id="opponent-id" value="" />
				<input type="hidden" name="action" value="bet" />
			</form>
		</div>
		
		<p><img src="images/<?php echo $game->icon; ?>" alt="" /> <strong><?php echo stripslashes($game->name); ?></strong>, from <strong><?php echo $match->start_formatted; ?></strong> to <strong><?php echo $match->end_formatted; ?></strong></p>
		
		<div class="row">
			<div class="span4 match-opponent-block">
				<p><img src="images/opponents/<?php echo $match->opponents[0]->image; ?>" alt="" /></p>
				<?php
				if($match->statut == 1) {
					?>
				<a href="#myModal" data-toggle="modal" class="btn btn-success btn-large btn-odds" data-id="<?php echo $match->opponents[0]->odds_id; ?>" data-match-id="<?php echo $match->id; ?>" data-opponent-id="<?php echo $match->opponents[0]->opponent_id; ?>">Odds: <?php echo $match->opponents[0]->value; ?></a>
					<?php	
				}
				?>
			</div>
			<div class="span4 match-opponent-block">
				<p><img src="images/opponents/<?php echo $match->opponents[1]->image; ?>" alt="" /></p>
				<?php
				if($match->statut == 1) {
					?>
				<a href="#myModal" data-toggle="modal" class="btn btn-warning btn-large btn-odds" data-id="<?php echo $match->opponents[1]->odds_id; ?>" data-match-id="<?php echo $match->id; ?>" data-opponent-id="<?php echo $match->opponents[1]->opponent_id; ?>">Odds: <?php echo $match->opponents[1]->value; ?></a>
					<?php	
				}
				?>
			</div>
			<?php
			if($match->statut == 2) {
				?>
			<div class="span8 alert alert-danger">This match is over, you can't bet on it anymore.</div>	
				<?php
			}
			?>
		</div>
		
		<h2>Comments</h2>
		
		<?php
		$handler_comments = new Handler_Comments();
		$comments = $handler_comments->get_list_active_from_match($match->id);
		if(!empty($comments)) {
			foreach($comments as $comment) {
				?>
		<blockquote>
			<p><?php echo stripslashes(htmlspecialchars($comment->content)); ?></p>
			<small><?php echo stripslashes(htmlspecialchars($comment->user->firstname)); ?>, on <?php echo $comment->datetime_formatted; ?></small>
		</blockquote>
				<?php
			}
		}
		?>
		
		<form method="post">
			<textarea rows="3" style="width:600px;" name="content"></textarea><br />
			<input type="hidden" name="match_id" value="<?php echo $match->id; ?>" />
			<input type="hidden"  name="action" value="post_comment" />
			<input type="submit" name="submit" class="btn btn-primary" value="Submit" />
		</form>
		
			<?php
		}
		else {
			echo '<div class="alert alert-warning">No match identifier was specified or the identifier is wrong.</div>';
		}
		?>
		
	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

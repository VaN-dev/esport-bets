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
if(isset($_POST['action']) && $_POST['action'] == 'bet') {
	
	if($_POST['stake'] <= $session_user->points) {
	
		// Instanciation de l'objet
		$bet = new Bet();
		
		// Définition des propriétés
		$bet->user_id	= $session_user->id;
		$bet->datetime = $sql->escape($_POST['datetime']);
		$bet->match_id	= $sql->escape($_POST['match_id']);
		$bet->opponent_id	= $sql->escape($_POST['opponent_id']);
		$bet->stake	= $sql->escape($_POST['stake']);
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
	header('Location: bets.php');
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
		
		<!-- Modal -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
		
		<div id="myCarousel" class="carousel slide">
			<!-- Carousel items -->
			<div class="carousel-inner">
				<div class="active item" style="background:url('images/banners/razer.jpg');"></div>
				<?php
				$handler_matches = new Handler_Matches();
				$matches = $handler_matches->get_featured_matches();
				if(!empty($matches)) {
					foreach($matches as $match) {
						?>
				<div class="item" style="background:url('images/banners/<?php echo $match->banner; ?>');">
					<div class="banner-description">
						<a href="#myModal" class="btn btn-primary btn-left btn-odds" data-toggle="modal" data-id="<?php echo $match->opponents[1]->odds_id; ?>" data-match-id="<?php echo $match->id; ?>" data-opponent-id="<?php echo $match->opponents[1]->opponent_id; ?>">Bet 100 points on EG, win <?php echo (100 * $match->opponents[1]->value); ?></a>
						<a href="#myModal" class="btn btn-primary btn-right btn-odds" data-toggle="modal" data-id="<?php echo $match->opponents[0]->odds_id; ?>" data-match-id="<?php echo $match->id; ?>" data-opponent-id="<?php echo $match->opponents[0]->opponent_id; ?>">Bet 100 points on GG, win <?php echo (100 * $match->opponents[0]->value); ?>.</a>
					</div>
				</div>
						<?php	
					}
				}
				?>
			</div>
			<!-- Carousel nav -->
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
		</div>
		
		<?php
		// Affichage des notices
		$handler_notices->display_all();
		$handler_notices->clear();
		?>
		
		<div class="row">
			<div class="span6 block">
				<h2>Welcome to E-sport Bets</h2>
				<p><strong>E-sport Bets</strong> is an online betting site on e-sports competitions, with fake money. 
				During registration, you will be awarded <strong>1000 points for free</strong>. <br />
				Choose a match, bet on it and earn more points. With those points, you can <strong>order gamers stuff in our shop</strong>.</p>
			</div>
			<div class="span6 block">
				<h2>Register</h2>
				<p>Register for free (30 seconds long), receive 1000 Points and start betting !</p>
				<p><a class="btn btn-primary btn-large" href="register.php">Register now &raquo;</a></p>
			</div>
		</div>
		
		<div class="row">
			<div class="span12 block index-shop">
				<a href="#" class="see-all">see all products ></a>
				<h2>The shop</h2>
				<ul class="thumbnails">
					<li class="span3">
						<a class="thumbnail" href="#">
							<img src="images/products/souris-razer.jpg" alt="">
							<h4>Souris Razer Lachesis RZ01</h4>
							<p>1200 points</p>
						</a>
					</li>
					<li class="span3">
						<a class="thumbnail" href="#">
							<img src="images/products/souris-razer.jpg" alt="">
							<h4>Souris Razer Lachesis RZ01</h4>
							<p>1200 points</p>
						</a>
					</li>
					<li class="span3">
						<a class="thumbnail" href="#">
							<img src="images/products/souris-razer.jpg" alt="">
							<h4>Souris Razer Lachesis RZ01</h4>
							<p>1200 points</p>
						</a>
					</li>
					<li class="span3">
						<a class="thumbnail" href="#">
							<img src="images/products/souris-razer.jpg" alt="">
							<h4>Souris Razer Lachesis RZ01</h4>
							<p>1200 points</p>
						</a>
					</li>
				</ul>
			</div>
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

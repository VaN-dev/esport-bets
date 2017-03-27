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
	// Tabs
	$('#games-list a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
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
		
		<div class="row">
			
			<div class="span12">
				
				<h1>Bets</h1>
				
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
				
				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs" id="games-list">       
					<?php
					$handler_games = new Handler_Games();
					$games = $handler_games->get_list();
					if(!empty($games)) {
						foreach($games as $key => $game) {
							?>
						<li<?php echo $key == 0 ? ' class="active"' : ''; ?>><a href="#tab<?php echo $key ?>"><img src="images/<?php echo $game->icon; ?>" alt="" /> <?php echo stripslashes($game->name); ?></a></li>
							<?php
						}
					}
					?>
						<li><a href="#tab1"><img src="images/icon-sc2.png" alt="" /> Starcraft 2</a></li>
						<li><a href="#tab2"><img src="images/icon-dota2.png" alt="" /> DOTA 2</a></li>
					</ul>
					<div class="tab-content">
					
					<?php
					if(!empty($games)) {
						foreach($games as $key => $game) {
							?>
					
						<div class="tab-pane<?php echo $key == 0 ? ' active' : ''; ?>" id="tab<?php echo $key; ?>">
							<h2><?php echo stripslashes($game->name); ?> bets</h2>
							
							<div class="accordion" id="accordion1">
								
								<?php
								$handler_matches = new Handler_Matches();
								$dates = $handler_matches->get_coming_dates_from_game($game->id);
								if(!empty($dates)) {
									foreach($dates as $key_date => $date) {
										
										$matches = $handler_matches->get_matches_from_date($date['date']);
										
										if(!empty($matches)) {
											?>
								
								<div class="accordion-group">
								
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="" href="#collapse<?php echo $key_date; ?>"><?php echo ucwords($date['date_formatted']); ?> <span class="accordion-toggle-icon icon-arrow-down"></span></a>
									</div>
									<div id="collapse<?php echo $key_date; ?>" class="accordion-body collapse in">
										<div class="accordion-inner">
											<table class="table table-hover">
												<thead>
													<tr>
														<th></th>
														<th width="100">Start at</th>
														<th width="350">Team #1</th>
														<th width="100" colspan="2" style="text-align:center;">Odds</th>
														<th width="300" class="text-right">Team #2</th>
													</tr>
												</thead>
												<tbody>
											<?php
											foreach($matches as $key_match => $match) {
												?>
													<tr>
														<td width="50"><a href="match.php?id=<?php echo $match->id; ?>" title="comments"><img src="images/icon-chat.jpg" alt="" class="icone-chat" /></a></td>
														<td class="vertical-middle"><?php echo $match->start_formatted; ?></td>
														<td class="vertical-middle"><?php echo stripslashes($match->opponents[0]->name); ?></td>
														<td><a href="#myModal" role="button" class="btn-odds btn btn-success btn-block" data-toggle="modal" data-id="<?php echo $match->opponents[0]->odds_id; ?>" data-match-id="<?php echo $match->id; ?>" data-opponent-id="<?php echo $match->opponents[0]->opponent_id; ?>"><?php echo $match->opponents[0]->value; ?></a></td>
														<td><a href="#myModal" role="button" class="btn-odds btn btn-warning btn-block" data-toggle="modal" data-id="<?php echo $match->opponents[1]->odds_id; ?>" data-match-id="<?php echo $match->id; ?>" data-opponent-id="<?php echo $match->opponents[1]->opponent_id; ?>"><?php echo $match->opponents[1]->value; ?></a></td>
														<td class="text-right vertical-middle"><?php echo stripslashes($match->opponents[1]->name); ?></td>
													</tr>
												<?php
												
											}
											?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								
											<?php
										}
										
									}
								}
								?>
								
							</div>
								
						</div>
							<?php
						}
					}
					?>
						<div class="tab-pane" id="tab1">
							<h2>Starcraft 2 bets</h2>
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="400">Team #1</th>
										<th width="50"></th>
										<th width="50"></th>
										<th width="400" class="text-right">Team #2</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="vertical-middle">Stephano</td>
										<td><button class="btn btn-success btn-block">1.2</button></td>
										<td><button class="btn btn-warning btn-block">1.8</button></td>
										<td class="text-right vertical-middle">TOD</td>
									</tr>
									<tr>
										<td class="vertical-middle">Select</td>
										<td><button class="btn btn-success btn-block">1.5</button></td>
										<td><button class="btn btn-warning btn-block">2.31</button></td>
										<td class="text-right vertical-middle">TLO</td>
									</tr>
									<tr>
										<td class="vertical-middle">DeMusliM</td>
										<td><button class="btn btn-warning btn-block">3.5</button></td>
										<td><button class="btn btn-success btn-block">1.3</button></td>
										<td class="text-right vertical-middle">Showtime</td>
									</tr>
									<tr>
										<td class="vertical-middle">MoMaN</td>
										<td><button class="btn btn-success btn-block">1.15</button></td>
										<td><button class="btn btn-warning btn-block">5.6</button></td>
										<td class="text-right vertical-middle">Goody</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<div class="tab-pane" id="tab2">
							<h2>DOTA 2 bets</h2>
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="400">Team #1</th>
										<th width="50"></th>
										<th width="50"></th>
										<th width="400" class="text-right">Team #2</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

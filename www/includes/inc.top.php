<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?php echo $config->site_url; ?>/"><img src="images/logo.png" alt="" /> <?php echo $site->_s($config->site_name); ?></a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li class="active"><a href="index.php">Home</a></li>
					<li><a href="bets.php">Bets</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="contact.php">Contact</a></li>
				</ul>
				
				
				<?php
				if($session->is_authed()) {
					$dates_open = $session_user->open_bets();
					$dates_unnoticed = $session_user->completed_unnoticed_bets();
					?>
				<div class="pull-right" id="navbar-profile">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropdown-user-trigger">Welcome <?php echo stripslashes($session_user->firstname); ?></a>, 
					you currently have <?php echo $session_user->points; ?> points. <a href="index.php?action=logout">Log out</a>
					
					
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" id="dropdown-user">
						<li>
							<a href="account.php">My account</a>
						</li>
						<li class="divider"></li>
					<?php
					if(!empty($dates_open)) {
						?>
						<li>
							<p><strong>Your current bets</strong></p>
						<?php
						foreach($dates_open as $date => $bets) {
							?>
						
							<div class="dates"><?php echo $date; ?></div>
							<table class="table table-hover">
								<thead>
								<tr>
									<th>Start</th>
									<th>Choice</th>
									<th>Odds</th>
									<th>Stake</th>
									<th>Earnings</th>
								</tr>
								</thead>
								<tbody>
							<?php
							foreach($bets as $bet) {
								?>
									<tr>
										<td><?php echo $bet->match_start_formatted; ?></td>
										<td><?php echo $bet->opponent_name; ?></td>
										<td><?php echo $bet->odds_value; ?></td>
										<td><?php echo $bet->stake; ?></td>
										<td><?php echo $bet->odds_value * $bet->stake; ?></td>
									</tr>
								<?php
							}
							?>
								</tbody>
							</table>
							<?php
						}
						?>
						</li>
						<?php
					}
					?>
					
					<?php
					if(!empty($dates_unnoticed)) {
						?>
						<li>
							<p><strong>Completed matches</strong></p>
						<?php
						foreach($dates_unnoticed as $date => $bets) {
							?>
						
							<div class="dates"><?php echo $date; ?></div>
							<table class="table table-hover">
								<thead>
								<tr>
									<th>End</th>
									<th>Match</th>
									<th>Winner</th>
									<th>Earnings</th>
								</tr>
								</thead>
								<tbody>
							<?php
							foreach($bets as $bet) {
								$match = new Match($bet->match_id);
								?>
									<tr>
										<td><?php echo $bet->match_end_formatted; ?></td>
										<td><strong><?php echo $match->opponents[0]->name; ?></strong> vs <strong><?php echo $match->opponents[1]->name; ?></strong></td>
										<td><strong><?php echo stripslashes($bet->winner_name); ?></strong></td>
										<td>
								<?php 
								if($bet->opponent_id == $bet->winner_id) {
									?>
									<span class="earnings-container"><strong><?php echo $bet->odds_value * $bet->stake; ?></strong> points</span>
									<?php
								}
								?>
										</td>
									</tr>
								<?php
							}
							?>
								</tbody>
							</table>
							<?php
						}
						?>
						</li>
						<?php
					}
					?>
					</ul>
						
				</div>
					<?php
				}
				else {
					?>
				<form class="navbar-form pull-right" id="navbar-form" method="post">
					<input class="span2" type="text" placeholder="Email" name="mail">
					<input class="span2" type="password" placeholder="Password" name="password">
					<button type="submit" class="btn">Sign in</button>
					<input type="hidden" name="action" value="login" />
				</form>
					<?php
					
				}
				?>
			</div>
		</div>
	</div>
</div>
<div id="push"></div>
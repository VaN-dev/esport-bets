<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="span4">
				<h5>Bets</h5>
				<ul>
				<?php
				$handler_games = new Handler_Games();
				$games = $handler_games->get_list();
				if(!empty($games)) {
					foreach($games as $key => $game) {
						?>
					<li><span class="icon-chevron-right"></span> <a href="#"><?php echo stripslashes($game->name); ?></a></li>
						<?php
					}
				}
				?>
				</ul>
			</div>
			<div class="span4">
				<h5>About us</h5>
				<ul>
					<li><span class="icon-chevron-right"></span> <a href="#">Nulla quis iaculis mattis</a></li>
					<li><span class="icon-chevron-right"></span> <a href="#">Risus quam</a></li>
					<li><span class="icon-chevron-right"></span> <a href="#">Porttitor quam</a></li>
					
				</ul>
			</div>
			<div class="span4">
				<h5>What's coming next ?</h5>
				<ul>
					<li><span class="icon-chevron-right"></span> <a href="#">Nulla quis iaculis mattis</a></li>
					<li><span class="icon-chevron-right"></span> <a href="#">Risus quam</a></li>
					<li><span class="icon-chevron-right"></span> <a href="#">Et gravida tortor augue</a></li>
					<li><span class="icon-chevron-right"></span> <a href="#">Suspendisse ipsum nisi</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>
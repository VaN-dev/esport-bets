<div class="container">

    <div class="row">
      	
      	<div class="span12">
      		
			<div class="widget stacked">
				
				<div class="widget-header">
					<i class="icon-th-large"></i>
					<h3>Matches</h3>
					
					<a href="index.php" class="btn btn-add">Retour</a>
				</div>
				<div class="widget-content">
				
<?php
if(isset($_GET['action']) && ($_GET['action'] == 'insert' || $_GET['action'] ==  'update'))
{
	if($_GET['action'] == 'update' && is_numeric($_GET['id']))
	{
		$clean['id'] = mysql_real_escape_string($_GET['id']);

		$data = new Match($clean['id']);
	}
	?>
					<form action="index.php" id="validation-form" class="match-form" method="post">
						<fieldset>
							
							
							<div class="control-group span3">
								<label class="control-label" for="game_id">Game</label>
								<div class="controls">
									<select id="game_id" name="game_id">
										<option value="">Select...</option>
	<?php
	$handler_games = new Handler_Games();
	$games = $handler_games->get_list();
	if(!empty($games)) {
		foreach($games as $game) {
			$game->id == $data->game_id ? $selected = ' selected="selected"' : $selected = '';
			?>
										<option value="<?php echo $game->id; ?>"<?php echo $selected; ?>><?php echo stripslashes($game->name); ?></option>
			<?php
		}
	}
	?>
									</select>
								</div>
							</div>
						    <div class="control-group span3">
								<label class="control-label" for="start">Start</label>
								<div class="controls input-append">
									<input type="text" class="input datepicker" name="start" id="start" value="<?php if(isset($data)) echo substr($data->start, 0, -3); ?>">
									<label for="start" class="add-on"><i class="icon-calendar"></i></label>
								</div>
						    </div>
							<div class="control-group span3">
								<label class="control-label" for="end">End</label>
								<div class="controls input-append">
									<input type="text" class="input datepicker" name="end" id="end" value="<?php if(isset($data)) echo substr($data->end, 0, -3); ?>">
									<label for="end" class="add-on"><i class="icon-calendar"></i></label>
								</div>
						    </div>
							
							<br class="clear-both" />
							
							<div class="control-group span3">
								<label class="control-label">Statut</label>
								<!--
								<div class="controls">
									<label class="radio">
										<input type="radio" name="statut" id="validateRadio1" value="1" <?php if(isset($data)) echo $data->statut == 1 ? ' checked="checked"' : ''; ?>>
										Activé
									</label>
									<label class="radio">
										<input type="radio" name="statut" id="validateRadio2" value="0" <?php if(isset($data)) echo $data->statut == 0 ? ' checked="checked"' : ''; ?>>
										Désactivé
									</label>
								</div>
								-->
								<div class="controls">
									<select id="statut" name="statut">
											<option value="">Select...</option>
											<option value="1"<?php if(isset($data) && $data->statut == 1) echo ' selected="selected"'; ?>>Activé</option>
											<option value="0"<?php if(isset($data) && $data->statut == 0) echo ' selected="selected"'; ?>>Désactivé</option>
											<option value="2"<?php if(isset($data) && $data->statut == 2) echo ' selected="selected"'; ?>>Fermé</option>
									</select>
								</div>
							</div>
							<div class="control-group span3">
								<label class="control-label" for="opponent_1_id">Opponent #1</label>
								<div class="controls">
									<select id="opponent_1_id" name="opponent_1_id">
										<option value="">Select...</option>
	<?php
	$handler_opponents = new Handler_Opponents();
	$opponents = $handler_opponents->get_list();
	if(!empty($opponents)) {
		foreach($opponents as $opponent) {
			$opponent->id == $data->opponents[0]->opponent_id ? $selected = ' selected="selected"' : $selected = '';
			?>
										<option value="<?php echo $opponent->id; ?>"<?php echo $selected; ?>><?php echo stripslashes($opponent->name); ?></option>
			<?php
		}
	}
	?>
									</select>
								</div>
							</div>
							<div class="control-group span3">
								<label class="control-label" for="opponent_2_id">Opponent #2</label>
								<div class="controls">
									<select id="opponent_2_id" name="opponent_2_id">
										<option value="">Select...</option>
	<?php
	$handler_opponents = new Handler_Opponents();
	$opponents = $handler_opponents->get_list();
	if(!empty($opponents)) {
		foreach($opponents as $opponent) {
			$opponent->id == $data->opponents[1]->opponent_id ? $selected = ' selected="selected"' : $selected = '';
			?>
										<option value="<?php echo $opponent->id; ?>"<?php echo $selected; ?>><?php echo stripslashes($opponent->name); ?></option>
			<?php
		}
	}
	?>
									</select>
								</div>
							</div>
							
							<br class="clear-both" />
							
							<div class="control-group span3">
								<label class="control-label">Page d'accueil</label>
								<div class="controls">
									<label class="radio">
										<input type="radio" name="featured" id="validateRadio1" value="1" <?php if(isset($data)) echo $data->featured == 1 ? ' checked="checked"' : ''; ?>>
										Oui
									</label>
									<label class="radio">
										<input type="radio" name="featured" id="validateRadio2" value="0" <?php if(isset($data)) echo $data->featured == 0 ? ' checked="checked"' : ''; ?>>
										Non
									</label>
								</div>
							</div>
							
							<hr class="clear-both" />
							
							<a class="btn float-right" id="add-odds">Ajouter une côte</a>
							<h4>Odds</h4>
							
							<br />
							
							<table class="table table-striped table-bordered" id="table-odds">
								<thead>
									<tr>
										<th>Date</th>
										<th>Odds #1</th>
										<th>Odds #2</th>
									</tr>
								</thead>
								<tbody>
	<?php
	if(isset($data)) {
		$odds = $data->get_all_odds();
		if(!empty($odds)) {
			foreach($odds as $odd) {
				?>
									<tr>
										<td><?php echo $odd->datetime; ?></td>
										<td><?php echo $odd->odds_1; ?></td>
										<td><?php echo $odd->odds_2; ?></td>
									</tr>
				<?php
			}
		}
		
	}
	else {
		?>
									<tr>
										<td></td>
										<td><input type="text" name="odds[new_odds_1]" class="input-small" value="1" /></td>
										<td><input type="text" name="odds[new_odds_2]" class="input-small" value="1" /></td>
									</tr>
		<?php
	}
	?>
								</tbody>
							</table>
							
						    <div class="form-actions">
	<?php
	if (isset($data)) {
		?>
								<input type="hidden" name="id" value="<?php echo $site->_s($data->id); ?>" />
		<?php
	}
	?>
								<input type="hidden" name="action" value="<?php echo $site->_s($_GET['action']); ?>" />
								<button type="submit" class="btn btn-large btn-primary">Validate</button>
						    </div>
						</fieldset>
					</form>
						
	<?php
}
else
{
	echo '<div class="notice notice-failure">L\'action demandée semble incorrecte. <a href="index.php">Retour à la liste</a></div>';
}
?>
				
				</div>
				
			</div>
      		
	    </div> <!-- /span12 -->
      	
    </div> <!-- /row -->

</div> <!-- /container -->
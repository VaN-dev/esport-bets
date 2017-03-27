<?php
$handler_matches = new Handler_Matches();
?>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Close match</h3>
	</div>
	<form class="bet-form" method="post">
		<div class="modal-body" id="modal-bet">
			<label for="stake">Winner:</label> 
			<select name="winner_id" id="winner_id">
			</select>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Close bet</button>
		</div>
		<input type="hidden" name="id" id="id" value="" />
		<input type="hidden" name="action" value="close" />
	</form>
</div>

<div class="container">

    <div class="row">
      	
		
      	<div class="span12">
		
      		<?php
			// Affichage des notices
			$handler_notices->display_all();
			$handler_notices->clear();
			?>
			
			<div class="widget stacked">
				
				<div class="widget-header">
					<i class="icon-th-large"></i>
					<h3>Matches</h3>
					
					<a href="index.php?action=insert" class="btn btn-primary btn-add">Ajouter un match</a>
				</div>
				
				
				
<?php
$handler_matches = new Handler_Matches();
$matches = $handler_matches->get_coming_matches('start');
		
if(!empty($matches)) {
	?>
				<div class="widget-content">
					
					<h4>A venir</h4>
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Date</th>
								<th>Time</th>
								<th>Opponent #1</th>
								<th>Odds #1</th>
								<th>Odds #2</th>
								<th>Opponent #2</th>
								<th width="140" class="td-actions"></th>
							</tr>
						</thead>
						<tbody>
	<?php
	foreach($matches as $key_match => $match) {
		?>
							<tr>
								<td><?php echo $match->start_date_formatted; ?></td>
								<td><?php echo $match->start_time_formatted; ?></td>
								<td><?php echo stripslashes($match->opponents[0]->name); ?></td>
								<td><?php echo $match->opponents[0]->value; ?></td>
								<td><?php echo $match->opponents[1]->value; ?></td>
								<td><?php echo stripslashes($match->opponents[1]->name); ?></td>
								<td class="td-actions">
									<a href="index.php?action=update&id=<?php echo $match->id; ?>" class="btn">Edit</a>
									<?php
									if($match->statut != 2) {
										?>
									<a href="#myModal" data-toggle="modal" data-id="<?php echo $match->id; ?>" class="btn btn-primary btn-close">Close</a>
										<?php
									}
									else {
										?>
									<a href="index.php?action=reopen&id=<?php echo $match->id; ?>" class="btn btn-primary">Re-open</a>
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
				
				</div>
				
			</div>
      <?php
}
?>
	    </div> <!-- /span12 -->
      	
    </div> <!-- /row -->

</div> <!-- /container -->
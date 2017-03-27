<h2>Liste des contacts</h2>

<br class="clear-both" /><br />

<?php
$results = $handler_contacts->get_list();

if(!empty($results))
{
	?>
	<table class="datatable display">
	  <thead>
		<tr>
	      <th width="40">#</th>
	      <th>Date</th>
		  <th>Nom</th>
		  <th>Mail</th>
		  <th width="70">Téléphone</th>
		  <th width="200">Actions</th>
		</tr>
	  </thead>
	  <tbody> 
		<?php
		foreach($results as $result)
		{
			$datetime = $site->datetime_sql2fr($result->datetime);
			?>
			<tr>
				<td><?php echo $site->_s($result->id); ?></td>
				<td><?php echo $datetime['date'] . ' ' . $datetime['time']; ?></td>
				<td><?php echo $site->_s($result->prenom_nom()); ?></td>
				<td><?php echo $site->_s($result->mail); ?></td>
				<td><?php echo $site->_s($result->telephone); ?></td>
				<td>
					<a href="index.php?action=display&item_id=<?php echo $site->_s($result->id); ?>" class="link-action"><img src="<?php echo ROOT_PATH; ?>core/images/icons/eye_16x16.png" alt="" class="icon" />Voir</a> 
					<a data-id="<?php echo $site->_s($result->id); ?>" class="link-action prompt-deletion"><img src="<?php echo ROOT_PATH; ?>core/images/icons/delete_16x16.png" alt="" class="icon" style="margin-left:20px;" />Supprimer</a>
				</td>
			</tr>
			<?php
		}
		?>
	  </tbody>
	</table>
	<?php
}
else
{
	echo '<p>Aucun contact à afficher.</p>';
}
?>
 
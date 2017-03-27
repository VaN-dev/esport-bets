<h2>Liste des administrateurs</h2>

<a href="index.php?action=insert" class="bouton float-right">Ajouter un utilisateur</a>

<br class="clear-both" /><br />

<?php
$results = $handler_admin_users->get_list_without_adveris();

if(!empty($results))
{
	?>
	<table class="datatable display">
		<thead>
			<tr>
			<th>Identifiant</th>
			<th>Prénom</th>
			<th>Nom</th>
			<th>Niveau</th>
			<th width="70">Statut</th>
			<th width="200">Actions</th>
			</tr>
		</thead>
		<tbody> 
		<?php
			foreach($results as $result)
			{
			?>
				<tr>
					<td><?php echo $site->_s($result->login); ?></td>
					<td><?php echo $site->_s($result->prenom); ?></td>
					<td><?php echo $site->_s($result->nom); ?></td>
					<td><?php echo $site->_s($result->level_name); ?></td>
					<td><a href="index.php?action=switch_statut&id=<?php echo $result->id; ?>"><?php echo $site->translate_status_into_img($site->_s($result->statut), 'admin_user'); ?></a></td>
					<td class="actions">
						<a href="index.php?action=update&item_id=<?php echo $site->_s($result->id); ?>" class="link-action"><img src="<?php echo ROOT_PATH; ?>core/images/icons/edit_16x16.png" alt="" class="icon" />Modifier</a> 
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
	echo '<p>Aucun utilisateur à afficher.</p>';
}
?>
 
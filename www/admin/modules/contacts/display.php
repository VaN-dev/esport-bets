<?php
if($_GET['action'] == 'display' && is_numeric($_GET['item_id']))
{
	$clean['item_id'] = mysql_real_escape_string($_GET['item_id']);

	$data = new Contact($clean['item_id']);
	
	$datetime = $site->datetime_sql2fr($data->datetime);
	?>

	<h2><?php echo ($_GET['action'] == 'display') ? 'Afficher' : ''; ?> un contact</h2>
	<form name="form-edit" method="post" enctype="multipart/form-data">
		<fieldset class="large-labels">

			<div class="input-container clearfix">
				<label for="nom">Date  :</label>
				<span><?php echo $datetime['date'] . ' ' . $datetime['time']; ?></span>
			</div>

			<div class="input-container clearfix">
				<label for="nom">Nom  :</label>
				<span><?php echo $site->_s($data->prenom_nom()); ?></span>
			</div>

			<div class="input-container clearfix">
				<label for="telephone">Téléphone  :</label>
				<span><?php echo $site->_s($data->telephone); ?></span>
			</div>

			<div class="input-container clearfix">
				<label for="mail">E-mail  :</label>
				<span><?php echo $site->_s($data->mail); ?></span>
			</div>

			<div class="input-container clearfix">
				<label for="menu_affichage">Message :</label>
				<span><?php echo $site->_s(nl2br($data->message)); ?></span>
			</div>

		</fieldset>
	</form>
	<?php
}
else
{
	echo '<div class="notice notice-failure">L\'identifiant de l\'élément à afficher est incorrect. <a href="index.php">Retour à la liste</a></div>';
}
?>
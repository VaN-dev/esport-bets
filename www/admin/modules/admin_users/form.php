<?php
if(isset($_GET['action']) && ($_GET['action'] == 'insert' || $_GET['action'] ==  'update'))
{
	if($_GET['action'] == 'update' && is_numeric($_GET['item_id']))
	{
		$clean['item_id'] = mysql_real_escape_string($_GET['item_id']);

		$data = new Admin_User($clean['item_id']);
	}
	?>
	<a href="index.php" class="bouton float-right">Retour à la liste</a>		
		
	<h2 class="clearfix"><?php echo ($_GET['action'] == 'insert') ? 'Ajouter' : 'Modifier'; ?> un administrateur</h2>
	<form name="form-edit" method="post" enctype="multipart/form-data">
		
		<fieldset class="large-labels">
		
			<div class="input-container">
				<label for="login">Identifiant <span class="required-field">*</span> :</label>
				<input type="text" name="login" id="login" class="required medium" value="<?php if(isset($data)) { echo htmlentities($site->_s($data->login), ENT_COMPAT, 'UTF-8'); } ?>" />
			</div>
			
			<div class="input-container">
				<label for="password">Mot de passe :</label>
				<input type="password" name="password" id="password" class="required medium" autocomplete="off" /> (Laissez vide si inchangé)
			</div>
			
			<div class="input-container">
				<label for="level">Niveau <span class="required-field">*</span> :</label>
				<select name="level" id="level" class="required medium">
				<?php
				$levels = Sql::get_array_from_query("SELECT id, name FROM " . TABLES__ADMIN_USERS_LEVELS);
				foreach($levels as $row)
				{
					isset($data) && $data->level == $row['id'] ? $selected = ' selected="selected"' : $selected = '';
					echo '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>';
				}
				?>
				</select>
			</div>
			
			<div class="input-container">
				<label for="prenom">Prénom <span class="required-field">*</span> :</label>
				<input type="text" name="prenom" id="prenom" class="required medium" value="<?php if(isset($data)) { echo htmlentities($site->_s($data->prenom), ENT_COMPAT, 'UTF-8'); } ?>" />
			</div>
			
			<div class="input-container">
				<label for="nom">Nom <span class="required-field">*</span> :</label>
				<input type="text" name="nom" id="nom" class="required medium" value="<?php if(isset($data)) { echo htmlentities($site->_s($data->nom), ENT_COMPAT, 'UTF-8'); } ?>" />
			</div>
			
			<div class="input-container">
				<label for="statut">Statut <span class="required-field">*</span> :</label>
				<select name="statut" id="statut" class="required medium">
				<?php
				$statuts = Sql::get_array_from_query("SELECT statut, name FROM " . TABLES__STATUTS . " WHERE type = 'admin_user' ORDER BY statut DESC");
				foreach($statuts as $row)
				{
					isset($data) && $data->statut == $row['statut'] ? $selected = ' selected="selected"' : $selected = '';
					echo '<option value="' . $row['statut'] . '"' . $selected . '>' . $row['name'] . '</option>';
				}
				?>
				</select>
			</div>
			
			<div class="input-container">
				<label>&nbsp;</label>
				<?php if(isset($data)) { ?><input type="hidden" name="id" value="<?php echo $site->_s($data->id); ?>" /><?php } ?>
				<input type="hidden" name="action" value="<?php echo $site->_s($_GET['action']); ?>" />
				<input type="submit" name="btn-submit" value="Valider" class="bouton" />
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
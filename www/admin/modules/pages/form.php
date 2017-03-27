<?php
function build_select_option($pages, $parent_id, $level = 0)
{
	foreach ($pages as $page)
	{
		$indent = '';
		for ($i = 0; $i < $level; $i++)
		{
			$indent .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		
		$page->id == $parent_id ? $selected = ' selected="selected"' : $selected = '';
		echo '<option value="' . $page->id . '"' . $selected . '>' . $indent . $page->_s($page->titre) . '</option>';
		
		if (isset($page->children) && !empty($page->children))
		{
			build_select_option($page->children, $parent_id, $level + 1);
		}
	}
}

if (isset($_GET['action']) && ($_GET['action'] == 'insert' || $_GET['action'] ==  'update'))
{
	if ($_GET['action'] == 'update' && is_numeric($_GET['item_id']))
	{
		$clean['item_id'] = mysql_real_escape_string($_GET['item_id']);
		$data = new Page($clean['item_id']);
	}
	?>

	<h2><?php echo ($_GET['action'] == 'insert') ? 'Ajouter' : 'Modifier'; ?> une page</h2>
	<form name="form-edit" method="post" enctype="multipart/form-data">
		<fieldset class="large-labels">

			<div class="input-container">
				<label for="parent_id">Page parent <span class="required-field">*</span> :</label>
				<select name="parent_id" id="parent_id" class="extralarge">
					<option value="0">Aucune</option>
					<?php
					$pages = $handler_pages->get_list_for_menu_admin();
					build_select_option($pages, $data->parent_id, 0);
					?>
				</select>
			</div>

			<div class="input-container">
				<label for="titre">Titre <span class="required-field">*</span> :</label>
				<input type="text" name="titre" id="titre" class="required extralarge" value="<?php if(isset($data)) { echo htmlentities($site->_s($data->titre), ENT_COMPAT, 'UTF-8'); } ?>" />
			</div>

			<div class="input-container">
				<label for="introduction">Introduction <span class="required-field">*</span> :</label>
				<textarea name="introduction" id="introduction" class="redactor" style="height:70px;"><?php if(isset($data)) { echo htmlentities($site->_s($data->introduction), ENT_COMPAT, 'UTF-8'); } ?></textarea>
			</div>

			<div class="input-container">
				<label for="contenu">Contenu <span class="required-field">*</span> :</label>
				<textarea name="contenu" id="contenu" class="redactor"><?php if(isset($data)) { echo htmlentities($site->_s($data->contenu), ENT_COMPAT, 'UTF-8'); } ?></textarea>
			</div>

			<div class="input-container">
				<label for="menu_affichage">Affichage dans le menu <span class="required-field">*</span> :</label>
				<select name="menu_affichage" id="menu_affichage" class="medium">
					<option value="1"<?php if(isset($data) && $data->menu_affichage == 1) echo ' selected="selected"'; ?>>Oui</option>
					<option value="0"<?php if(isset($data) && $data->menu_affichage == 0) echo ' selected="selected"'; ?>>Non</option>
				</select>
				<span id="menu_position-container"<?php if(isset($data) && $data->menu_affichage == 0) { echo ' style="display:none;"'; }; ?>>&nbsp;en position : <input type="text" name="menu_position" id="menu_position" class="small" value="<?php if(isset($data)) { echo $site->_s($data->menu_position); } ?>" /></span>
			</div>

			<div class="input-container">
				<label for="statut">Statut <span class="required-field">*</span> :</label>
				<select name="statut" id="statut" class="required medium">
				<?php
				$statuts = Sql::get_array_from_query("SELECT statut, name FROM " . TABLES__STATUTS . " WHERE type = 'pages' ORDER BY statut DESC");
				foreach($statuts as $row) {
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
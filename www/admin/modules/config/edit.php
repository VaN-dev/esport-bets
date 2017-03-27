<?php
if($session_admin_user->id == 1) {
	$config = new Config(1);
	?>

<form action="index.php" name="form-edit" method="post" enctype="multipart/form-data">

	<h2>Site</h2>

	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="site_name">Nom du site :</label>
			<input type="text" name="site_name" id="site_name" class="large" value="<?php echo htmlentities($site->_s($config->site_name), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="site_url">URL du site :</label>
			<input type="text" name="site_url" id="site_url" class="large" value="<?php echo htmlentities($site->_s($config->site_url), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="site_maintenance">Site en maintenance :</label>
			<div style="float: left;">
				<input type="radio" name="site_maintenance" value="0" id="site_maintenance_0" style="float:left;" <?php if($config->site_maintenance == 0) echo 'checked="checked"'; ?> /> <label for="site_maintenance_0" style="float:left; width:auto;">Non</label> 
				<input type="radio" name="site_maintenance" value="1" id="site_maintenance_1" style="float:left;" <?php if($config->site_maintenance == 1) echo 'checked="checked"'; ?> /> <label for="site_maintenance_1" style="float:left; width:auto;">Oui</label>
			</div>
			<br class="clear-both" />
		</div>
		<div class="input-container" id="input-container-site_maintenance_message"<?php if($config->site_maintenance == 0) { echo ' style="display:none;"'; } ?>>
			<label for="site_maintenance_message">Message de maintenance :</label>
			<textarea name="site_maintenance_message" class="tinymce" style="height:150px;"><?php echo htmlentities($site->_s($config->site_maintenance_message), ENT_COMPAT, 'UTF-8'); ?></textarea>
		</div>
	</fieldset>
	
	<h2>Référencement</h2>
	
	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="site_title">Titre du site :</label>
			<input type="text" name="site_title" id="site_title" class="extralarge" value="<?php echo htmlentities($site->_s($config->site_title), ENT_COMPAT, 'UTF-8'); ?>" maxlength="66" /> <span class="color-grey">(66 caractères maximum, <strong><span id="site_title_chars"><?php echo 66 - strlen(stripslashes($config->site_title)); ?></span> restants</strong>)</span>
		</div>
		<div class="input-container">
			<label for="site_description">Description du site :</label>
			<textarea name="site_description" id="site_description" style="height:30px;"><?php echo htmlentities($site->_s($config->site_description), ENT_COMPAT, 'UTF-8'); ?></textarea> <span class="color-grey">(156 caractères maximum, <strong><span id="site_description_chars"><?php echo 156 - strlen(stripslashes($config->site_description)); ?></span> restants</strong>)</span>
		</div>
		<div class="input-container">
			<label for="site_keywords">Mots-clés du site :</label>
			<input type="text" name="site_keywords" id="site_keywords" class="extralarge" value="<?php echo htmlentities($site->_s($config->site_keywords), ENT_COMPAT, 'UTF-8'); ?>" /> <span class="color-grey">(séparés par des virgules)</span>
		</div>
	</fieldset>
	
	<h2>Contact</h2>
	
	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="contact_tel">Téléphone :</label>
			<input type="text" name="contact_tel" id="contact_tel" class="large" value="<?php echo htmlentities($site->_s($config->contact_tel), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="contact_fax">Fax :</label>
			<input type="text" name="contact_fax" id="contact_fax" class="large" value="<?php echo htmlentities($site->_s($config->contact_fax), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="contact_mail">Mail :</label>
			<input type="text" name="contact_mail" id="contact_mail" class="large" value="<?php echo htmlentities($site->_s($config->contact_mail), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
	</fieldset>
	
	<h2>Infos entreprise (mentions légales)</h2>
	
	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="company_name">Nom :</label>
			<input type="text" name="company_name" id="company_name" class="large" value="<?php echo htmlentities($site->_s($config->company_name), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_address">Adresse :</label>
			<input type="text" name="company_address" id="company_address" class="large" value="<?php echo htmlentities($site->_s($config->company_address), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_zipcode">Code postal :</label>
			<input type="text" name="company_zipcode" id="company_zipcode" class="large" value="<?php echo htmlentities($site->_s($config->company_zipcode), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_city">Ville :</label>
			<input type="text" name="company_city" id="company_city" class="large" value="<?php echo htmlentities($site->_s($config->company_city), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_country">Pays :</label>
			<input type="text" name="company_country" id="company_country" class="large" value="<?php echo htmlentities($site->_s($config->company_country), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_legal_status">Forme juridique :</label>
			<input type="text" name="company_legal_status" id="company_legal_status" class="large" value="<?php echo htmlentities($site->_s($config->company_legal_status), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_capital">Capital :</label>
			<input type="text" name="company_capital" id="company_capital" class="large" value="<?php echo htmlentities($site->_s($config->company_capital), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_siren">SIREN :</label>
			<input type="text" name="company_siren" id="company_siren" class="large" value="<?php echo htmlentities($site->_s($config->company_siren), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="company_responsable">Responsable :</label>
			<input type="text" name="company_responsable" id="company_responsable" class="large" value="<?php echo htmlentities($site->_s($config->company_responsable), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
	</fieldset>

	<h2>Google</h2>

	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="analytics_key"><img src="<?php echo ROOT_PATH; ?>core/images/icons/google_16x16.png" alt="" class="icon" /> Clé Analytics :</label>
			<input type="text" name="analytics_key" id="analytics_key" class="large" value="<?php echo htmlentities($site->_s($config->analytics_key), ENT_COMPAT, 'UTF-8'); ?>" /> <span class="color-grey">(UA-XXXX-X)</span>
		</div>
	</fieldset>

	<h2>Social</h2>

	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="social_facebook"><img src="<?php echo ROOT_PATH; ?>core/images/icons/facebook_16x16.png" alt="" class="icon" /> Page facebook :</label>
			<input type="text" name="social_facebook" id="social_facebook" class="large" value="<?php echo htmlentities($site->_s($config->social_facebook), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
		<div class="input-container">
			<label for="social_twitter"><img src="<?php echo ROOT_PATH; ?>core/images/icons/twitter_16x16.png" alt="" class="icon" /> Compte Twitter :</label>
			<input type="text" name="social_twitter" id="social_twitter" class="large" value="<?php echo htmlentities($site->_s($config->social_twitter), ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
	</fieldset>

	<div<?php if($session_admin_user->id != 1) echo ' style="display:none;"'; ?>>

		<h2>Divers</h2>
		
		<fieldset class="medium-labels">
			<div class="input-container">
				<label for="site_build_xml">Flux XML :</label>
				<select name="site_build_xml" id="site_build_xml" class="large">
				<?php
				$available_choices = array(0 => 'Non', 1 => 'Oui');
				foreach($available_choices as $available_choice => $label)
				{
					$available_choice == $config->site_build_xml ? $selected = ' selected="selected"' : $selected = '';
					echo '<option value="' . $available_choice . '"' . $selected . '>' . $label . '</option>';
				}
				?>
				</select>
				<?php
				if($config->site_build_xml == 1)
				{
					?>
					<span class="color-grey" style="margin:0 0 0 10px;"><a href="<?php echo $site->_s($config->site_url); ?>/xml/actualites.xml" target="_blank" class="color-grey"><img src="<?php echo ROOT_PATH; ?>core/images/icons/file_xml_16x16.png" alt="" class="icon" /><?php echo $site->_s($config->site_url); ?>/xml/actualites.xml</a></span>
					<?php
				}
				?>
			</div>
			
			<?php
			$carousels_types = array(
				'lofslider'	=> 'LoF Slider',
				'slides'	=> 'Slides',
			);
			?>
			<div class="input-container">
				<label for="layout_carousel_homepage_type">Carrousel Homepage :</label>
				<select name="layout_carousel_homepage_type" id="layout_carousel_homepage_type" class="large">
					<option value="">Aucun</option>
					<?php
					foreach($carousels_types as $key_type => $carousel_type)
					{
						$key_type == $config->layout_carousel_homepage_type ? $selected = ' selected="selected"' : $selected = '';
						echo '<option value="' . $key_type . '"' . $selected . '>' . $carousel_type . '</option>';
					}
					?>
				</select>
			</div>
			
			<?php
			$carousels = array(
				1 => 'Photo + texte, défilement horizontal, pagination évoluée horizontale',
				2 => 'Photo + texte, défilement horizontal, pagination évoluée verticale',
				3 => 'Photo + texte, défilement horizontal, pagination simplifiée horizontale',
				4 => 'Photo, défilement horizontal, pagination simplifiée horizontale',
			);
			?>
			<div class="input-container">
				<label for="layout_carousel_homepage_id">Style Carrousel :</label>
				<select name="layout_carousel_homepage_id" id="layout_carousel_homepage_id" class="large">
					<option value="">Aucun</option>
					<?php
					foreach($carousels as $key_id => $carousel)
					{
						$key_id == $config->layout_carousel_homepage_id ? $selected = ' selected="selected"' : $selected = '';
						echo '<option value="' . $key_id . '"' . $selected . '>' . $carousel . '</option>';
					}
					?>
				</select>
			</div>
			
			<div class="input-container">
				<label for="layout_carousel_galeries">Carrousel galeries :</label>
				<select name="layout_carousel_galeries" id="layout_carousel_galeries" class="large">
					<?php
					foreach($carousels as $key_carousel => $carousel)
					{
						$key_carousel == $config->layout_carousel_galeries ? $selected = ' selected="selected"' : $selected = '';
						echo '<option value="' . $key_carousel . '"' . $selected . '>' . $carousel . '</option>';
					}
					?>
				</select>
			</div>
		</fieldset>
	</div>
	
	<fieldset class="medium-labels">
		<div class="input-container">
			<label for="btn-submit">&nbsp;</label>
			<input type="hidden" name="action" value="edit" />
			<input type="submit" name="btn-submit" id="btn-submit" value="Enregistrer les modifications" class="bouton" />
		</div>
	</fieldset>
</form>
	<?php
}
else
{
	echo '<div class="notice notice-failure">Vous n\'avez pas accès à ce module.</div>';
}
?>
<?php
if(!isset($target)) {
	if($current_page == 'login.php') {
		$target = 'account.php';
	}
	else {
		$target = $_SERVER['REQUEST_URI'];
	}
}
?>

<form action="<?php echo $target; ?>" method="post">
	
	<fieldset class="small-labels">
		<p>
			<label for="mail">E-mail :</label>
			<input type="text" name="mail" id="mail" class="required medium" /> 
		</p>
		<p>
			<label for="password">Password :</label>
			<input type="password" name="password" id="password" class="required medium" /> 
		</p>
		<p>
			<label for="btn-submit">&nbsp;</label>
			<input type="hidden" name="subaction" value="login" />
			<input type="submit" name="btn-submit" value="Log in" class="btn btn-primary btn-large" />
		</p>
		
		<?php
		// $site->print_r_pre($_POST);
		foreach($_POST as $post_field => $post_value) {
			echo '<input type="hidden" name="' . $post_field . '" value ="' . $post_value . '" />';
		}
		?>
	</fieldset>
	
</form>
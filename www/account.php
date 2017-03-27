<?php
##############################################################
## Définitions des chemins d'accès à / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE('ROOT_PATH', '');
DEFINE('ADMIN_PATH', ROOT_PATH . 'admin/');
require_once(ROOT_PATH . 'init.php');

$cryptinstall = ROOT_PATH . "core/tools/cryptographp/cryptographp.fct.php";
include $cryptinstall; 

##############################################################
## Définitions des scripts/styles à charger pour la page	##
##############################################################
$scripts_to_load = array();
$styles_to_load = array();

##############################################################
## Traitements PHP											##
##############################################################
if(isset($_POST['action']) && $_POST['action'] == 'profile') {
	
	// Définition des propriétés
	$session_user->mail = $_POST['register_mail'];
	$session_user->firstname = $_POST['register_firstname'];
	$session_user->birthdate = $_POST['register_birthday_year'] . '-' . $_POST['register_birthday_month'] . '-' . $_POST['register_birthday_day'];
	
	if(!empty($_POST['register_password'])) {
		if($_POST['register_password'] == $_POST['register_password_confirm']) {
			$session_user->password = $_POST['register_password'];
		}
		else {
			$notice = new Notice('warning', 'Password and confirmation don\'t match.');
			$notice->sessionize();
		}
	}
	
	// Update
	$session_user->edit();
	
	$notice = new Notice('success', 'Your profile was successfully edited.');
	$notice->sessionize();
	
	header('Location: account.php');
	exit();
	
}

##############################################################
## Chargement du header										##
##############################################################
require_once(ROOT_PATH . 'includes/inc.head.php');
?>
<script type="text/javascript"> 
$(function() {
	// Tabs
	$('#account-list a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
});
</script>
</head>

<body>

<div id="wrap">

	<?php
	require_once(ROOT_PATH . 'includes/inc.top.php');
	?>
	
	<div id="main" class="container clear-top">
		
		<div class="row">
			
			<div class="span12">
			
				<h1>Account</h1>
					
				<?php
				// Affichage des notices
				$handler_notices->display_all();
				$handler_notices->clear();
				?>
				
				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs" id="account-list"> 
						<li><a href="#tab0">Profile</a></li>
						<li><a href="#tab1">Bets</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab0">
							<h2>Profile</h2>
							<form method="post" class="register-form">
								<fieldset>
									<label>E-mail <span class="required-star">*</span></label>
									<div class="input-prepend">
										<span class="add-on"><i class="icon-envelope"></i></span>
										<input type="text" name="register_mail" id="register_mail" class="span3" placeholder="E-mail" value="<?php echo htmlentities(stripslashes($session_user->mail), ENT_COMPAT, 'UTF-8'); ?>">
									</div>
									
									<label>Firstname <span class="required-star">*</span></label>
									<div class="input-prepend">
										<span class="add-on"><i class="icon-user"></i></span>
										<input type="text" name="register_firstname" id="register_firstname" class="span2" placeholder="Firstname" value="<?php echo htmlentities(stripslashes($session_user->firstname), ENT_COMPAT, 'UTF-8'); ?>">
									</div>
									
									<label>Password <span class="required-star">*</span></label>
									<div class="input-prepend">
										<span class="add-on"><i class="icon-lock"></i></span>
										<input type="password" name="register_password" id="register_password" class="span2" placeholder="Password">
									</div>
									
									<label>Confirm password <span class="required-star">*</span></label>
									<div class="input-prepend">
										<span class="add-on"><i class="icon-lock"></i></span>
										<input type="password" name="register_password_confirm" id="register_password_confirm" class="span2" placeholder="Confirm password">
									</div>
									
									<label>Birthday <span class="required-star">*</span></label>
									<?php
									$birthdate = explode('-', $session_user->birthdate);
									?>
									<div class="input-prepend">
										<span class="add-on"><i class="icon-calendar"></i></span>
										<select name="register_birthday_month" id="register_birthday_month" class="span1 grouped-selector-first">
											<?php
											for($m = 1; $m <= 12; $m++) {
												$birthdate[1] == $m ? $selected = ' selected="selected"' : $selected = '';
												echo '<option value="' . $m . '"' . $selected . '>' . date('M', mktime(0, 0, 0, $m)) . '</option>';
											}
											?>
											
										</select>
										<select name="register_birthday_day" id="register_birthday_day" class="span1 grouped-selector-middle">
											<?php
											for($d = 1; $d <= 31; $d++) {
												$birthdate[2] == $d ? $selected = ' selected="selected"' : $selected = '';
												echo '<option value="' . $d . '"' . $selected . '>' . str_pad($d, 2, '0', STR_PAD_LEFT) . '</option>';
											}
											?>
										</select>
										<select name="register_birthday_year" id="register_birthday_year" class="span1 grouped-selector-last">
											<?php
											for($y = date('Y'); $y >= date('Y') - 100; $y--) {
												$birthdate[0] == $y ? $selected = ' selected="selected"' : $selected = '';
												echo '<option value="' . $y . '"' . $selected . '>' . $y . '</option>';
											}
											?>
										</select>
									</div>
									
									<br />
									
									<input type="hidden" name="action" value="profile" />
									<button type="submit" class="btn btn-primary">Submit</button>
								</fieldset>
							</form>
						</div>
						<div class="tab-pane" id="tab1">
							<h2>Bets</h2>
							<?php
							$dates = $session_user->all_bets();
							if(!empty($dates)) {
								foreach($dates as $key_date => $bets) {
									?>
							<div class="accordion-group">
								
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="" href="#collapse<?php echo $key_date; ?>"><?php echo date('F jS Y', $site->date_sql2timestamp($key_date)); ?> <span class="accordion-toggle-icon icon-arrow-down"></span></a>
									</div>
									<div id="collapse<?php echo $key_date; ?>" class="accordion-body collapse in">
										<div class="accordion-inner">
											<table class="table table-hover">
												<thead>
													<tr>
														<th width="100">Start at</th>
														<th>Opponents</th>
														<th>Choice</th>
														<th>Winner</th>
														<th>Stake</th>
														<th>Odds</th>
														<th>Earnings</th>
													</tr>
												</thead>
												<tbody>
											<?php
											foreach($bets as $key_bet => $bet) {
												$match = new Match($bet->match_id);
												?>
													<tr>
														<td><?php echo $bet->match_start_formatted; ?></td>
														<td><?php echo stripslashes($match->opponents[0]->name); ?> vs <?php echo stripslashes($match->opponents[1]->name); ?></td>
														<td><?php echo stripslashes($bet->opponent_name); ?></td>
														<td><?php echo $bet->winner_id ? stripslashes($bet->winner_name) : '-'; ?></td>
														<td><?php echo $bet->stake; ?></td>
														<td><?php echo $bet->odds_value; ?></td>
														<td><?php if(!$bet->winner_id) echo '-'; elseif($bet->winner_id == $bet->opponent_id) echo ($bet->stake * $bet->odds_value); else echo 0; ?></td>
													</tr>
												<?php
												
											}
											?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
									<?php
								}
							}
							?>
							
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

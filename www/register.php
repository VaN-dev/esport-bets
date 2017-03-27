<?php
##############################################################
## D�finitions des chemins d'acc�s � / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE('ROOT_PATH', '');
DEFINE('ADMIN_PATH', ROOT_PATH . 'admin/');
require_once(ROOT_PATH . 'init.php');

$cryptinstall = ROOT_PATH . "core/tools/cryptographp/cryptographp.fct.php";
include $cryptinstall; 

##############################################################
## D�finitions des scripts/styles � charger pour la page	##
##############################################################
$scripts_to_load = array();
$styles_to_load = array();

##############################################################
## Traitements PHP											##
##############################################################
if(isset($_POST['action']) && $_POST['action'] == 'register') {
	
	// Sauvegarde des informations post�es
	$_SESSION['form-register'] = $_POST;
	
	if(chk_crypt($_POST['captcha'])) { 
		
		if($_POST['register_password'] == $_POST['register_password_confirm']) {
			
			// Instanciation de l'objet
			$user = new User();
			
			// D�finition des propri�t�s
			$user->mail = $_POST['register_mail'];
			$user->password = $_POST['register_password'];
			$user->firstname = $_POST['register_firstname'];
			$user->birthdate = $_POST['register_birthday_year'] . '-' . $_POST['register_birthday_month'] . '-' . $_POST['register_birthday_day'];
			$user->points = 100;
			$user->statut = 0;
			$user->activation_key = $site->string_random_string(10);
			
			if($user->is_unique()) {
				// Insertion
				$user->create();
				
				$content = '<a href="' . $config->site_url . '/activation.php?mail=' . $user->mail . '&key=' . $user->activation_key . '">' . $config->site_url . '/activation.php?mail=' . $user->mail . '&key=' . $user->activation_key . '</a>';
						
				// Envoi du mail
				$tpl = file_get_contents(ROOT_PATH . 'templates/mails.header.htm');
				$tpl .= file_get_contents(ROOT_PATH . 'templates/mails.registration.htm');
				$tpl .= file_get_contents(ROOT_PATH . 'templates/mails.footer.htm');
				
				// On remplace les infos personnelles
				$tpl = str_replace("%FIRSTNAME%", 			$user->firstname, 								$tpl);
				$tpl = str_replace("%MAIL%", 				$user->mail, 									$tpl);
				$tpl = str_replace("%PASSWORD%", 			$user->password, 								$tpl);
				$tpl = str_replace("%KEY%", 				$user->activation_key, 							$tpl);
				$tpl = str_replace("%SITE_NAME%", 			$config->site_name, 							$tpl);
				$tpl = str_replace("%SITE_URL%", 			$config->site_url, 								$tpl);
				$tpl = str_replace("%SITE_SHORT_URL%", 		$config->site_short_url, 						$tpl);
				$tpl = str_replace("%LOGO_URL%", 			$config->site_url . '/images/logo.png', 		$tpl);
				$tpl = str_replace("%SITE_MAIL%", 			$config->site_mail, 							$tpl);
				
				// Envoi du mail
				$to = $user->mail;
				$subject = mb_strtoupper($config->site_name, 'UTF-8') . ': Activate your account';
				$content = $tpl;
				
				$headers = "From: " . $config->site_name . "<no-reply@e-sport-bets.com>" . "\n";
				$headers .= "MIME-Version: 1.0" . "\n";
				$headers .= "Return-Path: <no-reply@e-sport-bets.com>" . "\n";
				$headers .= "Content-type: text/html; charset=utf-8" . "\n";
				$headers .= "X-Sender: <" . $config->site_short_url . ">" ." \n";
				$headers .= "X-Mailer: PHP/".phpversion() . "\n";
				
				mail($to, $subject, $content, $headers);
				
				// On supprime la variable de session tampon
				unset($_SESSION['form-register']);
				
				$notice = new Notice('success', 'Your account was successfully registered. Please click on the link in the e-mail we just sent you to confirm your registration.');
				$notice->sessionize();
			}
			else {
				$notice = new Notice('danger', 'This mail is already registered.');
				$notice->sessionize();
			}
		}
		else {
			$notice = new Notice('danger', 'Password and confirmation don\'t match.');
			$notice->sessionize();
		}
		
	}
	else {
		$notice = new Notice('danger', 'CAPTCHA (spam protection) is incorrect.');
		$notice->sessionize();
	}
	
	header('Location: register.php');
	exit();
}

##############################################################
## Chargement du header										##
##############################################################
require_once(ROOT_PATH . 'includes/inc.head.php');
?>
</head>

<body>

<div id="wrap">

	<?php
	require_once(ROOT_PATH . 'includes/inc.top.php');
	?>
	
	<div id="main" class="container clear-top">
		
		<div class="row">
			
			<div class="span12">
			
				<h1>Register</h1>
					
				<?php
				// Affichage des notices
				$handler_notices->display_all();
				$handler_notices->clear();
				?>
				
				<form method="post" class="register-form">
					<fieldset>
						<label>E-mail <span class="required-star">*</span></label>
						<div class="input-prepend">
							<span class="add-on"><i class="icon-envelope"></i></span>
							<input type="text" name="register_mail" id="register_mail" class="span3" placeholder="E-mail" value="<?php if(isset($_SESSION['form-register']['register_mail'])) echo htmlentities(stripslashes($_SESSION['form-register']['register_mail']), ENT_COMPAT, 'UTF-8'); ?>">
						</div>
						
						<label>Firstname <span class="required-star">*</span></label>
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<input type="text" name="register_firstname" id="register_firstname" class="span2" placeholder="Firstname" value="<?php if(isset($_SESSION['form-register']['register_firstname'])) echo htmlentities(stripslashes($_SESSION['form-register']['register_firstname']), ENT_COMPAT, 'UTF-8'); ?>">
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
						<div class="input-prepend">
							<span class="add-on"><i class="icon-calendar"></i></span>
							<select name="register_birthday_month" id="register_birthday_month" class="span1 grouped-selector-first">
								<?php
								for($m = 1; $m <= 12; $m++) {
									isset($_SESSION['form-register']['register_birthday_month']) && $_SESSION['form-register']['register_birthday_month'] == $m ? $selected = ' selected="selected"' : $selected = '';
									echo '<option value="' . $m . '"' . $selected . '>' . date('M', mktime(0, 0, 0, $m)) . '</option>';
								}
								?>
								
							</select>
							<select name="register_birthday_day" id="register_birthday_day" class="span1 grouped-selector-middle">
								<?php
								for($d = 1; $d <= 31; $d++) {
									isset($_SESSION['form-register']['register_birthday_day']) && $_SESSION['form-register']['register_birthday_day'] == $d ? $selected = ' selected="selected"' : $selected = '';
									echo '<option value="' . $d . '"' . $selected . '>' . str_pad($d, 2, '0', STR_PAD_LEFT) . '</option>';
								}
								?>
							</select>
							<select name="register_birthday_year" id="register_birthday_year" class="span1 grouped-selector-last">
								<?php
								for($y = date('Y'); $y >= date('Y') - 100; $y--) {
									isset($_SESSION['form-register']['register_birthday_year']) && $_SESSION['form-register']['register_birthday_year'] == $y ? $selected = ' selected="selected"' : $selected = '';
									echo '<option value="' . $y . '"' . $selected . '>' . $y . '</option>';
								}
								?>
							</select>
						</div>
						
						<label>Spam protection <span class="required-star">*</span></label>
						<div>
							<?php dsp_crypt(0,1); ?>
							<input type="text" name="captcha" id="captcha" class="required" style="width:38px;" />
						</div>
						
						<br />
						
						<input type="hidden" name="action" value="register" />
						<button type="submit" class="btn btn-primary">Submit</button>
					</fieldset>
				</form>
				
			</div>
		</div>

	</div>
	
</div>

<?php 
require_once(ROOT_PATH . 'includes/inc.footer.php'); 
?>

</body>
</html>

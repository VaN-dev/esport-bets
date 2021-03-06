<?php
##############################################################
## D�finitions des chemins d'acc�s � / et au dossier admin	##
## Initialisation de l'interface							##
##############################################################
DEFINE('ROOT_PATH', '../');
DEFINE('ADMIN_PATH', '');
require_once(ADMIN_PATH . 'init.php');

##############################################################
## D�finitions des scripts/styles � charger pour la page	##
##############################################################
$scripts_to_load = array();
$styles_to_load = array();

##############################################################
## Traitements PHP											##
##############################################################


##############################################################
## Chargement du header										##
##############################################################
require_once(ADMIN_PATH . 'includes/inc.head.php');
?>
</head>

<body>
	
	<div class="navbar navbar-inverse navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<i class="icon-cog"></i>
			</a>
			
			<a class="brand" href="./index.html">
				Base Admin <sup>2.0</sup>				
			</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">		
					<li class="">						
						<a href="./" class="">
							<i class="icon-chevron-left"></i>
							Back to Homepage
						</a>
						
					</li>
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->



<div class="account-container stacked">
	
	<div class="content clearfix">
		
		<form action="index.php" method="post">
		
			<h1>Sign In</h1>		
			
			<div class="login-fields">
				
				<p>Sign in using your registered account:</p>
				
				<div class="field">
					<label for="username">Username:</label>
					<input type="text" id="username" name="user_login" value="" placeholder="Username" class="login username-field" />
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<input type="password" id="password" name="user_password" value="" placeholder="Password" class="login password-field"/>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Keep me signed in</label>
				</span>
				
				<input type="hidden" name="action" value="login" />	
				<button class="button btn btn-warning btn-large">Sign In</button>
				
			</div> <!-- .actions -->
			
		</form>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->



<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="./js/libs/jquery-1.8.3.min.js"></script>
<script src="./js/libs/jquery-ui-1.10.0.custom.min.js"></script>
<script src="./js/libs/bootstrap.min.js"></script>

<script src="./js/Application.js"></script>

<script src="./js/signin.js"></script>

</body>
</html>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
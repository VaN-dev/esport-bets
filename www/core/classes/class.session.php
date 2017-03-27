<?php 
/*
=======================================================================
SCRIPT:		Class : Message Handler
PROJECT:	Framwork
AUTHOR:		Marco Vögeli
=======================================================================
Methods:	- clear()
			- add(type,text)
			- write()			
=======================================================================
*/ 

class Session
{
    private $sql;

	private $session_id;
	private $session_authed;
	private $session_admin_authed;
	private $user_id;
	private $user_login;
	private $user_password;
	
	/*****************************
	** func - session()
	** @Constructor
	** @Access - public
	** @Desc - The cunstructor used for warming up
	** and preparing the sessions.
	** @params - None
	*****************************/
	public function session()
	{
        global $sql;
        $this->sql = $sql;
        
		// Let's initialise the sessions
		session_start();
	}

	/*****************************
	** func - set_var()
	** @Access - public
	** @Desc - Set a session variable
	** @param $var_name - the variable name
	** @paran $var_val  - value for $$var_name
	*****************************/
	public function set_var($var_name, $var_value)
	{
		if(!$var_name || !$var_value)
		{
			return false;
		}
		$_SESSION[$var_name] = $var_value;
	}

	/*****************************
	** func - get_var()
	** @Access - public
	** @Desc - Get a session variable
	** @param $var_name -  the variable name to be retrieved
	*****************************/
	public function get_var($var_name)
	{
		return $_SESSION[$var_name];
	}

	/*****************************
	** func - delete_var()
	** @Access - public
	** @Desc - Delete a session variable
	** @param $var_name -  the variable name to be deleted
	*****************************/
	public function del_var($var_name)
	{
		unset($_SESSION[$var_name]);
	}

	/*****************************
	** func - delete_vars()
	** @Access - public
	** @Desc - Delete session variables contained in an array
	** @param $arr -  Array of the elements
	** to be deleted
	*****************************/
	public function del_vars($arr)
	{
		if(!is_array($arr))
		{
			return false;
		}
		foreach($arr as $element)
		{
			unset($_SESSION[$element]);
		}
		return true;
	}

	/*****************************
	** func - delete_all_vars()
	** @Access - public
	** @Desc - Delete all session variables
	** @params - None
	*****************************/
	public function del_all_vars()
	{
		del_all_vars();
	}

	/*****************************
	** func - end_session()
	** @Access - public
	** @Desc - Destroy the session
	** @params - None
	*****************************/
	public function destroy_session()
	{
		unset($_SESSION['USER']);
	}

	/*****************************
	** func - end_admin_session()
	** @Access - public
	** @Desc - Destroy the session
	** @params - None
	*****************************/
	public function destroy_admin_session()
	{
		unset($_SESSION['ADMIN-USER']);

		setcookie(COOKIE_NAME . "[admin_login]", '', time() - 3600);
		setcookie(COOKIE_NAME . "[admin_password]", '', time() - 3600);
	}

	/*****************************
	** func - attempt_login()
	** @Access - public
	** @Desc - Attempt to log in admin user
	** @params - $user_mail - the mail provided
	** @params - $user_password - the password provided
	*****************************/
	public function attempt_login($mail, $password)
	{
		global $lang;

		$this->user_mail = $this->sql->escape($mail);
		$this->user_password = $this->sql->escape($password);
		
		$accounts = $this->sql->get_array_from_query(sprintf("SELECT id, mail, password, statut FROM " . TABLES__USERS . " WHERE mail = '%s' AND password = '%s'", 
		$this->user_mail, $this->user_password));
		
		// No account found
		if (sizeof($accounts) == 0)
		{
			$notice = new Notice('failure', 'Aucun compte correspondant.');
			$notice->sessionize();
			return false;
		}
		// More than one account found
		elseif (sizeof($accounts) > 1)
		{
			$notice = new Notice('failure', 'Plus d\'un compte correspondant à ces identifiants.');
			$notice->sessionize();
			return false;
		}
		// One account found - login
		else
		{
			if($accounts[0]['statut'] == 1)
			{
				
				$this->session_authed = true;

				$session_user = New User($accounts[0]['id']);
				
				$_SESSION["USER"]["authed"] 		= $this->session_authed;
				$_SESSION["USER"]["id"] 			= $session_user->id;
				$_SESSION["USER"]["mail"] 			= $session_user->mail;

				return true;
			}
			else
			{
				$notice = new Notice('failure', 'Votre compte est désactivé. Authentification impossible.');
				$notice->sessionize();
				return false;
			}
		}
	}

	public function attempt_admin_login($mail, $password, $remember = false)
	{
		global $lang;

		$this->user_mail = $this->sql->escape($mail);
		$this->user_password = $this->sql->escape($password);

		$accounts = $this->sql->get_array_from_query(sprintf("SELECT id, login, password, statut FROM " . TABLES__ADMIN_USERS . " WHERE login = '%s' AND password = '%s'", 
		$this->user_mail, $this->user_password));

		// No account found
		if(sizeof($accounts) == 0)
		{
			$notice = new Notice('failure', 'Aucun compte correspondant.');
			$notice->sessionize();
			return false;
		}
		// More than one account found
		elseif(sizeof($accounts) > 1)
		{
			$notice = new Notice('failure', 'Plus d\'un compte correspondant à ces identifiants.');
			$notice->sessionize();
			return false;
		}
		// One account found - login
		else
		{
			if($accounts[0]['statut'] == 1)
			{
				$this->session_admin_authed = true;

				$session_admin_user = New Admin_User($accounts[0]['id']);

				$_SESSION["ADMIN-USER"]["authed"] 	= $this->session_admin_authed;
				$_SESSION["ADMIN-USER"]["id"] 		= $session_admin_user->id;
				$_SESSION["ADMIN-USER"]["login"] 	= $session_admin_user->login;
				$_SESSION["ADMIN-USER"]["level"] 	= $session_admin_user->level;

				if($remember !== false)
				{
					setcookie(COOKIE_NAME . "[admin_login]", $session_admin_user->login, time() + 60*60*24*30*365);
					setcookie(COOKIE_NAME . "[admin_password]", $session_admin_user->password, time() + 60*60*24*30*365);
				}

				return true;
			}
			else
			{
				$notice = new Notice('failure', 'Votre compte est désactivé. Authentification impossible.');
				$notice->sessionize();
				return false;
			}
			
		}
	}

	/*****************************
	** func - log_auth()
	** @Access - public
	** @Desc - Log the authing process
	*****************************/
	public function log_auth($user)
	{
		sql_query(sprintf("INSERT INTO " . TABLES__AUTH_LOGS . " (log_id, log_datetime, log_user_id, log_user_ip) VALUES('', NOW(), '%d', '%s')", $user->id(), $_SERVER['REMOTE_ADDR']));
	}

	/*****************************
	** func - is_authed()
	** @Access - public
	** @Desc - Check if user is authed or not
	*****************************/
	public function is_authed()
	{
		if(isset($_SESSION["USER"]["authed"]))
		{
			return $_SESSION["USER"]["authed"];
		}
		else
		{
			return false;
		}
	}

	/*****************************
	** func - is_authed()
	** @Access - public
	** @Desc - Check if user is authed or not
	*****************************/
	public function is_admin_authed()
	{
		if(isset($_SESSION["ADMIN-USER"]["authed"]))
		{
			return $_SESSION["ADMIN-USER"]["authed"];
		}
		else
		{
			return false;
		}
	}
}
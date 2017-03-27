<?php 
/*
=======================================================================
SCRIPT:		Class : Handler_Notices
AUTHOR:		David Micheau
=======================================================================
Methods:	- get(key)
			- display_all()
			- clear()			
=======================================================================
*/ 

class Handler_Notices
{
	// --------------------------------------------------------------------
	//--- Clear Method
	function get($key)
	{
		if(isset($_SESSION['notices'][$key]))
		{
			$notice = new Notice($_SESSION['notices'][$key]['level'], $_SESSION['notices'][$key]['content']);
			return $notice;
		}
	}

	// --------------------------------------------------------------------
	//--- Display Method
	function display_all()
	{
		if(isset($_SESSION['notices']))
		{
			foreach($_SESSION['notices'] as $notice)
			{
				$notice = new Notice($notice['level'], $notice['content']);
				$notice->display();
			}
		}
	}

	function clear()
	{
		unset($_SESSION['notices']);
	}
}
?>
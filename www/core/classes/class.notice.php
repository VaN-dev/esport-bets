<?php 
/*
=======================================================================
SCRIPT:		Class : Notice
AUTHOR:		David Micheau
=======================================================================
Methods:	- sessionize()
			- clear()
			- display()	
=======================================================================
*/ 

class Notice
{
	//--- Class Attributes
	var $key;
	var $level;
	var $content;

	// --------------------------------------------------------------------
	//--- Constructor
	public function  __construct($level, $content)
	{	
		if (!isset($_SESSION['notices']))
		{
			$_SESSION['notices'] = array();
			$this->key = 0;
		}
		else
		{
			$this->key = max(array_keys($_SESSION['notices'])) + 1;
		}
		$this->level = $level;
		$this->content = $content;
	} 

	// --------------------------------------------------------------------
	//--- Sessionize Method
	public function sessionize()
	{
		$_SESSION['notices'][] = array('key' => $this->key, 'level' => $this->level, 'content' => $this->content);
	}

	// --------------------------------------------------------------------
	//--- Clear Method
	public function clear()
	{
		unset($_SESSION[$this->key]);
	}

	// --------------------------------------------------------------------
	//--- Display Method
	public function display()
	{
		echo '<div class="alert alert-' . $this->level . '">' . $this->content . '</div>';
	}
}
?>
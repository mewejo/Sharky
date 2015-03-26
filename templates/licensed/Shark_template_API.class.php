<?php

class Shark_%%module_name_short%%_API
{
	function __construct(&$SharkAPI)
	{
		// This is a live instance of the Shark API..
		$this->_Shark_API = &$SharkAPI;
		
		// Path?
		$this->_PATH = dirname(__FILE__) . "/";
		
		// Get the %%module_name_class%% class.
		require_once($this->_PATH . "%%module_name_class%%.class.php");
	}

	function __destruct()
	{
		//
	}
}




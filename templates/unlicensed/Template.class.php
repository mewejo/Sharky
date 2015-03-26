<?php

class %%module_name_class%%
{	
	var $_ModuleName 		= "%%module_name_friendly%%";
	var $_ModuleShortName 	= "%%module_name_short%%";
	var $_ModuleVersion 	= 1.0;
	const Shark_Version_Required = 1.31;
	
	function __construct()
	{
		// Shark installed?
		if(!defined("SHARK_INSTALLED")) throw new Exception("Shark framework is not installed, cannot continue!");
		if(SHARK_INSTALLED !== true) throw new Exception("Shark framework is not installed, cannot continue!");
		
		// Path?
		$this->_PATH = dirname(__FILE__) . "/";
		
		// WHMCS?
		$this->_WHMCSPATH = $this->_PATH . "../../../";
	}
	
	function __destruct()
	{
		//
	}
	
	function _CanRun(&$Result)
	{
		$Result = $this->_CanRun_Real();
		return $Result;
	}
	
	function _CanRun_Real()
	{
		if(version_compare(Shark::Version, %%module_name_class%%::Shark_Version_Required) === -1) return "Shark framework outdated, please download latest version";
		return true;
	}
		
	function _AO_SetupSmarty()
	{
		if(isset($this->Smarty)) return true;

		// WHMCS 5.3.3 Fix for Smarty being moved
		$SmartyPath = $this->_WHMCSPATH . "includes/classes/Smarty/";
		if(!is_dir($SmartyPath)) $SmartyPath = $this->_WHMCSPATH . "includes/smarty/";
		
		// Get Smarty
		require_once($SmartyPath . "Smarty.class.php");
		$this->Smarty = new Smarty();
		$this->Smarty->caching = 0;
		$this->Smarty->compile_check = true;
		$this->Smarty->template_dir = $this->_PATH . "admintpls/";
		$this->Smarty->compile_dir = $this->_PATH . "admintpls/compiled/";		
		$this->Smarty->cache_dir = $this->_PATH . "admintpls/cache/";
		$this->Smarty->plugins_dir = array($this->_PATH . "admintpls/plugins/", $SmartyPath . "plugins/");
		
		// Assign some basic variables..!
		$this->_AO_AssignBasicSmartyVars();
		
		return true;
	}
	
	function _AO_General($_WHMCSModuleVars = array())
	{
		// WHMCS Module Vars
		$this->_WHMCSModuleVars = $_WHMCSModuleVars;
		
		// Smarty.
		$this->_AO_SetupSmarty();
		
		// Header
		$Return = $this->Smarty->fetch('header.tpl');
		
		// Do the action?
		$ActionRes = $this->_AO_ProcessAction();
		if($ActionRes === false)
		{
			// Fatal error.
			$Return .= $this->Smarty->fetch('error-fatal.tpl');
		}
		else
		{
			$Return .= $ActionRes;
		}
		
		// Footer
		$Return .= $this->Smarty->fetch('footer.tpl');
		
		return $Return;
	}
	
	function _AO_Homescreen()
	{
		return $this->Smarty->fetch('homescreen.tpl');
	}
	
	function _AO_ProcessAction()
	{
		if(!isset($_GET["AM_Action"])) return $this->_AO_Homescreen();
		
		// Do the action..
		$Action = $_GET["AM_Action"];
		$Action = trim(strtolower($Action));
		
		$Method = "_AO_ActionMethod_" . $Action;
		
		if(!method_exists($this, $Method))
		{
			$this->Smarty->assign('FatalError', 'Could not find requested action in module subsystem');
			return false;
		}
		
		return $this->$Method();
	}
	
	function _AO_AssignBasicSmartyVars()
	{
		// Module details
		$this->Smarty->assign("ModuleName", $this->_ModuleName);
		$this->Smarty->assign("ModuleLink", $this->_WHMCSModuleVars["modulelink"]);
		$this->Smarty->assign("ModuleWHMCSVars", $this->_WHMCSModuleVars);
		$this->Smarty->assign("ModuleShortName", $this->_ModuleShortName);
		$this->Smarty->assign("ModuleVersion", number_format($this->_ModuleVersion, 2));
	}
	
	function _AO_FatalError($Error = "Unknown Error")
	{
		// Get all of the alerts to display..
		$this->Smarty->assign("FatalError", $Error);
		return $this->Smarty->fetch('error-fatal.tpl');
	}
	
	//////////////////////////////////////////////////////
	// 				MODULE METHODS BELOW 				//
	//////////////////////////////////////////////////////
	
	
}
































































































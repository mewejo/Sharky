<?php

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function %%module_name_short%%_config()
{
	$ModuleConfiguration = array();
	$ModuleConfiguration["name"] 			= "%%module_name_friendly%%";
	$ModuleConfiguration["description"] 	= "%%module_description%%";
	$ModuleConfiguration["version"] 		= "1.0";
	$ModuleConfiguration["author"] 			= "Bluetip Software";
	$ModuleConfiguration["language"] 		= "english";
	$ModuleConfiguration["fields"] 			= array();
	
	$ConfigOpt = "LicenseKey";
	$ModuleConfiguration["fields"][$ConfigOpt] = array();
	$ModuleConfiguration["fields"][$ConfigOpt]["FriendlyName"] 	= "License Key";
	$ModuleConfiguration["fields"][$ConfigOpt]["Type"] 			= "text"; // One of: text, password, yesno, dropdown, radio, textarea
	$ModuleConfiguration["fields"][$ConfigOpt]["Size"] 			= "40";
	$ModuleConfiguration["fields"][$ConfigOpt]["Description"] 	= "The license key which was emailed to you upon order";
	$ModuleConfiguration["fields"][$ConfigOpt]["Default"] 		= "";
	
	return $ModuleConfiguration;
}

function %%module_name_short%%_activate()
{
	$ModuleShortName = "%%module_name_short%%";
	
	// Check Shark is installed!
	if(!defined("SHARK_INSTALLED"))
	{
		// Cannot continue without Shark!
		return array("status" => "error", "description" => "Shark Framework could not be located. You will need to install Shark first. You can download Shark here: www.bluetipsoftware.com/whmcs-modules/whmcs-shark/");
	}
	
	if(SHARK_INSTALLED !== true)
	{
		// Cannot continue without Shark!
		return array("status" => "error", "description" => "Shark Framework could not be located. You will need to install Shark first. You can download Shark here: www.bluetipsoftware.com/whmcs-modules/whmcs-shark/");
	}
	
	// Got the version of Shark we need?
	require_once("%%module_name_class%%.class.php");
	if(version_compare(Shark::Version, %%module_name_class%%::Shark_Version_Required) === -1)
	{
		return array("status" => "error", "description" => "The version of Shark framework installed is too old. Please download at least version " . %%module_name_class%%::Shark_Version_Required . " from our website: www.bluetipsoftware.com/whmcs-modules/whmcs-shark/");
	}
		
	// Install it..
	$Result = Shark::InstallModule($ModuleShortName);
	
	if($Result === true)
	{
		return array("status" => "success", "description" => "Everything has been installed correctly, you can now configure the module.");
	}
	else
	{
		return array("status" => "error", "description" => $Result);
	}
}

function %%module_name_short%%_deactivate()
{
	$ModuleShortName = "%%module_name_short%%";
	
	// Uninstall it..
	$Result = Shark::UninstallModule($ModuleShortName);
	
	if($Result === true)
	{
		return array("status" => "success", "description" => "Everything has now been uninstalled.");
	}
	else
	{
		return array("status" => "info", "description" => "We had an error, you may want to check everything has been removed correctly: " . $Result);
	}
}

function %%module_name_short%%_upgrade($InputVars)
{
	$ModuleShortName = "%%module_name_short%%";
	
	// Version history! Just add in a new version and it'll do the upgrades for us.
	$VersionHistory = array();
	
	$CurrentVersion = floatval($InputVars['version']);	
	if(count($VersionHistory) < 1) return;
	foreach($VersionHistory as $VersionCheck)
	{
		if(version_compare($CurrentVersion, $VersionCheck) === -1)
		{
			$Res = Shark::UpgradeModule($ModuleShortName, $VersionCheck);
		}
	}
}

function %%module_name_short%%_output($Vars)
{
	require_once("%%module_name_class%%.class.php");
	
	try
	{
		$Module = new %%module_name_class%%();
		echo $Module->_AO_General($Vars);
	}
	catch(Exception $E)
	{
		echo "Error: " . $E->getMessage();
	}
}

function %%module_name_short%%_sidebar($Vars)
{	
	$ModuleLink = $Vars["modulelink"];
	$Sidebars = array();
	
	// 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 //
	
		$Sidebar = array();
		$Sidebar["Title"] = "Main menu";
		$Sidebar["MenuItems"] = array();
		
			// 88 // 88 // 88 // 88 // 88 // 88 // 88 // 88 // 88 // 88 //
			$MenuItem = array();
			$MenuItem["Name"] = "Home";
			$MenuItem["Link"] = $ModuleLink;
			$Sidebar["MenuItems"][] = $MenuItem;
			// 88 // 88 // 88 // 88 // 88 // 88 // 88 // 88 // 88 // 88 //
			
		// Add the side bar in!
		$Sidebars[] = $Sidebar;

	// 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 // 77 //
	
	// Build the sidebar..!
	return Shark::Module_BuildSidebarsHTML($Sidebars);
		
}



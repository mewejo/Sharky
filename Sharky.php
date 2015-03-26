<?php

define("SHARKY_PATH", dirname(__FILE__) . "/");

require(SHARKY_PATH . "Sharky.class.php");
require(SHARKY_PATH . "CLI.class.php");

$Sharky = new \Sharky\Sharky();
$CLI = new \Sharky\CLI();

$CLI->Clear();
$CLI->Banner("Welcome to Sharky - Please choose an option below");
$CLI->Line();

$CLI->Line();

$CLI->Text("Options", array("underline" => true, "colour" => "cyan"));
$CLI->Line();
$CLI->Text("[1] Create new module");

$CLI->Line();
$CLI->Line();

$CLI->Text("Option [1]: ");

$Option = $CLI->Input();
if(!$Option) $Option = 1;

$CLI->Line();
$CLI->Line();

switch($Option)
{
	case 1:
		$CLI->Text("Please choose a template", array("underline" => true, "colour" => "cyan"));
		$CLI->Line();
		$Templates = $Sharky->GetTemplates();
		foreach($Templates as $ID => $Name)
		{
			$ID++;
			$CLI->Text("[{$ID}] {$Name}");
			$CLI->Line();
		}
		
		$CLI->Line();
		
		// Tpl?
		$CLI->Text("Template [1]: ");
		
		// Get the real ID
		$ChosenTemplate = $CLI->Input();
		if(!$ChosenTemplate) $ChosenTemplate = 1;
		$ChosenTemplateID = $ChosenTemplate - 1;
		
		// Check it's valid
		if(!isset($Templates[$ChosenTemplateID]))
		{
			$CLI->Line();
			$CLI->Text("Invalid template - quitting.", array("colour" => "red"));
			$CLI->Line();
			$CLI->Line();
			$CLI->Quit();
		}
		
		$TemplateName = $Templates[$ChosenTemplateID];
		
		// Get a path
		$CLI->Line();
		$CLI->Line();
		
		
				
		if(count($Sharky->Preferences["whmcs_installs"]) > 0)
		{
			$CLI->Text("WHMCS Installation", array("underline" => true, "colour" => "cyan"));
			$CLI->Line();
			
			foreach($Sharky->Preferences["whmcs_installs"] as $ID => $Install)
			{
				$ID++;
				$CLI->Text("[{$ID}] {$Install['label']}");
				$CLI->Line();
			}
			
			$ID++;
			$CLI->Text("[{$ID}] Custom Path");
			$CLI->Line();
			
			// Tpl?
			$CLI->Line();
			$CLI->Text("WHMCS Install [1]: ");
			
			// Get it - is it custom?
			$WHMCSInstall = $CLI->Input();
			if(!$WHMCSInstall) $WHMCSInstall = 1;
			if($WHMCSInstall == $ID)
			{
				$CLI->Line();
				$CLI->Line();
				$WHMCSInstall = "custom";
			}
		}
		else
		{
			$WHMCSInstall = "custom";
		}
		
		if($WHMCSInstall == "custom")
		{
			// Get a path			
			$CLI->Text("WHMCS Location", array("underline" => true, "colour" => "cyan"));
			$CLI->Line();
			$CLI->Text("Directory (absolute): ");
			$WHMCSInstallPath = $CLI->Input();
		}
		else
		{
			// Back to the correct ID
			$WHMCSInstall = $WHMCSInstall - 1;
			
			if(!isset($Sharky->Preferences["whmcs_installs"][$WHMCSInstall]))
			{
				$CLI->Text("Invalid install - quitting.", array("colour" => "red"));
				$CLI->Line();
				$CLI->Line();
				$CLI->Quit();
			}
			
			$WHMCSInstallPath = $Sharky->Preferences["whmcs_installs"][$WHMCSInstall]["path"];
		}
		
		// Get the module short name
		$CLI->Line();
		$CLI->Line();
		$CLI->Text("Module short name", array("underline" => true, "colour" => "cyan"));
		$CLI->Line();
		$CLI->Text("Short name (search_party): ");
		$ModuleShortName = $CLI->Input();
		if(!$ModuleShortName)
		{
				$CLI->Line();
				$CLI->Text("Invalid value - quitting.", array("colour" => "red"));
				$CLI->Line();
				$CLI->Line();
				$CLI->Quit();
		}
		
		// Get the module friendly name
		$CLI->Line();
		$CLI->Line();
		$CLI->Text("Module friendly name", array("underline" => true, "colour" => "cyan"));
		$CLI->Line();
		$CLI->Text("Friendly name (Search Party): ");
		$ModuleFriendlyName = $CLI->Input();
		if(!$ModuleFriendlyName)
		{
				$CLI->Line();
				$CLI->Text("Invalid value - quitting.", array("colour" => "red"));
				$CLI->Line();
				$CLI->Line();
				$CLI->Quit();
		}
		
		// Get the module description
		$CLI->Line();
		$CLI->Line();
		$CLI->Text("Module description", array("underline" => true, "colour" => "cyan"));
		$CLI->Line();
		$CLI->Text("Description [{$ModuleFriendlyName}]: ");
		$ModuleDescription = $CLI->Input();
		if(!$ModuleDescription) $ModuleDescription = $ModuleFriendlyName;
		
		// Get the module class name
		$CLI->Line();
		$CLI->Line();
		$CLI->Text("Module class name", array("underline" => true, "colour" => "cyan"));
		$CLI->Line();
		$CLI->Text("Class name (SearchParty): ");
		$ModuleClassName = $CLI->Input();
		if(!$ModuleClassName)
		{
				$CLI->Line();
				$CLI->Text("Invalid value - quitting.", array("colour" => "red"));
				$CLI->Line();
				$CLI->Line();
				$CLI->Quit();
		}
		
		// Confirm all the details
		$CLI->Clear();
		$CLI->Banner("Please confirm all details - they are final.");
		
		$CLI->Line();
		$CLI->Line();
		$CLI->Line();
		
		// Install location
		$CLI->Text("Install Location: ", array("colour" => "white"));
		$CLI->Text(stripcslashes($WHMCSInstallPath), array("colour" => "yellow"));
		$CLI->Line();
		
		// Module directory
		$CLI->Text("Module Directory: ", array("colour" => "white"));
		$CLI->Text(stripcslashes($Sharky->WHMCSModuleLocation($WHMCSInstallPath, $ModuleShortName)), array("colour" => "yellow"));
		$CLI->Line();
		
		// Module name
		$CLI->Text("Module name: ", array("colour" => "white"));
		$CLI->Text("{$ModuleFriendlyName} ({$ModuleShortName})", array("colour" => "yellow"));
		$CLI->Line();
		
		// Module Description
		$CLI->Text("Module Description: ", array("colour" => "white"));
		$CLI->Text($ModuleDescription, array("colour" => "yellow"));
		$CLI->Line();
		
		// Module Class
		$CLI->Text("Module Class: ", array("colour" => "white"));
		$CLI->Text($ModuleClassName, array("colour" => "yellow"));
		$CLI->Line();
		
		// Confirmed?
		$CLI->Line();
		$CLI->Line();
		$CLI->Text("Happy to proceed?", array("underline" => true, "colour" => "cyan"));
		$CLI->Line();
		$CLI->Text("Do you want to continue [Y/n]? ");

		if(strtolower($CLI->Input()) !== "y")
		{
				$CLI->Line();
				$CLI->Text("Quitting.", array("colour" => "red"));
				$CLI->Line();
				$CLI->Line();
				$CLI->Quit();
		}
		
		$CLI->Clear();
		$CLI->Banner("Installing - please wait...");
		sleep(1);
		
		// Do it!
		$Result = $Sharky->CreateModule($WHMCSInstallPath, $TemplateName, $ModuleShortName, $ModuleFriendlyName, $ModuleDescription, $ModuleClassName);
		
		if($Result === true)
		{
			$CLI->Clear();
			$CLI->Banner("Successful");
			$CLI->Line();		
			$CLI->Text("Success, the module has been created.", array("underline" => true, "colour" => "green"));
			$CLI->Line();
			$CLI->Line();			
		}
		else
		{
			$CLI->Line();
			$CLI->Text("Error: " . $Result, array("colour" => "red"));
			$CLI->Line();
			$CLI->Line();
			$CLI->Quit();
		}
		
		break;
		
	default:
		$CLI->Text("Invalid option - quitting.", array("colour" => "red"));
		$CLI->Line();
		$CLI->Line();
		$CLI->Quit();
}

$CLI->Line();
$CLI->Line();





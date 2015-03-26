<?php

namespace Sharky;

class Sharky
{
	var $Path;
	var $Preferences = array();
	
	function __construct()
	{
		$this->Path = dirname(__FILE__) . "/";
		$this->Preferences();
	}

	function __destruct()
	{
		//
	}
	
	function Preferences()
	{
		$PrefFile = $this->Path . "preferences";
		if(!file_exists($PrefFile)) return;
		$this->Preferences = json_decode(file_get_contents($PrefFile), true);
		if(!$this->Preferences) $this->Preferences = array(); // In case of bad JSON.
	}
	
	function GetTemplates()
	{
		return array_values(array_diff(scandir($this->Path . "templates/"), array("..", ".", ".DS_Store")));
	}
	
	function WHMCSModuleLocation($WHMCSPath, $ShortName)
	{
		return $WHMCSPath . "/modules/addons/" . $ShortName;
	}
	
	function CreateModule($WHMCSInstallPath, $Template, $ModuleShortName, $ModuleFriendlyName, $ModuleDescription, $ModuleClassName)
	{
		// Create a temp directory
		$DestinationPath = $this->WHMCSModuleLocation($WHMCSInstallPath, $ModuleShortName);
		
		// Does it exist?
		if(is_dir($DestinationPath)) return "Module directory ({$ModuleShortName}) already exists. It must not exist to continue.";
		
		// Move files over.
		$this->CopyDirectory($this->Path . "templates/" . $Template, $DestinationPath);
		
		// Edit the contents of the files.
		$MergeFields = array();
		$MergeFields["module_name_short"] = $ModuleShortName;
		$MergeFields["module_name_friendly"] = $ModuleFriendlyName;
		$MergeFields["module_description"] = $ModuleDescription;
		$MergeFields["module_name_class"] = $ModuleClassName;
		
		$MergeFields_Split = array();
		$MergeFields_Split["from"] = array();
		$MergeFields_Split["to"] = array();
		
		foreach($MergeFields as $Field => $Value)
		{
			$MergeFields_Split["from"][] = "%%" . $Field . "%%";
			$MergeFields_Split["to"][] = $Value;
		}
		
		$this->ModifyFiles($DestinationPath, $MergeFields_Split);
		
		// Rename the files.
		rename("{$DestinationPath}/template.php", "{$DestinationPath}/{$ModuleShortName}.php");
		rename("{$DestinationPath}/Template.class.php", "{$DestinationPath}/{$ModuleClassName}.class.php");
		rename("{$DestinationPath}/Shark_template_API.class.php", "{$DestinationPath}/Shark_{$ModuleShortName}_API.class.php");
		
		return true;
	}
	
	function ModifyFiles($Directory, $MergeFields_Split)
	{
		foreach(array_diff(scandir($Directory), array("..", ".", ".DS_Store")) as $Filename)
		{
			$FilePath = $Directory . "/" . $Filename;
			
			if(is_dir($FilePath))
			{
				$this->ModifyFiles($FilePath, $MergeFields_Split);
			}
			else
			{
				// Mod it
				$Contents = file_get_contents($FilePath);
				$Contents = str_replace($MergeFields_Split["from"], $MergeFields_Split["to"], $Contents);
				file_put_contents($FilePath, $Contents);
			}
		}
	}
	
	
	// Source: http://stackoverflow.com/a/2050909/1002843
	function CopyDirectory($src, $dst)
	{ 
		$dir = opendir($src); 
		mkdir($dst); 
		
		while(false !== ($file = readdir($dir)))
		{ 
			if(($file != '.') && ($file != '..'))
			{
				if(is_dir($src . '/' . $file))
				{ 
					$this->CopyDirectory($src . '/' . $file,$dst . '/' . $file); 
				} 
				else
				{ 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		}
		 
		closedir($dir); 
	} 
}

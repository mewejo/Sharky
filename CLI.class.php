<?php

namespace Sharky;

class CLI
{
	var $Cols;
	var $Rows;
	var $Path;
	var $Colours;
	
	function __construct()
	{
		$this->Size();
		$this->Path = dirname(__FILE__) . "/";
		require($this->Path . "CLIColours.class.php");
		$this->Colours = new Colors();
	}

	function __destruct()
	{
		//
	}
	
	function Size()
	{
		$this->Cols = exec('tput cols');
		$this->Rows = exec('tput lines');
	}
	
	function Clear($Print = true)
	{
		$Res = chr(27) . "[H" . chr(27) . "[2J";
		if($Print) echo $Res;
		return $Res;
	}
	
	function Line($Print = true)
	{
		if($Print) echo "\n";
		return "\n";
	}
	
	function Text($Text, $Options = array(), $Print = true)
	{
		if($Options["underline"]) $Text .= $this->Line(false) . str_repeat("-", strlen($Text));
		if($Options["colour"]) $Text = $this->Colours->getColoredString($Text, $Options["colour"]);
		if($Print) echo $Text;
		return $Text;
	}
	
	function Quit()
	{
		exit;
	}
	
	function Banner($Text)
	{
		$Padding = $this->Cols - strlen($Text);
		$Padding_Left = round($Padding / 2, 0, PHP_ROUND_HALF_DOWN);
		$Padding_Right = $Padding - $Padding_Left;
		
		$Banner = "";
		$Banner .= str_repeat("*", $this->Cols);
		$Banner .= $this->Line(false);
		
		$Banner .= str_repeat("*", $this->Cols);
		$Banner .= $this->Line(false);
		$Banner .= $this->Line(false);
		
		$Banner .= str_pad(" ", $Padding_Left) . $Text . str_pad(" ", $Padding_Right);
		$Banner .= $this->Line(false);	
		$Banner .= $this->Line(false);	
		
		$Banner .= str_repeat("*", $this->Cols);
		$Banner .= $this->Line(false);
			
		$Banner .= str_repeat("*", $this->Cols);
		
		echo $Banner;
	}
	
	function Input()
	{
		$Handle = fopen("php://stdin", "r");
		$Result = fgets($Handle);
		fclose($Handle);
		return trim($Result);
	}
}

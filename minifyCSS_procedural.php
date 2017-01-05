#! /usr/bin/php
<?php

	$css = "";
	$minifiedCss = "";

	$myfile = fopen($argv[1], "r") or exit("Unable to open ".$argv[1].PHP_EOL);
	
	$myfileDirectory = dirname($argv[1]).'/';
	$myfileName = basename($argv[1],'.css');
	
	if(array_key_exists(2, $argv))
	{
		$myfileDirectory = substr($argv[2], strlen($argv[2])-1,1) == '/'?$argv[2]:$argv[2].'/';
	}
	
	while(!feof($myfile)) 
	{
	  $css .= fgetc($myfile);
	}
	fclose($myfile);

	
	$css = trim(preg_replace('/\s+/','',$css), ' ');
	for ($i = 0; $i < strlen($css); $i++) 
	{
		if(substr($css,$i,1) == '/' && substr($css,$i+1,1) == '*')
		{
			$i = $i + 2;
			for ($j = 0; $j < strlen($css); $j++) 
			{	
				if(substr($css,$i,1) == '*' && substr($css,$i+1,1) == '/')
				{
					$i++;
					break;
				}
				$i++;
			}
		}
		else
		{
			$minifiedCss .= substr($css,$i,1); 
		}
	}
	$path = $myfileDirectory.$myfileName.'.min.css';

	if(!is_writable($myfileDirectory))
	{
		$currentFilePermission = substr(sprintf("%o",fileperms($myfileDirectory)),-4);
		chmod($myfileDirectory, 0777);

		$myfile = fopen($path, "w+") or exit("You do not have write permission for ".$path.PHP_EOL);
		fwrite($myfile, $minifiedCss."\n");
		fclose($myfile);
		chmod($myfileDirectory, $currentFilePermission);
	}
	else
	{		
		$myfile = fopen($path, "w+") or exit("You do not have write permission for ".$path.PHP_EOL);
		fwrite($myfile, $minifiedCss."\n");
		fclose($myfile);
	}

?>

<?php
	$css = "";
	$minifiedCss = "";
	$myfile = fopen($argv[1], "r") or die("Unable to open file!");
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

	$path = "/var/www/html/minifyCSS/minifiedCss.css";
	
	$myfile = fopen($path, "w+") or die("Unable to open file!");
	fwrite($myfile, $minifiedCss."\n");
	fclose($myfile);
?>
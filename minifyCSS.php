#! /usr/bin/php
<?php
	class MinifyCss
	{
		private $cssFilePath;
		private $minifiedCssFilePath;
		private $minifiedCssFileName;
		
		function __construct($argv,$argc)
		{
			if(array_key_exists(1, $argv))
			{
				$this->cssFilePath 		 	= 	$argv[1];
			}
			else
			{
				exit("Please provide the path of surce file.".PHP_EOL);
			}
			if(array_key_exists(2, $argv))
			{
				$this->minifiedCssFilePath 	= 	substr($argv[2], strlen($argv[2])-1,1) == '/'?$argv[2]:$argv[2].'/'; 
				//$argv[2] must be a valid directory or blank not minified css file name. eg-> /home/akash/Desktop
			}	
			$this->get_minified_css();		
		}
		
		function get_minified_css()
		{
			$minifiedCss 	= 	'';
			$css 			= 	$this->get_css();
			$css 			= 	trim(preg_replace('/\s+/','',$css), ' ');
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
			
			if(!$this->minifiedCssFilePath)
			{
				$this->minifiedCssFilePath 	= 	dirname($this->cssFilePath).'/';
			}
			$this->minifiedCssFileName 		= 	basename($this->cssFilePath,'.css');
			
			$path = $this->minifiedCssFilePath.$this->minifiedCssFileName.'.min.css';

			if(!is_writable($this->minifiedCssFilePath))
			{
				$currentFilePermission = substr(sprintf("%o",fileperms($this->minifiedCssFilePath)),-4);
				chmod($this->minifiedCssFilePath, 0777);

				$myfile = fopen($path, "w+") or exit("You do not have write permission for ".$path.PHP_EOL);
				fwrite($myfile, $minifiedCss."\n");
				fclose($myfile);
				chmod($this->minifiedCssFilePath, $currentFilePermission);
			}
			else
			{		
				$myfile = fopen($path, "w+") or exit("You do not have write permission for ".$path.PHP_EOL);
				fwrite($myfile, $minifiedCss."\n");
				fclose($myfile);
			}
		}

		public function get_css()
		{
			$css = '';
			$myfile 						= 	fopen($this->cssFilePath, "r") or exit("Unable to open ".$this->cssFilePath.PHP_EOL);
			while(!feof($myfile)) 
			{
			  $css .= fgetc($myfile);
			}
			fclose($myfile);
			return $css;
		}

	}
	
	$minifyCss = new MinifyCss($argv,$argc);
?>

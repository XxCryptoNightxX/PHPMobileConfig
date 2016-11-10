<?php
	abstract class HELPER
	{
		/*
		 * Generates a random string
		 *
		 * @param		int			$length				Length of random string
		 * @param		bool		$numbers			String contains numbers
		 * @param		bool		$letters_lc			String contains lower case letters
		 * @param		bool		$letters_uc			String contains upper case letters
		 * @param		bool		$symbols			String contains special characters
		 *
		 * return string
		 */  
		static public function generateRandomString($length = 32, $symbols = false, $numbers = true, $letters_lc = true, $letters_uc = true)
		{
			$characters = '';
			
			if($numbers)
			{
				$characters .= '0123456789';
			}
			if($letters_lc)
			{
				$characters .= 'abcdefghijklmnopqrstuvwxyz';
			}
			if($letters_uc)
			{
				$characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			if($symbols)
			{
				$characters .= ',.-;:_!§%()=?@{}[]$#+*´`"';
			}
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }
		    return $randomString;
		}
		
		/*
		 * Generates a unique UUID
		 *
		 * @param		string		$version				Desired ersion of UUID
		 * @param		string		$uuid					Current UUID
		 *
		 * return string
		 */  
		public static function get_uuid($version = '1', $uuid = null)
		{
			// Import global array that contains used UUIDs to prevent duplicates
			global $uuid_array;
			
			$version = strval($version);
			$uuid = file_get_contents('https://www.uuidgenerator.net/api/version' . $version);
			
			// Check if current UUID has already been used
			if(!in_array(md5($uuid), $uuid_array))
			{
				$uuid_array[] = md5($uuid);
				return substr($uuid,0,36);
			}
			else
			{
				get_uuid(file_get_contents('https://www.uuidgenerator.net/api/version' . $version, $uuid));
			}
		}
		
		/*
		 * Check which device is requesting
		 *
		 */
		
		public static function client()
		{
			//Detect special conditions devices
			$iPod	= stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
			$iPhone	= stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
			$iPad	= stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
			$Android= stripos($_SERVER['HTTP_USER_AGENT'],"Android");
			$webOS	= stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
			$Mac	= stripos($_SERVER['HTTP_USER_AGENT'],"Macintosh");
			$Windows	= stripos($_SERVER['HTTP_USER_AGENT'],"Windows");
			
			//do something with this information
			if($iPod || $iPhone || $iPad || $Mac)
			{
			    //browser reported as an iPhone/iPod touch -- do something here
			    return 'apple';
			}
			elseif($Android || $Windows)
			{
			    //browser reported as an iPad -- do something here
			    return 'other';
			}
		}

		
		/*
		 * Improved version of var_dump()
		 *
		 * 
		 * @param		variant			$var				Variable to dump
		 *
		 *
		 */	
		public static function varDump($var)
		{
			echo '<pre>';
			var_dump($var);
			echo '</pre>';
		}


		public static function printStatus($status)
		{
			echo $status . "<br /> ";
		    echo str_pad('',4096)."\n";
		    ob_flush();
		    flush();
		    sleep(1);
		}
	
		public static function addCSS()
		{
			echo <<< END
			<style type="text/css">
				body {
					font-family: 'Helvetica',serif;
				}
			</style>
END;
		}
		
		public static function checkDependencies($files, $xml)
		{
			if(is_null($xml) || $xml == "")
			{
				return array(false, 'xml');
			}
			
			// If infile not exists yet it will be created
			// If writing content into it fails the profile is invalid (empty)
			if(file_put_contents($files->infile, $xml) === false)
			{
				// Content of profile xml could not be written into file
				return array(false, $files->infile);
			}
		
			// Check if outfile exists
			if(!file_exists($files->outfile))
			{
				// If no it will be created
				if(file_put_contents($files->outfile, '') === false)
				{
					// File not exists. It is necessary for download.php to work. It needs to exist.
					return array(true, $files->outfile);
				}
				// else everything's fine
			}
		
			foreach(array($files->signcert, $files->privkey, $files->extracerts) as $file)
			{
				// Check if one of the files necessary to sign is missing
				if(!file_exists($file))
				{
					// If yes profile can't be signed, so there's no need to check the rest of the files
					$noCert = true;
					printStatus('The following file necessary to sign the profile is missing: ' . $file);
					printStatus('Profile signing will be skipped');
					return array(true, $file);
				}
				// else continue checking remaining files
			}
			// Everything's fine, all dependecies exist
			return true;
		}










}



?>
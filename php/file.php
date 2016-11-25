<?php
	
	/**
	* Sets the memory to the required limit for creating a thumbnail
	* 
	* @param {String} $FileLocation Location of file to create
	* @param {String} $FileData Information to place in file
	* @param {String} [$FileMode = 'w'] Mode for file writing
	* @param {Octal} [$FileAccessLevel = 0644] CHMOD level for $FileLocation
	* @param {Octal} [$FolderAccessLevel = 0755] CHMOD level for newly created folders containing $FileLocation
	* @returns {Boolean} True if memory was reserved, false if the memory could not be reserved
	*/
	function createFile($FileLocation, $FileData, $FileMode = 'w', $FileAccessLevel = 0644, $FolderAccessLevel = 0755)
	{
		$directory = explode('/', $FileLocation);
		$fileName = array_pop($directory);
		$fileDirectory = implode('/', $directory);
		if(!is_dir($fileDirectory) && !mkdir($fileDirectory, $FolderAccessLevel, true))
		{
			return false;
		}
	
		if(($handle = fopen($FileLocation, $FileMode)) === false)
		{
			return false;
		}
		if(fwrite($handle, $FileData) === false)
		{
			fclose($handle);
			return false;
		}
		fclose($handle);
		
		chmod($FileLocation, $FileAccessLevel);
		
		return true;
	}
?>
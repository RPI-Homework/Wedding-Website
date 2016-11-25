<?php

	/**
	* Sets the memory to the required limit for creating a thumbnail
	* 
	* @param {Integer} $ImageLocation Location of image to create thumbnail for
	* @returns {Boolean} True if memory was reserved, false if the memory could not be reserved
	*/
	function setMemoryForImage($ImageLocation)
	{
		$imageInfo = getimagesize($ImageLocation);
		$imageInfo['mime'] = strtolower($imageInfo['mime']);
		if($imageInfo['mime'] == "image/jpeg" || $imageInfo['mime'] == "image/gif")
		{
			$memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
		}
		else if($imageInfo['mime'] == "image/png")
		{
			$memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo[2] * $imageInfo['bits'] / 8 + Pow(2, 16)) * 1.65);
		}
		else if($imageInfo['mime'] == "image/vnd.wap.wbmp")
		{
			$memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo[2] + Pow(2, 16)) * 1.65);
		}
		else
		{
			return false;
		}
		
		$memoryNeeded += memory_get_usage();
		$memoryLimit = (int) ini_get('memory_limit')*1048576;

		if ($memoryLimit < $memoryNeeded)//if do not already have that memory
		{
			//reserves the memory needed
			
			ini_set('memory_limit', ceil(($memoryNeeded + $memoryLimit)/1048576).'M');
			
			//check if the memory was reserved
			return $memoryNeeded <= ((int)ini_get('memory_limit') * 1048576);
		}
		else
		{
			return true;
		}
	}
	
	/**
	* Finds valid Image Dimensions
	* 
	* @param {Integer} $ImageWidth Width of the image
	* @param {Integer} $ImageHeight Height of the image
	* @param {Integer} $ThumbWidth The maximum thumbnail width
	* @param {Integer} $ThumbHeight The maximum thumbnail height
	* @returns {Array|Boolean} Array of the dimensions on success, false otherwise
	*/
	function getThumbDimensions($ImageWidth, $ImageHeight, $ThumbWidth, $ThumbHeight)
	{
		//make sure the image dimensions are valid
		if($ImageWidth > 0 && $ImageHeight > 0)
		{
			//if the width is greater than the height
			if($ThumbWidth / $ImageWidth < $ThumbHeight / $ImageHeight)
			{
				$x = $ThumbWidth / $ImageWidth;
				$ThumbHeight = intval(round($ImageHeight * $x));
			}
			//if the height is greater than the width
			else if($ThumbWidth / $ImageWidth > $ThumbHeight / $ImageHeight)
			{
				$x = $ThumbHeight / $ImageHeight;
				$ThumbWidth = intval(round($ImageWidth * $x));
			}
			
			//return true only if the return dimensions are valid
			//then make sure the image has real dimension
			if($ThumbWidth < 1 && $ThumbHeight < 1)
			{
				return false;
			}
			else if($ThumbWidth < 1)
			{
				$ThumbWidth = 1;
			}
			else if($ThumbHeight < 1)
			{
				$ThumbHeight = 1;
			}
			
			return array (
				"width"  => $ThumbWidth,
				"height" => $ThumbHeight
			);
		}
		return false;
	}
	
	/**
	* Opens an image and places it into an image resource
	* 
	* @param {String} $ImageLocation The location of the image which to load into the resource
	* @returns {Image|Boolean} Returns the image on success, otherwise return false
	*/
	function loadImage($ImageLocation)
	{
		if(setMemoryForImage($ImageLocation))
		{
			//get the info about the image
			$imageInfo = getimagesize($ImageLocation);
			$imageInfo['mime'] = strtolower($imageInfo['mime']);

			//load the image resource
			$Image = null;
			if($imageInfo['mime'] == 'image/jpeg')
			{
				$Image = imagecreatefromjpeg($ImageLocation);
			}
			else if($imageInfo['mime'] == 'image/png')
			{
				$Image = imagecreatefrompng($ImageLocation);
			}
			else if($imageInfo['mime'] == 'image/gif')
			{
				$Image = imagecreatefromgif($ImageLocation);
			}
			else if($imageInfo['mime'] == 'image/vnd.wap.wbmp')
			{
				$Image = imagecreatefromwbmp($ImageLocation);
			}
			else
			{
				return false;
			}
			return $Image;
		}
		return false;
	}
	
	/**
	* Saves an image resource to a location
	* 
	* @param {String} $Image The image resource to be saved
	* @param {String} $ImageLocation The location where to save the image
	* @param {String} [$Type = 'image/jpeg'] The type of image to save the image resource as
	* @returns {Boolean} True on success, otherwise return false
	*/
	function saveImage($Image, $ImageLocation, $Type = 'image/jpeg')
	{
		if(is_file($ImageLocation))
		{
			unlink($ImageLocation);
		}
		
		$Type = strtolower($Type);
		if($Type == 'image/jpeg')
		{
			return imagejpeg($Image, $ImageLocation);
		}
		else if($Type == 'image/png')
		{
			return imagepng($Image, $ImageLocation);
		}
		else if($Type == 'image/gif')
		{
			return imagegif($Image, $ImageLocation);
		}
		else if($Type == 'image/vnd.wap.wbmp')
		{
			return imagewbmp($Image, $ImageLocation);
		}
		return false;
	}
	
	/**
	* Creates a thumbnail from an image resource
	* 
	* @param {Image} $Image The image resource to create the thumbnail from
	* @param {Integer} $MaxWidth The maximum width of the thumbnail
	* @param {Integer} $MaxHeight The maximum height of the thumbnail
	* @returns {Image|Boolean} Returns the thumb on success, otherwise return false
	*/
	function createThumbnail($Image, $MaxWidth, $MaxHeight)
	{
		$ImageWidth = imagesx($Image);
		$ImageHeight = imagesy($Image);
		$thumbnail = getThumbDimensions($ImageWidth, $ImageHeight, $MaxWidth, $MaxHeight);
		
		$Thumbnail = imagecreatetruecolor($thumbnail['width'], $thumbnail['height']);//creates thumbnail image
		imagecopyresized($Thumbnail, $Image, 0, 0, 0, 0, $thumbnail['width'], $thumbnail['height'], $ImageWidth, $ImageHeight);//resizes thumbnail image
		return $Thumbnail;
	}

	/**
	* Creates a thumbnail from an image resource
	* 
	* @param {String} $ImageLocation The location of the image to make the thumbnail from
	* @param {String|String[]} $ImageDestination The location of the thumbnail or an array of the location of thumbnails to place the image
	* @param {Integer|Interger[]} [$MaxThumbnailWidth = 100] The maximum width to make the thumbnail or an array of the maximum width to make each thumbnail
	* @param {Integer|Interger[]} [$MaxThumbnailHeight = 100] The maximum height to make the thumbnail or an array of the maximum height to make each thumbnail
	* @param {String[]} [$AcceptedImages = array('image/vnd.wap.wbmp', 'image/png', 'image/gif', 'image/jpeg')] All the image types which are accepted to make thumbnails from
	* @returns {Boolean} True on success, otherwise return false
	*/
	function makeThumbnails($ImageLocation, $ImageDestination, $MaxThumbnailWidth = 100, $MaxThumbnailHeight = 100, $AcceptedImages = array('image/vnd.wap.wbmp', 'image/png', 'image/gif', 'image/jpeg'))
	{
		$orginalMemoryLimit = ini_get('memory_limit');
		if(is_file($ImageLocation))
		{
			//get image information
			$imageInfo = getimagesize($ImageLocation);
			$imageInfo['mime'] = strtolower($imageInfo['mime']);

			//check if legal image type
			foreach ($AcceptedImages as $allowedImage)
			{
				if ($imageInfo['mime'] == $allowedImage)
				{
					//make $ImageDestination into array format
					if(!is_array($ImageDestination))
					{
						$ImageDestination = array($ImageDestination);
					}
					
					//loads the image
					if(($source = loadImage($ImageLocation)) === false)
					{
						return false;
					}
					
					//create each thumbnail
					$i = 0;
					foreach($ImageDestination as $imageDestination)
					{
						$thumb = createThumbnail($source, is_array($MaxThumbnailWidth) ? $MaxThumbnailWidth[$i] : $MaxThumbnailWidth, is_array($MaxThumbnailHeight) ? $MaxThumbnailHeight[$i] : $MaxThumbnailHeight);
						saveImage($thumb, $imageDestination, $imageInfo['mime']);
						$i++;

						ImageDestroy($thumb);
					}
					
					//reset everything
					ImageDestroy($source);
					ini_set("memory_limit", $orginalMemoryLimit);
					return true;
				}
			}
		}
		return false;
	}

?>
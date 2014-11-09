<?php
class ImageLibrary {
	/* IMAGETYPE_* defined in http://www.php.net/manual/en/function.exif-imagetype.php */
	private $IMAGE_TYPES = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
	
	public function __construct() {
		
	}

	private function createImageFromFile($filename, $fileType) {
		switch($fileType) {
			case IMAGETYPE_GIF:
				return imagecreatefromgif($filename);
			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($filename);
			case IMAGETYPE_PNG:
				return imagecreatefrompng($filename);
			default:
				return false;
		}
	}
	private function saveImageToFile($targetImage, $newFilename, $fileType) {
		switch($fileType) {
			case IMAGETYPE_GIF:
				imagegif($targetImage, $newFilename);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($targetImage, $newFilename);
				break;
			case IMAGETYPE_PNG:
				imagepng($targetImage, $newFilename);
				break;
			default:
				return false;
		}
		
		return true;
	}
	
	/**
	 * Scales the image to the new size given.
	 * Depending on the action given either resample or resize the image to the desired size.
	 * 
	 * @param string $targetFile Where to place the new image.
	 * @param string $sourceFile What image to resize.
	 * @param integer $newWidth The new width of the image.
	 * @param integer $newHeight The new height of the image.
	 * @param string $action The action to take, either resample or resize.
	 */
	private function scaleImage($targetFile, $sourceFile, $newWidth, $newHeight, $action) {
		$imagesize = getimagesize($sourceFile);
		if(!in_array($imagesize[2], $this->IMAGE_TYPES)) {
			return false;
		}
		
		$sourceImage = $this->createImageFromFile($sourceFile, $imagesize[2]);
		if (!$sourceImage) {
			return false;
		}
		$sourceWidth = imagesx($sourceImage);
		$sourceHeight = imagesy($sourceImage);
		
		$targetImage = imagecreatetruecolor($newWidth, $newHeight);
		switch ($action) {
			case "resample":
				imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
				break;
			case "resize":
				imagecopyresized($targetImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
				break;
			default:
				return false;
		}
		
		$this->saveImageToFile($targetImage, $targetFile, $imagesize[2]);
		return true;
	}
	
	/**
	 * Resamples the given image ($sourceFile) image to a new size.
	 * The new image is saved in the given place ($targetFile)
	 * 
	 * @param string $targetFile Where to place the new image.
	 * @param string $sourceFile What image to resize.
	 * @param integer $newWidth The new width of the image.
	 * @param integer $newHeight The new height of the image.
	 */
	public function resample($targetFile, $sourceFile, $newWidth, $newHeight) {
		return $this->scaleImage($targetFile, $sourceFile, $newWidth, $newHeight, "resample");
	}
	
	/**
	 * Resizes the given image ($sourceFile) to a new size.
	 * The new image is saved in the given place ($targetFile)
	 * 
	 * @param string $targetFile Where to place the new image.
	 * @param string $sourceFile What image to resize.
	 * @param integer $newWidth The new width of the image.
	 * @param integer $newHeight The new height of the image.
	 */
	public function resize($targetFile, $sourceFile, $newWidth, $newHeight) {
		return $this->scaleImage($targetFile, $sourceFile, $newWidth, $newHeight, "resize");
	}
}
?>


































<?php
/**
 * ImagingHelper
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Helpers;



/** 
 * Helper class to deal with pictures
 */
class ImagingHelper
{
	/**
	 * If exif data indicates a rotated image, we apply the transformation to the image and set back orientation to normal
	 *
	 * @param string $path Path to image
	 * @return bool
	 */
	static function imageAdjustOrientation($path)
	{
		// read image
		$image = new \imagick();
		if ( !$image->readImage($path) )
			return FALSE;
		
		
		// read exif orientation
		$orientation = $image->getImageOrientation(); 
		$rotated = false;

		switch($orientation)
		{ 
			case \imagick::ORIENTATION_BOTTOMRIGHT: 
				$image->rotateimage("#000", 180); // rotate 180 degrees 
				$rotated = true;
			break; 

			case \imagick::ORIENTATION_RIGHTTOP: 
				$image->rotateimage("#000", 90); // rotate 90 degrees CW 
				$rotated = true;
			break; 

			case \imagick::ORIENTATION_LEFTBOTTOM: 
				$image->rotateimage("#000", -90); // rotate 90 degrees CCW 
				$rotated = true;
			break; 
		} 

		// Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image! 
		$image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); 
		
		
		if ( $rotated )
		{
			$image->setImageCompressionQuality(90);
			$image->writeImage($path); 
		}

		
		return TRUE;
	}
	
	
	
	
	/**
     * Resize an image (width x height) to a squared image (with a width/height of $imgw) ; aspect ratio is preserved
     *
     * @param resource $source Image resource to process
     * @param int $width Width of source image
     * @param int $height Height of source image
     * @param resource $img Image resource that will contain the resized image
     * @param int $imgw Width/height of the resized image (square)
     * @param int $imgbkcolor Color of background
     * @retun bool Return true or false depending on the success of the resampling
     */
	static function image_resize_boxed($source, $width, $height, &$img, $imgw, $imgbkcolor)
	{
		// which orientation : landscape or portrait ?
		if ( $width > $height ) // landscape
		{            
			$hc = (int) round($height * $imgw / $width);	
			$wc = $imgw;
		}
		else					// portrait
		{
			$wc = (int) round($width * $imgw / $height);
			$hc = $imgw;
		}
	
					
		// fill the background with a color
		imagefilledrectangle($img, 0, 0, $imgw, $imgw, $imgbkcolor);
		
		// $dst_image , $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h
		$r = imagecopyresampled($img, $source, (int) floor(($imgw - $wc) / 2), (int) floor(($imgw - $hc) / 2), 0, 0, $wc, $hc, $width, $height);
		return $r;
	}
	
	

	/**
     * Resize an image (width x height) to a small image (with a max with and/or height)
     * 
     * If we don't want to set a max width/height, just pass NULL as parameter ;
     * Note that aspect ratio is preserved
     *
     * @param resource $source Image resource to process
     * @param int $width Width of source image
     * @param int $height Height of source image
     * @param int $wmax Max width of resized image
     * @param int $hmax Max height of resized image
     * @return mixed Return an image resource with a new image resized, or FALSE if an error occured
     */
    static function image_resize($source, $width, $height, $wmax, $hmax)
	{
		// if max width AND height are defined
		if ( $hmax && $wmax )
		{
			// first resize with max height
			$hc = $hmax;
			$wc = (int) round($width * $hmax / $height);
			
			// if width W is greater than max width, go on resize with the max width
			if ( $wc > $wmax )
			{
				$hc = (int) round($hc * $wmax / $wc);
				$wc = $wmax;
			}
		}
		
		// if only max height is provided (max width is set to 0)
		else if ( $hmax )
		{
			$hc = $hmax;
			$wc = (int) round($width * $hc / $height);
		}
	
	
		// if only max width is provided (max height is set to 0)
		else 
		{
			$wc = $wmax;
			$hc = (int) round($height * $wc / $width);
		}
		
		
		// creating the image
		$img = imagecreatetruecolor($wc, $hc);
		if ( imagecopyresampled($img, $source, 0, 0, 0, 0, $wc, $hc, $width, $height) )
			return $img;
		else
			return FALSE;
	}
}


?>
<?php

/**
 * @author Pierre - dev@net-tools.ovh
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
			$hc = (integer) round($height * $imgw / $width);	
			$wc = $imgw;
		}
		else					// portrait
		{
			$wc = (integer) round($width * $imgw / $height);
			$hc = $imgw;
		}
	
					
		// fill the background with a color
		imagefilledrectangle($img, 0, 0, $imgw, $imgw, $imgbkcolor);
		
		// $dst_image , $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h
		$r = imagecopyresampled($img, $source, (integer) floor(($imgw - $wc) / 2), (integer) floor(($imgw - $hc) / 2), 0, 0, $wc, $hc, $width, $height);
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
			$wc = (integer) round($width * $hmax / $height);
			
			// if width W is greater than max width, go on resize with the max width
			if ( $wc > $wmax )
			{
				$hc = (integer) round($hc * $wmax / $wc);
				$wc = $wmax;
			}
		}
		
		// if only max height is provided (max width is set to 0)
		else if ( $hmax )
		{
			$hc = $hmax;
			$wc = (integer) round($width * $hc / $height);
		}
	
	
		// if only max width is provided (max height is set to 0)
		else 
		{
			$wc = $wmax;
			$hc = (integer) round($height * $wc / $width);
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
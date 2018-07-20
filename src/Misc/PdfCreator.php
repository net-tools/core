<?php


namespace Nettools\Core\Misc;



/**
 * Class for PDF output
 */
class PdfCreator {
	
	protected $_tcpdf_config;
	
	
	
	/**
	 * Constructor
	 *
	 * @param string $tcpdf_config Full path to TCPDF config file (tcpdf_config.php file)
	 */
	public function __construct($tcpdf_config)
	{
		$this->_tcpdf_config = $tcpdf_config;
	}
	
	
	
	/**
	 * Create a PDF file from HTML content
	 * 
	 * @param \Nettools\Core\Misc\HtmlContent $content
	 * @param string $filename Full path to file to create
	 * @param string $title PDF title
	 * @param string $author
	 * @param string $orientation Orientation (L for Landscape, or P for Portrait - default)
	 * @param int $fontsize Default font size (10)
	 * @return string|false Returns full path of created file or FALSE if it has not been created
	 */
	public function create(\Nettools\Core\Misc\HtmlContent $content, $filename, $title, $author, $orientation = 'P', $fontsize = 10)
	{
		$pdfh = new \Nettools\Pdf\PdfHelper(
						// config file
						//Bootstrap::$root . '/lib/tcpdf/config/tcpdf_config.php',
						$this->_tcpdf_config,
						
						// orientation
						$orientation,
						
						// author, title, subject
						$author, $title, '',
						
						// fontsize
						$fontsize
					);
					

		$pdfh->addHTMLPage($content->html);

		$pdfh->output($filename);
		if ( file_exists($filename) )
			return $filename;
		else
			return false;	
	}	
}


?>
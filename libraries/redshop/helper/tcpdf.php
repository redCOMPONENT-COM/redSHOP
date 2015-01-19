<?php
/**
 * TCPDF Library file.
 * Including this file into your application will make redSHOP available to use.
 *
 * @package    TCPDF.Library
 * @copyright  Copyright (C) 2012 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Extends TCPDF
 *
 * @since  1.5
 */
class RedshopHelperTCPDF extends TCPDF
{
	// Page header
	public $img_file;

	/**
	 * This is the class constructor.
	 * It allows to set up the page format, the orientation and the measure unit used in all the methods (except for the font sizes).
	 *
	 * IMPORTANT: Please note that this method sets the mb_internal_encoding to ASCII, so if you are using the mbstring module functions with TCPDF you need to correctly set/unset the mb_internal_encoding when needed.
	 *
	 * @param   string  $orientation  page orientation. Possible values are (case insensitive):<ul><li>P or Portrait (default)</li><li>L or Landscape</li><li>'' (empty string) for automatic orientation</li></ul>
	 * @param   string  $unit         User measure unit. Possible values are:<ul><li>pt: point</li><li>mm: millimeter (default)</li><li>cm: centimeter</li><li>in: inch</li></ul><br />A point equals 1/72 of inch, that is to say about 0.35 mm (an inch being 2.54 cm). This is a very common unit in typography; font sizes are expressed in that unit.
	 * @param   string  $format       The format used for pages. It can be either: one of the string values specified at getPageSizeFromFormat() or an array of parameters specified at setPageFormat().
	 * @param   bool    $unicode      TRUE means that the input text is unicode (default = true)
	 * @param   string  $encoding     Charset encoding (used only when converting back html entities); default is UTF-8.
	 * @param   bool    $diskcache    DEPRECATED FEATURE
	 * @param   bool    $pdfa         If TRUE set the document to PDF/A mode.
	 */
	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
	{
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

		$this->setFontSubsetting(true);
		$this->SetFont('times', '', 12);
		$this->setHeaderFont(array('times', '', 10));
		$this->SetAuthor(JText::_('LIB_REDSHOP_PDF_CREATOR'));
		$this->SetCreator(JText::_('LIB_REDSHOP_PDF_CREATOR'));
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->SetMargins(8, 8, 8);
	}

	/**
	 * This method is used to render the page header.
	 * It is automatically called by AddPage() and could be overwritten in your own inherited class.
	 *
	 * @return  void
	 */
	public function Header()
	{
		if (file_exists($this->img_file))
		{
			// Full background image
			$this->SetAutoPageBreak(false, 0);
			$this->Image(
				$this->img_file, $x = 0, $y = 0, $w = 210, $h = 297, $type = '', $link = '', $align = '', $resize = false,
				$dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0
			);
			$this->SetAutoPageBreak($this->AutoPageBreak);
		}
		else
		{
			parent::Header();
		}
	}
}

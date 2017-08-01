<?php
/**
 * mPDF Library file.
 * Including this file into your application will make redSHOP available to use.
 *
 * @package    MPDF.Library
 * @copyright  Copyright (C) 2012 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

require __DIR__ . '/vendor/autoload.php';

/**
 * Extends mPDF
 *
 * @since  1.0.0
 */
class PlgRedshop_PdfMPDFHelper extends mPDF
{
	/**
	 * PlgRedshop_PdfMPDFHelper constructor.
	 *
	 * @param   string  $mode          Mode
	 * @param   string  $format        Page size
	 * @param   int     $fontSize      Font size
	 * @param   string  $font          Font family
	 * @param   int     $marginLeft    Page margin left
	 * @param   int     $marginRight   Page margin right
	 * @param   int     $marginTop     Page margin top
	 * @param   int     $marginBottom  Page margin bottom
	 * @param   int     $headerMargin  Page header margin
	 * @param   int     $footerMargin  Page footer margin
	 * @param   string  $orientation   Orientation mode.
	 */
	public function __construct($mode = 'utf-8', $format = 'A4', $fontSize = 10, $font = '', $marginLeft = 0, $marginRight = 0, $marginTop = 15,
		$marginBottom = 15, $headerMargin = 0, $footerMargin = 0, $orientation = 'P')
	{
		parent::__construct(
			array(
				$mode, $format, $fontSize, $font, $marginLeft, $marginRight, $marginTop, $marginBottom, $headerMargin, $footerMargin, $orientation
			)
		);

		$this->charset_in = 'utf-8';
		$this->SetAuthor(JText::_('LIB_REDSHOP_PDF_CREATOR'));
		$this->SetCreator(JText::_('LIB_REDSHOP_PDF_CREATOR'));
		$this->keep_table_proportions = true;
	}
}

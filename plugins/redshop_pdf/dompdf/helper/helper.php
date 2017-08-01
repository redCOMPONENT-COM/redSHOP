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

require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Extends domPDF
 *
 * @since  1.0.0
 */
class PlgRedshop_PdfDomPDFHelper extends Dompdf
{
	/**
	 * PlgRedshop_PdfMPDFHelper constructor.
	 *
	 * @param   string  $format       Page size
	 * @param   string  $font         Font family
	 * @param   string  $orientation  Orientation mode.
	 */
	public function __construct($format = 'A4', $font = 'Times', $orientation = 'P')
	{
		$options = new Options;
		$options->setIsFontSubsettingEnabled(true);
		$options->setDefaultFont($font);

		parent::__construct($options);

		$this->setPaper($format, $orientation);
	}
}

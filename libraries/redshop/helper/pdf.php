<?php
/**
 * @package     Redshop.Library
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * PDF helper.
 *
 * @package     Redshop.Library
 * @subpackage  Helpers
 * @since       1.0
 */
final class RedshopHelperPdf
{
	/**
	 * Returns a mPDF object, with inits done
	 *
	 * @return object PDF library
	 */
	public static function getInstance()
	{
		JLoader::import('tcpdf', JPATH_SITE . '/libraries/tcpdf');

		// Start pdf code
		$pdfObj = new TCPDF('utf-8', 'A4', '10', '', 15, 15, 15, 0, '', '', 'P');

		$pdfObj->charset_in = 'utf-8';
		$pdfObj->SetCreator(JText::_('COM_REDSHOPB_PDF_CREATOR'));
		$pdfObj->SetAuthor(JText::_('COM_REDSHOPB_PDF_CREATOR'));
		$pdfObj->keep_table_proportions = true;

		return $pdfObj;
	}
}

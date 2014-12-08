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
 * @since       1.5
 */
class RedshopHelperPdf
{
	/**
	 * @var    array  PDF instances container.
	 */
	protected static $instances = array();

	/**
	 * Returns a mPDF object, with inits done
	 *
	 * @param   string  $client   The name of the client
	 * @param   array   $options  Array of options
	 *
	 * @return object PDF library
	 */
	public static function getInstance($client = 'tcpdf', $options = array())
	{
		$className = strtoupper($client);

		if (!class_exists($className))
		{
			JLoader::import(strtolower($client) . '.library');
		}

		if (class_exists($className))
		{
			if (!isset($options['orientation']))
			{
				$options['orientation'] = 'P';
			}

			if (!isset($options['unit']))
			{
				$options['unit'] = 'mm';
			}

			if (!isset($options['format']))
			{
				$options['format'] = 'A5';
			}

			$pdfObj = new $className($options['orientation'], $options['unit'], $options['format']);
			$pdfObj->SetFont('freeserif', '', 12);
			$pdfObj->setHeaderFont(array('freeserif', '', 10));
			$pdfObj->SetAuthor(JText::_('LIB_REDSHOP_PDF_CREATOR'));
			$pdfObj->SetCreator(JText::_('LIB_REDSHOP_PDF_CREATOR'));
			$pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdfObj->SetMargins(8, 8, 8);
		}
		else
		{
			throw new RuntimeException(JText::sprintf('LIB_REDSHOP_APPLICATION_ERROR_PDF_LOAD', $client), 500);
		}

		return $pdfObj;
	}
}

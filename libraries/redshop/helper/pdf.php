<?php
/**
 * @package     Redshop.Library
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2012 - 2015 redCOMPONENT.com. All rights reserved.
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
	 * Get PDF Merger
	 *
	 * @return PDFMerger
	 */
	public static function getPDFMerger()
	{
		JLoader::import('helper.PDFMerger', JPATH_REDSHOP_LIBRARY);

		return new PDFMerger;
	}

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
		$className = $client;

		if (!class_exists($className))
		{
			JLoader::import(strtolower($client) . '.library');

			// If in library use namespace or difference class - we can set right class here
			if (defined(strtoupper($client) . '_LIBRARY_CLASS'))
			{
				$className = constant(strtoupper($client) . '_LIBRARY_CLASS');
			}
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

			if (!isset($options['helper']))
			{
				$options['helper'] = $client;
			}

			if (JLoader::import('helper.' . strtolower($options['helper']), JPATH_REDSHOP_LIBRARY))
			{
				$className = 'RedshopHelper' . $options['helper'];
			}

			return new $className($options['orientation'], $options['unit'], $options['format']);
		}
		else
		{
			throw new RuntimeException(JText::sprintf('LIB_REDSHOP_APPLICATION_ERROR_PDF_LOAD', $client), 500);
		}
	}
}

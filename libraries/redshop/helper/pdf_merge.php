<?php
/**
 * @package     Redshop.Library
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * PDF Merge helper.
 *
 * @package     Redshop.Library
 * @subpackage  Helpers
 * @since       2.0.7
 */
class RedshopHelperPdf_Merge
{
	/**
	 * @var  array  ['form.pdf']  ["1,2,4, 5-19"]
	 */
	private $files;

	/**
	 * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16.
	 *
	 * @param   string $filePath File path
	 * @param   string $pages    Page
	 *
	 * @return  self
	 *
	 * @throws  Exception
	 */
	public function addPDF($filePath, $pages = 'all')
	{
		if (!JFile::exists($filePath))
		{
			throw new exception('Could not locate PDF on: ' . $filePath);
		}

		if (strtolower($pages) != 'all')
		{
			$pages = $this->rewritePages($pages);
		}

		$this->files[] = array($filePath, $pages);

		return $this;
	}

	/**
	 * Merges your provided PDFs and outputs to specified location.
	 *
	 * @param   string $outputMode Output mode
	 * @param   string $outputPath Output path
	 *
	 * @return  boolean
	 *
	 * @throws  Exception
	 */
	public function merge($outputMode = 'browser', $outputPath = 'newfile.pdf')
	{
		if (!isset($this->files) || !is_array($this->files))
		{
			throw new exception("No PDFs to merge.");
		}

		$fpdi = new FPDI;

		// Merger operations
		foreach ($this->files as $file)
		{
			$fileName = $file[0];
			$pages    = $file[1];
			$count    = $fpdi->setSourceFile($fileName);

			// Add the pages
			if ($pages == 'all')
			{
				for ($i = 1; $i <= $count; $i++)
				{
					$template = $fpdi->importPage($i);
					$size     = $fpdi->getTemplateSize($template);

					$fpdi->AddPage('P', array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}
			else
			{
				foreach ($pages as $page)
				{
					if (!$template = $fpdi->importPage($page))
					{
						throw new exception("Could not load page '$page' in PDF '$fileName'. Check that the page exists.");
					}

					$size = $fpdi->getTemplateSize($template);

					$fpdi->AddPage('P', array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}
		}

		// Output operations
		$mode = $this->switchMode($outputMode);

		return $fpdi->Output($outputPath, $mode);
	}

	/**
	 * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
	 *
	 * @param   string  $mode  Mode
	 *
	 * @return  string
	 */
	private function switchMode($mode)
	{
		switch (strtolower($mode))
		{
			case 'download':
				return 'D';
				break;

			case 'browser':
				return 'I';
				break;

			case 'file':
				return 'F';
				break;

			case 'string':
				return 'S';
				break;

			default:
				return 'I';
				break;
		}
	}

	/**
	 * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
	 *
	 * @param   string  $pages  Page
	 *
	 * @return  mixed
	 *
	 * @throws  Exception
	 */
	private function rewritePages($pages)
	{
		$pages    = str_replace(' ', '', $pages);
		$parts    = explode(',', $pages);
		$newPages = array();

		if (empty($parts))
		{
			return $newPages;
		}

		// Parse hyphens
		foreach ($parts as $part)
		{
			$ind = explode('-', $part);

			if (count($ind) != 2)
			{
				$newPages[] = (int) $ind[0];

				continue;
			}

			// Start page
			$x = $ind[0];

			// End page
			$y = $ind[1];

			if ($x > $y)
			{
				throw new exception("Starting page, '$x' is greater than ending page '$y'.");
			}

			// Add middle pages
			while ($x <= $y)
			{
				$newPages[] = (int) $x;
				$x++;
			}
		}

		return $newPages;
	}
}

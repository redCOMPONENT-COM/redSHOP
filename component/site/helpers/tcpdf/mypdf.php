<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('eng', JPATH_COMPONENT_SITE . '/helpers/tcpdf/config/lang');
JLoader::import('tcpdf', JPATH_COMPONENT_SITE . '/helpers/tcpdf');

class MYPDF extends TCPDF
{
	// Page header
	public $img_file;

	public function Header()
	{
		// Full background image
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = $this->img_file;

		if (file_exists($img_file))
		{
			$this->Image($img_file, $x = 0, $y = 0, $w = 210, $h = 297, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0);
		}

		$this->SetAutoPageBreak($auto_page_break);
	}
}

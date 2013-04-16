<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class integrationModelintegration extends JModel
{
	/*
	 *  download googlebase xml file
	 */
	public function gbasedownload()
	{
		$file_path = JPATH_COMPONENT_SITE . "/assets/document/gbase/product.xml";

		if (!file_exists($file_path))
		{
			return false;
		}

		$xml_code = implode("", file($file_path));

		header("Content-Type: application/rss+xml");
		header('Content-Encoding: UTF-8');
		header('Content-Disposition: attachment; filename="product.xml"');
		echo  $xml_code;
		exit;
	}
}

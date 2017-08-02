<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewZip_import extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$layout = JFactory::getApplication()->input->getCmd('layout', '');

		if ($layout == 'confirmupdate')
		{
			$this->setLayout('confirmupdate');
		}
		else
		{
			$result = $this->get('Data');
			$this->result = $result;
		}

		parent::display($tpl);
	}
}

<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerSample_catalog extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->input->set('view', 'sample_catalog');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
	}
}

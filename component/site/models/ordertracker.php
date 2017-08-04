<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class ordertrackerModelordertracker
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelOrdertracker extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_template = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}
}

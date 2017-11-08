<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders a Order Status MultiSelect List
 *
 * @since  1.1
 */
class JFormFieldOrderStatus extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'orderstatus';

	/**
	 * Set select list options
	 *
	 * @return  string  select list options
	 */
	protected function getOptions()
	{
		// Load redSHOP Library
		JLoader::import('redshop.library');

		$orderStatus = RedshopHelperOrder::getOrderStatusList();

		// Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $orderStatus);
	}
}

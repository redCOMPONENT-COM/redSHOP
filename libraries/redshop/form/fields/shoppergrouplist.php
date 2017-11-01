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
 * Renders a Shopper Group MultiSelect List
 *
 * @since  1.1
 */
class JFormFieldShoppergrouplist extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'shoppergrouplist';

	/**
	 * Set select list options
	 *
	 * @return  string  select list options
	 */
	protected function getOptions()
	{
		// Load redSHOP Library
		JLoader::import('redshop.library');

		$userHelper = rsUserHelper::getInstance();
		$shopperGroups = Redshop\Helper\ShopperGroup::generateList();

		// Merge any additional options in the XML definition.
		$shopperGroups = array_merge(parent::getOptions(), $shopperGroups);

		return $shopperGroups;
	}
}

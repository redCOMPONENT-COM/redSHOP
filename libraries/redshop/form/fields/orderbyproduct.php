<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Renders a Product Orderby Fields
 *
 * @package  RedSHOP
 * @since    1.2
 */
class JFormFieldOrderByProduct extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'orderbyproduct';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		if (!$this->value)
		{
			$this->value = Redshop::getConfig()->get('DEFAULT_PRODUCT_ORDERING_METHOD');
		}

		return parent::getInput();
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		return array_merge(parent::getOptions(), RedshopHelperUtility::getOrderByList());
	}
}

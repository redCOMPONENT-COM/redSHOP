<?php
/**
 * @package     RedSHOP.Libraries
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Ajax Users Form Field
 *
 * @package     RedSHOP.Libraries
 * @subpackage  FormField
 * @since       2.0.0.4
 */

class JFormFieldRAjaxProducts extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    Public
	 * @var        string
	 */
	public $type = 'RAjaxProducts';

	/**
	 * getInput.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.0.0.4
	 */
	protected function getInput()
	{
		return JHTML::_('redshopselect.jFormSearch', $this->value, 'productid',
					array(
						'select2.ajaxOptions' => array('typeField' => ', product:1'),
						'select2.options' => array('placeholder' => JText::_('COM_REDSHOP_PRODUCT'))
					)
				);
	}
}

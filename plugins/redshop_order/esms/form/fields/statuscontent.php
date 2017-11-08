<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');
JLoader::import('redshop.library');

/**
 * Renders field status and content mapping
 *
 * @package  Joomla
 *
 * @since    2.0.3
 */
class JFormFieldStatuscontent extends JFormField
{
	/**
	 * The form field type
	 *
	 * @var    string
	 *
	 * @since  2.0.3
	 */
	protected $type = 'statuscontent';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$statusList = RedshopHelperOrder::getOrderStatusList();

		return RedshopLayoutHelper::render(
			'status_content',
			array(
				'statusList' => $statusList,
				'name'       => $this->name,
				'values'     => $this->value
			),
			JPATH_PLUGINS . '/redshop_order/esms/layouts'
		);
	}
}

<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Order Status
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.3
 */
class RedshopModelOrder_Status extends RedshopModelForm
{
	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 */
	public function getForm($data = [], $loadData = true)
	{
		/** @var JForm $form */
		$form = parent::getForm($data, $loadData);

		$id = (int) $form->getValue('order_status_id');

		if ($id)
		{
			$form->setFieldAttribute('order_status_code', 'readonly', true);
		}

		return $form;
	}
}

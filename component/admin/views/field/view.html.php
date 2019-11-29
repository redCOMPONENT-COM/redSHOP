<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

/**
 * View Field
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewField extends RedshopViewForm
{
	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	public function beforeDisplay(&$tpl)
	{
		/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.fields.min.js', false, true);

		// Initialise variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		if ($this->item->type == 15)
		{
			$this->form->setFieldAttribute('section', 'disabled', 1);
		}

		$this->item = $this->get('Item');

		$field_data = RedshopEntityField::getInstance($this->item->id)->getFieldValues();

		$list                = array();
		$lists['extra_data'] = $field_data;

		$this->lists = $lists;

		parent::beforeDisplay($tpl);
	}
}

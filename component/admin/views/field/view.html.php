<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * @param   string &$tpl Template name
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function beforeDisplay(&$tpl)
	{
		$extra_field = extra_field::getInstance();
		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/fields.js');

		$model = $this->getModel('field');

		// Initialise variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		if ($this->item->type == 15)
		{
			$this->form->setFieldAttribute('section', 'disabled', 1);
		}

		$this->item = $this->get('Item');

		$field_data = $extra_field->getFieldValue($this->item->id);

		$list = array();
		$lists['extra_data'] = $field_data;

		$this->lists = $lists;

		parent::beforeDisplay($tpl);
	}

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_FIELD_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}

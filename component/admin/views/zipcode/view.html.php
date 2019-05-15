<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * View zipcode
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.0
 */
class RedshopViewZipcode extends RedshopViewForm
{
	/**
	 * Method for prepare field HTML
	 *
	 * @param   object  $field  Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 *
	 * @since   2.1.0
	 * @throws \Exception
	 */
	protected function prepareField($field)
	{
		$input = JFactory::getApplication()->input;
		$id    = $input->getInt('id', '');

		if ($id && $field->getAttribute('name') == 'zipcodeto')
		{
			return false;
		}

		if ($field->getAttribute('name') == 'zipcode')
		{
			$this->form->setFieldAttribute('zipcode', 'label', JText::_('COM_REDSHOP_FROM'));
		}

		return parent::prepareField($field);
	}
}

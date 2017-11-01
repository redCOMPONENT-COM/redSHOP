<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');

/**
 * Renders a Credit Card Form
 *
 * @package        RedSHOP.Backend
 * @subpackage     Element
 * @since          1.5
 */
class JFormFieldCreditCards extends JFormFieldCheckboxes
{
	/**
	 * Element name
	 *
	 * @var  string
	 */
	protected $type = 'creditcards';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		JFactory::getLanguage()->load('com_redshop');

		$cardTypes             = array();
		$cardTypes['VISA']     = JText::_('COM_REDSHOP_CARD_TYPE_VISA');
		$cardTypes['MC']       = JText::_('COM_REDSHOP_CARD_TYPE_MASTERCARD');
		$cardTypes['amex']     = JText::_('COM_REDSHOP_CARD_TYPE_AMERICAN_EXPRESS');
		$cardTypes['maestro']  = JText::_('COM_REDSHOP_CARD_TYPE_MAESTRO');
		$cardTypes['jcb']      = JText::_('COM_REDSHOP_CARD_TYPE_JCB');
		$cardTypes['diners']   = JText::_('COM_REDSHOP_CARD_TYPE_DINERS_CLUB');
		$cardTypes['discover'] = JText::_('COM_REDSHOP_CARD_TYPE_DISCOVER');

		// Allow parent options - This will extends the options added directly from XML
		$options = parent::getOptions();

		foreach ($cardTypes as $value => $text)
		{
			$tmp = JHtml::_(
				'select.option',
				$value,
				$text,
				'value',
				'text'
			);

			// Set some option attributes.
			$tmp->checked = false;

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		return $options;
	}
}

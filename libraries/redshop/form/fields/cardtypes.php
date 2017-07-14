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
 * Prepare checkboxes for different card types
 *
 * @package        RedShop.Backend
 * @subpackage     Element
 * @since          1.1
 */
class JFormFieldCardTypes extends JFormFieldCheckboxes
{
	/**
	 * Element name
	 *
	 * @var   string
	 */
	public $type = 'cardtypes';

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

		$cardTypes                        = array();
		$cardTypes['ALL']                 = 'COM_REDSHOP_CARD_TYPE_ALL';
		$cardTypes['DANKORT']             = 'COM_REDSHOP_CARD_TYPE_DANKORT';
		$cardTypes['VD']                  = 'COM_REDSHOP_CARD_TYPE_VD';
		$cardTypes['VE']                  = 'COM_REDSHOP_CARD_TYPE_VE';
		$cardTypes['MCDK']                = 'COM_REDSHOP_CARD_TYPE_MCDK';
		$cardTypes['MC']                  = 'COM_REDSHOP_CARD_TYPE_MC';
		$cardTypes['VEDK']                = 'COM_REDSHOP_CARD_TYPE_VEDK';
		$cardTypes['JCB']                 = 'COM_REDSHOP_CARD_TYPE_JCB';
		$cardTypes['DDK']                 = 'COM_REDSHOP_CARD_TYPE_DDK';
		$cardTypes['MDK']                 = 'COM_REDSHOP_CARD_TYPE_MDK';
		$cardTypes['AEDK']                = 'COM_REDSHOP_CARD_TYPE_AEDK';
		$cardTypes['DINERS']              = 'COM_REDSHOP_CARD_TYPE_DINERS';
		$cardTypes['JCBS']                = 'COM_REDSHOP_CARD_TYPE_JCBS';
		$cardTypes['AE']                  = 'COM_REDSHOP_CARD_TYPE_AE';
		$cardTypes['MAESTRO']             = 'COM_REDSHOP_CARD_TYPE_MAESTRO';
		$cardTypes['FORBRUGSFORENINGEN']  = 'COM_REDSHOP_CARD_TYPE_FORBRUGSFORENINGEN';
		$cardTypes['EWIRE']               = 'COM_REDSHOP_CARD_TYPE_EWIRE';
		$cardTypes['VISA']                = 'COM_REDSHOP_CARD_TYPE_VISA';
		$cardTypes['IKANO']               = 'COM_REDSHOP_CARD_TYPE_IKANO';
		$cardTypes['NORDEA']              = 'COM_REDSHOP_CARD_TYPE_NORDEA';
		$cardTypes['DB']                  = 'COM_REDSHOP_CARD_TYPE_DB';
		$cardTypes['IKANO']               = 'COM_REDSHOP_CARD_TYPE_IKANO';
		$cardTypes['MASTERCARDDEBETCARD'] = 'COM_REDSHOP_CARD_TYPE_MASTERCARDDEBETCARD';
		$cardTypes['PAII']                = 'COM_REDSHOP_CARD_TYPE_PAII';
		$cardTypes['VIABILL']             = 'COM_REDSHOP_CARD_TYPE_VIABILL';

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

		JFactory::getDocument()->addScriptDeclaration('
			jQuery(document).ready(function (){
				jQuery("#jform_params_cardtypes0").click(function(event) {
					jQuery("[id^=jform_params_cardtypes]").attr("checked", jQuery(this).get(0).checked);
				});
			});'
		);

		return $options;
	}
}

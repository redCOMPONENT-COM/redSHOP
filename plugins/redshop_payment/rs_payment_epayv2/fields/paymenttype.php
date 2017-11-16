<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
class JFormFieldPaymentType extends JFormFieldCheckboxes
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'PaymentType';

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

		$cardTypes        = array();
		$cardTypes['ALL'] = 'COM_REDSHOP_CARD_TYPE_ALL';
		$cardTypes['1']   =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_DANKORT';
		$cardTypes['2']   =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_DANKORT_EDANKORT';
		$cardTypes['3']   =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_VISA';
		$cardTypes['4']   =	'COM_REDSHOP_CARD_TYPE_MC';
		$cardTypes['6']   =	'COM_REDSHOP_CARD_TYPE_JCB';
		$cardTypes['7']   =	'COM_REDSHOP_CARD_TYPE_MAESTRO';
		$cardTypes['8']   =	'COM_REDSHOP_CARD_TYPE_DINERS';
		$cardTypes['9']   =	'COM_REDSHOP_CARD_TYPE_AE';
		$cardTypes['11']  =	'COM_REDSHOP_CARD_TYPE_FORBRUGSFORENINGEN';
		$cardTypes['12']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_NORDEA';
		$cardTypes['13']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_DANSKE';
		$cardTypes['14']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_PAYPAL';
		$cardTypes['17']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_KLARNA';
		$cardTypes['18']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_SVEAWEBPAY';
		$cardTypes['23']  =	'COM_REDSHOP_CARD_TYPE_VIABILL';
		$cardTypes['24']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_BEEPTIFY';
		$cardTypes['25']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_IDEAL';
		$cardTypes['27']  =	'COM_REDSHOP_CARD_TYPE_PAII';
		$cardTypes['28']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_BRANDTS_GAVEKORT';
		$cardTypes['29']  =	'PLG_RS_PAYMENT_EPAYV2_CARD_TYPE_MOBILEPAY_ONLINE';

		// Allow parent options - This will extends the options added directly from XML
		$options = parent::getOptions();

		foreach ($cardTypes as $value => $text)
		{
			$tmp = JHtml::_(
				'select.option',
				$value,
				JText::_($text),
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
				jQuery("#jform_params_paymenttype0").click(function(event) {
					jQuery("[id^=jform_params_paymenttype]").attr("checked", jQuery(this).get(0).checked);
				});
			});
		');

		return $options;
	}
}

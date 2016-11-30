<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');

/**
 * Renders a Productfinder Form
 *
 * @package     RedSHOP.Backend
 * @subpackage  Element
 * @since       1.5
 */
class JFormFieldTicket extends JFormFieldText
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	protected $type = 'ticket';

	protected $secretWord = null;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		JText::script('PLG_REDSHOP_PAYMENT_QUICKBOOK_APP_ID_REQUIRED');
		RedshopHelperConfig::script('SITE_URL', JUri::root());

		// Get system plugin params if available else return an error
		$quickBookSystem = JPluginHelper::getPlugin('system', 'quickbook');

		if (empty($quickBookSystem))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_SYSTEM'), 'error');
		}
		else
		{
			$quickBookSystemParams = new JRegistry($quickBookSystem->params);
			$this->secretWord      = $quickBookSystemParams->get('secretWord', false);

			if (!$this->secretWord)
			{
				JFactory::getApplication()->enqueueMessage(
					JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_SYSTEM_SECRET_WORD'),
					'error'
				);
			}

			RedshopHelperConfig::script('SECRET_WORD', $this->secretWord);
		}

		JHtml::stylesheet('plg_redshop_payment_quickbook/quickbook.css', false, true);
		JHtml::script('plg_redshop_payment_quickbook/quickbook.js', false, true);

		$parentInput = parent::getInput();

		$html[] = RedshopLayoutHelper::render(
				'ticket',
				[
					'parentInput' => $parentInput
				],
				JPATH_SITE . '/plugins/redshop_payment/quickbook/layouts'
			);

		$html[] = $this->getModalHtml();

		return implode($html);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	private function getModalHtml()
	{
		$title = JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_GET_CONNECTION_TICKET_TITLE');
		$userAgreementText   = JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_USER_AGREEMENT_TEXT');
		$connectionTicketButtonTxt = JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_CONN_TICKET_BUTTON_TEXT');

		$subscriptionUrl = JUri::root() . 'index.php?option=com_redshop&tmpl=component&secret=' . $this->secretWord . '&control=setConnectionTicket';

		$html = RedshopLayoutHelper::render(
				'modal_certificate',
				[
					'title' 					=> $title,
					'connectionTicketButtonTxt' => $connectionTicketButtonTxt,
					'subscriptionUrl' 			=> $subscriptionUrl,
					'userAgreementText'			=> $userAgreementText
				],
				JPATH_SITE . 'plugins/redshop_payment/quickbook/layouts/layouts'
			);

		return $html;
	}
}
